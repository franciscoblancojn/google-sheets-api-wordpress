<?php

use franciscoblancojn\wordpress_utils\FWUSystemLog;

function tooltip($text)
{
    return '
    <span class="goshap-tooltip">
        <span class="dashicons dashicons-info"></span>
        <span class="goshap-tooltip-text">' . $text . '</span>
    </span>';
}
if (isset($_POST['save']) && $_POST['save'] == 1) {
    if (isset($_POST['AUTH_CONFIG'])) {
        $_POST['AUTH_CONFIG'] = json_decode(stripslashes($_POST['AUTH_CONFIG']), true);
    }
    FWUSystemLog::add(GOSHAP_KEY, [
        'type' => "save_config",
        'data' => $_POST
    ]);
    update_option(GOSHAP_CONFIG, $_POST);
}
$CONFIG = get_option(GOSHAP_CONFIG, []);
?>
<form method="post">
    <input type="hidden" name="save" value="1">
    <table class="form-table">
        <tr>
            <th scope="row">
                <label for="APP_NAME">
                    APP_NAME
                    <?= tooltip('Nombre de la aplicación. Solo es usado internamente para identificar el cliente de Google.') ?>
                </label>
            </th>
            <td>
                <input type="text" id="APP_NAME" name="APP_NAME"
                    placeholder="Name App"
                    value="<?= esc_attr($CONFIG['APP_NAME'] ?? 'Sheets API PHP') ?>"
                    class="regular-text">
            </td>
        </tr>

        <tr>
            <th scope="row">
                <label for="SHEETNAME">
                    SHEETNAME
                    <?= tooltip('Nombre de la hoja dentro del spreadsheet (ej: Hoja1, Sheet1, etc).') ?>
                </label>
            </th>
            <td>
                <input type="text" id="SHEETNAME" name="SHEETNAME"
                    placeholder="Name"
                    value="<?= esc_attr($CONFIG['SHEETNAME'] ?? '') ?>"
                    class="regular-text">
            </td>
        </tr>

        <tr>
            <th scope="row">
                <label for="SPREADSHEET_ID">
                    SPREADSHEET_ID
                    <?= tooltip('Lo encuentras en la URL del Google Sheet: https://docs.google.com/spreadsheets/d/ESTE_ID/edit') ?>
                </label>
            </th>
            <td>
                <input type="password" id="SPREADSHEET_ID" name="SPREADSHEET_ID"
                    placeholder="xxxxxxxxxxx"
                    value="<?= esc_attr($CONFIG['SPREADSHEET_ID'] ?? '') ?>"
                    class="regular-text">
            </td>
        </tr>

        <tr>
            <th scope="row">
                <label for="AUTH_CONFIG">
                    AUTH_CONFIG
                    <?= tooltip('JSON del Service Account de Google Cloud. Se descarga al crear credenciales tipo "Service Account".') ?>
                </label>
            </th>
            <td>
                <textarea
                    id="AUTH_CONFIG"
                    name="AUTH_CONFIG"
                    onchange="onSaveTextArea()"
                    oninput="onSaveTextArea()"
                    placeholder="{.....}"
                    class="large-text code"
                    style="min-height: 500px;"
                    rows="8"><?= esc_textarea($CONFIG['AUTH_CONFIG'] ?
                                    json_encode($CONFIG['AUTH_CONFIG'], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
                                    :
                                    '') ?></textarea>
            </td>
        </tr>
        <?php
        if (isset($CONFIG['AUTH_CONFIG']['client_email'])) {
        ?>
            <tr>
                <th>
                    PERMISOS
                </th>
                <td>
                    Es importante dale permisos de editor a <strong><?= $CONFIG['AUTH_CONFIG']['client_email'] ?></strong>
                </td>
            </tr>
        <?php
        }

        ?>
    </table>
    <style>
        .goshap-tooltip {
            position: relative;
            cursor: pointer;
            margin-left: 6px;
            display: inline-block;
        }

        .goshap-tooltip-text::after {
            content: "";
            position: absolute;
            top: 100%;
            left: 10px;
            border-width: 5px;
            border-style: solid;
            border-color: #1d2327 transparent transparent transparent;
        }

        .goshap-tooltip-text {
            visibility: hidden;
            opacity: 0;
            width: 360px;
            background: #1d2327;
            color: #fff;
            text-align: left;
            padding: 8px;
            border-radius: 6px;
            position: absolute;
            z-index: 9999;
            bottom: 125%;
            left: 0;
            transition: opacity 0.2s ease;
            font-size: 12px;
            line-height: 1.4;
        }

        .goshap-tooltip:hover .goshap-tooltip-text {
            visibility: visible;
            opacity: 1;
        }
    </style>

    <?php submit_button('Guardar'); ?>
</form>
<?php
