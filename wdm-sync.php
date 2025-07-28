<?php
/**
 * Plugin Name: WDM Sync
 * Plugin URI: https://example.com/wdm-sync
 * Description: A basic WordPress plugin with admin settings page
 * Version: 1.0.0
 * Author: Your Name
 * Author URI: https://example.com
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: wdm-sync
 * Domain Path: /languages
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Include custom REST API endpoint
require_once __DIR__ . '/includes/api-sync.php';

// Include sync workflow
require_once __DIR__ . '/includes/sync-workflow.php';

// Add admin menu
add_action('admin_menu', 'wdm_sync_add_admin_menu');

function wdm_sync_add_admin_menu() {
    add_menu_page(
        'WDM Sync',
        'WDM Sync',
        'manage_options',
        'wdm-sync-settings',
        'wdm_sync_admin_page',
        'dashicons-update',
        30
    );
}

// Initialize settings
add_action('admin_init', 'wdm_sync_init_settings');

function wdm_sync_init_settings() {
    register_setting('wdm_sync_options', 'wdm_sync_settings');
}

// Include admin page frontend
require_once __DIR__ . '/includes/admin-page.php';

// Activation hook
register_activation_hook(__FILE__, 'wdm_sync_activate');

function wdm_sync_activate() {
    // Set default options
    $default_settings = array(
        'live_site_url' => '',
        'schedule_enabled' => false,
        'schedule_interval' => 'hourly'
    );
    
    add_option('wdm_sync_settings', $default_settings);
    
    // Clear any existing scheduled events
    wdm_sync_clear_cron();
}

// Deactivation hook
register_deactivation_hook(__FILE__, 'wdm_sync_deactivate');

function wdm_sync_deactivate() {
    // Clear scheduled events
    wdm_sync_clear_cron();
}