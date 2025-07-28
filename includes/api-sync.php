<?php
// Register custom REST API endpoint for WDM Sync
if (function_exists('add_action')) {
    add_action('rest_api_init', function () {
        register_rest_route('wdm-sync/v1', '/start', array(
            'methods' => 'POST',
            'callback' => 'wdm_sync_start_callback',
            'permission_callback' => '__return_true', // Allow public access for testing
        ));
    });
}

if (!function_exists('wdm_sync_start_callback')) {
    function wdm_sync_start_callback($request) {
        return new WP_REST_Response([
            'success' => true,
            'message' => 'Sync started successfully.'
        ], 200);
    }
}
