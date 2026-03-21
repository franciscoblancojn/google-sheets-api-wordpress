<?php

// use franciscoblancojn\wordpress_utils\FWUSystemLog;
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
    require_once GOSHAP_DIR . 'src/page/page.php';
}
