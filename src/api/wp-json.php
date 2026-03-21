<?php

use franciscoblancojn\wordpress_utils\FWUSystemLog;

class GOSHAP_WP_JSON
{
    public static function api()
    {
        $CONFIG = get_option(GOSHAP_CONFIG, []);
        $api = new GOSHAP_Api($CONFIG);
        return $api;
    }
    public static function init()
    {
        register_rest_route(GOSHAP_KEY, '/send-rows', [
            'methods' => 'POST',
            'callback' => [self::class, 'sendRows'],
        ]);
    }

    public static function sendRows($request)
    {
        $api = self::api();
        $values = $request->get_json_params();
        $result = $api->sendRows($values);
        FWUSystemLog::add(GOSHAP_KEY, [
            'type' => "send_rows",
            'send' => $values,
            'result' => $result,
        ]);
        return $result;
    }
}

add_action('rest_api_init', ['GOSHAP_WP_JSON', 'init']);
