<?php

function GOSHAP_LOG_GET()
{
    if (!GOSHAP_LOG) {
        return [];
    }
    $log = get_option(GOSHAP_LOG_KEY, "[]");
    if ($log === false || $log == null || $log == "") {
        $log = "[]";
    }
    $log = json_decode($log, true);
    return $log;
}

function GOSHAP_LOG_ADD($newLog)
{
    if (!GOSHAP_LOG) {
        return;
    }
    $type = str_replace(" ", "_", strtoupper($newLog['type'] ?? "NO_TIPE"));
    $log = GOSHAP_LOG_GET();
    $log[$type] ??= [];
    $log[$type][] = $newLog;
    $log[$type] = array_slice($log[$type], -1 * GOSHAP_LOG_COUNT, GOSHAP_LOG_COUNT);
    update_option(GOSHAP_LOG_KEY, json_encode($log));
}

if (GOSHAP_LOG) {
    function GOSHAP_LOG_PAGE_OPTION_PAGE_ADD_BAR($admin_bar)
    {
        global $pagenow;
        $admin_bar->add_menu(
            array(
                'id' => GOSHAP_LOG_KEY,
                'title' => GOSHAP_LOG_KEY,
                'href' => get_site_url() . '/wp-admin/options-general.php?page='.GOSHAP_LOG_KEY
            )
        );
    }

    function GOSHAP_LOG_PAGE_OPTION_PAGE()
    {
        add_options_page(
            GOSHAP_LOG_KEY,
            GOSHAP_LOG_KEY,
            'manage_options',
            GOSHAP_LOG_KEY,
            'GOSHAP_LOG_PAGE_VIEW'
        );
    }

    function GOSHAP_LOG_PAGE_VIEW()
    {
        try {
            if ($_POST['clear-log'] == "1") {
                update_option(GOSHAP_LOG_KEY, "[]");
            }
            $log = GOSHAP_LOG_GET();
            ?>

            <div
                class="GOSHAP_LOG_PAGE_VIEW"
            >
            <div
                class="GOSHAP_LOG_PAGE_VIEW_HEADER"
            >
                <h1 style="color:inherit">
                    Solo se guardan las <?= GOSHAP_LOG_COUNT ?> peticiones por tipo
                </h1>
                <form method="post" style="margin-left: auto;">
                    <button class="button page-title-action">Recargar</button>
                </form>
                <form method="post">
                    <input type="hidden" name="clear-log" value="1">
                    <button class="button page-title-action">Borrar Log</button>
                </form>
                
            </div>
            <script>
                function copyJson(id) {
                    const element = document.getElementById(id);
                    const text = element.innerText;

                    // Crear textarea temporal
                    const textarea = document.createElement("textarea");
                    textarea.value = text;
                    document.body.appendChild(textarea);

                    textarea.select();
                    textarea.setSelectionRange(0, 999999); // Para mobile

                    try {
                        document.execCommand("copy");
                        showCopiedMessage(id);
                    } catch (err) {
                        console.error("Error al copiar", err);
                    }

                    document.body.removeChild(textarea);
                }

                function showCopiedMessage(id) {
                    const btn = document.querySelector(`[onclick="copyJson('${id}')"]`);
                    if (!btn) return;

                    const original = btn.innerText;
                    btn.innerText = "Copiado ✅";

                    setTimeout(() => {
                        btn.innerText = original;
                    }, 1500);
                }
                const json_log = <?= wp_json_encode($log) ?>;
            </script>
            <style>
                *:has(  .GOSHAP_LOG_PAGE_VIEW_HEADER){
                    position: static;
                }
                #wpbody-content > *:not(.GOSHAP_LOG_PAGE_VIEW){
                    display: none !important;
                }
                #wpcontent{
                    position: relative;
                }
                .GOSHAP_LOG_PAGE_VIEW_HEADER{
                    /* position: fixed;
                    top:1rem;
                    left: 0; */
                    /* width: 100%; */
                    padding: .25rem 1.5rem;
                    margin-bottom: 1rem;
                    z-index:1000;
                    display:flex;
                    gap:1rem;
                    align-items: center;
                    background: #1d2327;
                    color: #f0f0f1;
                    box-shadow: -20px 0 #1d2327;
                }
                /* Contenedor general */
                details {
                    margin-bottom: 1rem;
                    border: 1px solid #dcdcde;
                    border-radius: 8px;
                    background: #fff;
                    overflow: hidden;
                }

                /* Header tipo collapse */
                details summary {
                    cursor: pointer;
                    padding: 12px 16px;
                    font-weight: 600;
                    font-size: 14px;
                    background: #f6f7f7;
                    list-style: none;
                    position: relative;
                    transition: background 0.2s ease;
                }

                /* Hover */
                details summary:hover {
                    background: #e5e5e5;
                }

                /* Quitar flecha default */
                details summary::-webkit-details-marker {
                    display: none;
                }

                /* Flecha custom */
                details summary::after {
                    content: "▸";
                    position: absolute;
                    right: 16px;
                    font-size: 14px;
                    transition: transform 0.2s ease;
                }

                /* Rotar cuando está abierto */
                details[open] summary::after {
                    transform: rotate(90deg);
                }

                /* Contenido interno */
                details>div {
                    padding: 16px;
                    background: #ffffff;
                    border-top: 1px solid #dcdcde;
                    max-height: 75dvh;
                    overflow: auto;
                }
            </style>
            <?php
            foreach ($log as $key => $value) {
            ?>
                <details>
                    <summary style="display: flex;">
                        <span><?= $key ?> </span>
                        <span style="margin-left: auto; margin-right:1rem">(<?= count($value) ?>)</span>
                    </summary>
                    <div>
                        <?php
                        for ($i = 0; $i < count($value); $i++) {
                            $print = wp_json_encode(
                                array_reverse($value[$i]),
                                JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
                            );

                            $id = 'json_' . $key . '_' . $i;
                        ?>
                            <div style="position:relative;margin-bottom:1rem;">

                                <button
                                    type="button"
                                    onclick="copyJson('<?= $id ?>')"
                                    style="
                                    position:absolute;
                                    top:.5rem;
                                    right:.5rem;
                                    cursor:pointer;
                                    background:#00ff88;
                                    border:0;
                                    padding:.25rem .75rem;
                                    border-radius:.35rem;
                                    font-weight:bold;
                                ">
                                    Copiar
                                </button>

                                <pre
                                    id="<?= $id ?>"
                                    style="background:#1d2327;color:#00ff88;padding:1rem;border-radius:.5rem;overflow:auto;"><?= esc_html($print) ?></pre>

                            </div>
                        <?php
                        }
                        ?>
                    </div>
                </details>
            <?php
            }
            ?>
            </div>
            <?php
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
    add_action('admin_bar_menu', 'GOSHAP_LOG_PAGE_OPTION_PAGE_ADD_BAR', 100);

    add_action('admin_menu', 'GOSHAP_LOG_PAGE_OPTION_PAGE');
}
