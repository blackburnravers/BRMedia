<?php
if (!defined('ABSPATH')) exit;

class BRMedia_REST_API {
    public function register_routes() {
        // Basic ping endpoint for testing
        register_rest_route('brmedia/v1', '/ping', [
            'methods' => 'GET',
            'callback' => [$this, 'ping'],
            'permission_callback' => '__return_true'
        ]);
    }

    public function ping() {
        return new WP_REST_Response([
            'status' => 'active',
            'version' => BRMEDIA_VERSION
        ], 200);
    }
}