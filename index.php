<?php
/*
Plugin Name: Google Sheets Api
Plugin URI: https://github.com/franciscoblancojn/google-sheets-api-wordpress
Description: It is an plugin of wordpress, for send information to google sheets.
Version: 1.2.1
Author: franciscoblancojn
Author URI: https://franciscoblanco.vercel.app/
License: GPL2+
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: wc-google-sheets-api-wordpress
*/

if (!function_exists( 'is_plugin_active' ))
    require_once( ABSPATH . '/wp-admin/includes/plugin.php' );

require_once __DIR__ . '/vendor/franciscoblancojn/wordpress_utils/src/FWUSystemLog.php';
//GOSHAP_
define("GOSHAP_KEY",'GOSHAP');
define("GOSHAP_CONFIG",'GOSHAP_CONFIG');
define("GOSHAP_LOG",true);
define("GOSHAP_LOG_KEY","GOSHAP_LOG");
define("GOSHAP_LOG_COUNT",100);
define("GOSHAP_BASENAME",plugin_basename(__FILE__));
define("GOSHAP_DIR",plugin_dir_path( __FILE__ ));
define("GOSHAP_URL",plugin_dir_url(__FILE__));

//importar libreria
// add_system_log("GOSHAP")


require_once GOSHAP_DIR . 'update.php';
github_updater_plugin_wordpress([
    'basename'=>GOSHAP_BASENAME,
    'dir'=>GOSHAP_DIR,
    'file'=>"index.php",
    'path_repository'=>'franciscoblancojn/google-sheets-api-wordpress',
    'branch'=>'master',
    'token_array_split'=>[
        "g",
        "h",
        "p",
        "_",
        "G",
        "4",
        "W",
        "E",
        "W",
        "F",
        "p",
        "V",
        "U",
        "E",
        "F",
        "V",
        "x",
        "F",
        "U",
        "n",
        "b",
        "M",
        "k",
        "P",
        "R",
        "x",
        "o",
        "f",
        "t",
        "Y",
        "8",
        "z",
        "j",
        "t",
        "4",
        "E",
        "x",
        "b",
        "i",
        "9"
    ]
]);

use franciscoblancojn\wordpress_utils\FWUSystemLog;
FWUSystemLog::init("GOSHAP");

require_once GOSHAP_DIR . 'src/_.php';

// FWUSystemLog::add("GOSHAP", [
//     "type" => "API",
//     "message" => "Se envió data a Google Sheets",
//     "data" => ["id" => 123]
// ]);