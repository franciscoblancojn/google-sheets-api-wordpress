<?php

use franciscoblancojn\wordpress_utils\FWUSystemLog;
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
                    rows="8"><?= esc_textarea($CONFIG['AUTH_CONFIG'] ?
                                    json_encode($CONFIG['AUTH_CONFIG'], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
                                    :
                                    '') ?></textarea>
            </td>
        </tr>
        <?php
            if(isset($CONFIG['AUTH_CONFIG']['client_email'])){
                ?>
                <tr>
                    <th>
                        PERMISOS
                    </th>
                    <td>
                        Es importante dale permisos de editor a <strong><?=$CONFIG['AUTH_CONFIG']['client_email']?></strong>
                    </td>
                </tr>
                <?php
            }
        
        ?>
    </table>


    <?php submit_button('Guardar'); ?>
</form>
<?php
