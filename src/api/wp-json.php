<?php

use franciscoblancojn\wordpress_utils\FWUSystemLog;

class GOSHAP_WP_JSON
{
    
    public static function api($key)
    {
        $CONFIG = get_option(GOSHAP_CONFIG, []);
        $connections = $CONFIG['connections'] ?? [];

        foreach ($connections as $conn) {
            if (isset($conn['KEY']) && $conn['KEY'] === $key) {
                return new GOSHAP_Api($conn);
            }
        }
        return null;
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
        // 🔥 obtener key desde query param ?k=
        $key = $request->get_param('k');
        if (!$key) {
            return [
                "status" => "error",
                'message' => 'Key (k) requerida'
            ];
        }
        $api = self::api($key);
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
