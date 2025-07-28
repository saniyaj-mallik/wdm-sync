<?php
// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Simple sync workflow function
 * This function handles the synchronization process
 */
function wdm_sync_workflow() {
    // Get settings
    $settings = get_option('wdm_sync_settings', array());
    $live_site_url = isset($settings['live_site_url']) ? $settings['live_site_url'] : '';
    
    // Simulate sync process (you can add your actual sync logic here)
    $sync_data = array(
        'timestamp' => current_time('mysql'),
        'live_site_url' => $live_site_url,
        'status' => 'completed',
        'message' => 'Sync workflow completed successfully'
    );
    
    // Store sync result in options for admin page display
    update_option('wdm_sync_last_result', $sync_data);
    
    // Log the sync
    error_log('WDM Sync: Workflow completed at ' . $sync_data['timestamp']);
    
    return $sync_data;
}

/**
 * Get the last sync result for display in admin
 */
function wdm_sync_get_last_result() {
    return get_option('wdm_sync_last_result', array());
}

/**
 * Schedule the cron job for automatic sync
 */
function wdm_sync_schedule_cron($interval = 'hourly') {
    // Clear any existing scheduled events first
    wdm_sync_clear_cron();
    
    // Schedule the new cron event
    if (!wp_next_scheduled('wdm_sync_cron')) {
        wp_schedule_event(time(), $interval, 'wdm_sync_cron');
    }
}

/**
 * Clear the scheduled cron job
 */
function wdm_sync_clear_cron() {
    wp_clear_scheduled_hook('wdm_sync_cron');
}

/**
 * Cron callback function - this is what runs when the cron executes
 */
function wdm_sync_cron_callback() {
    // Run the sync workflow
    $result = wdm_sync_workflow();
    
    // Log the cron execution
    error_log('WDM Sync: Cron executed at ' . current_time('mysql') . ' - Status: ' . $result['status']);
}

// Hook the cron callback to the scheduled event
add_action('wdm_sync_cron', 'wdm_sync_cron_callback'); 