<?php

use franciscoblancojn\wordpress_utils\FWUSystemLog;
// 1. Crear menú en el admin
add_action('admin_menu', function () {
    add_menu_page(
        'Google Sheets Api Configuración', // Título página
        'Google Sheets Api',              // Nombre en menú
        'manage_options',        // Permisos
        GOSHAP_KEY,      // Slug
        'GOSHAP_PAGE_VIEW'  // Callback
    );
});

// 2. Página HTML
function GOSHAP_PAGE_VIEW()
{
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
    if (isset($CONFIG['AUTH_CONFIG'])) {
        $CONFIG['AUTH_CONFIG'] = json_encode($CONFIG['AUTH_CONFIG'],JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }
    
?>
    <div class="wrap">
        <h1>Google Sheets Api</h1>

        <form method="post">
            <input type="hidden" name="save" value="1">
            <table class="form-table">
                <tr>
                    <th scope="row">
                        <label for="APP_NAME">APP_NAME</label>
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
                        <label for="SHEETNAME">SHEETNAME</label>
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
                        <label for="SPREADSHEET_ID">SPREADSHEET_ID</label>
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
                        <label for="AUTH_CONFIG">AUTH_CONFIG</label>
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
                            rows="8"><?= esc_textarea($CONFIG['AUTH_CONFIG'] ?? '') ?></textarea>
                    </td>
                </tr>
            </table>

            <?php submit_button('Guardar'); ?>
        </form>
    </div>
<?php
}
