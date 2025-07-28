<?php
/**
 * Uninstall WDM Sync Plugin
 * 
 * This file is executed when the plugin is deleted from WordPress.
 * It cleans up all plugin data from the database.
 */

// If uninstall not called from WordPress, exit
if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

// Delete plugin options
delete_option('wdm_sync_settings');

// Delete any transients created by the plugin
delete_transient('wdm_sync_cache');

// Clear any scheduled events
wp_clear_scheduled_hook('wdm_sync_cron');

// Remove any custom database tables if they exist
global $wpdb;

// Example: Remove custom table (uncomment if you create custom tables)
// $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}wdm_sync_logs");

// Clear any cached data
wp_cache_flush(); 