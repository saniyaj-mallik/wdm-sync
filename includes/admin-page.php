<?php
// Admin page content for WDM Sync
function wdm_sync_admin_page() {
    if (!current_user_can('manage_options')) {
        return;
    }
    
    // Handle form submission
    if (isset($_POST['submit'])) {
        $settings = array(
            'live_site_url' => sanitize_url($_POST['live_site_url']),
            'schedule_enabled' => isset($_POST['schedule_enabled']) ? true : false,
            'schedule_interval' => sanitize_text_field($_POST['schedule_interval']),
        );
        update_option('wdm_sync_settings', $settings);
        
        // Handle cron scheduling
        if ($settings['schedule_enabled']) {
            wdm_sync_schedule_cron($settings['schedule_interval']);
        } else {
            wdm_sync_clear_cron();
        }
        
        echo '<div class="notice notice-success"><p>Settings saved successfully!</p></div>';
    }
    
    // Get current settings
    $settings = get_option('wdm_sync_settings', array());
    $live_site_url = isset($settings['live_site_url']) ? $settings['live_site_url'] : '';
    $schedule_enabled = isset($settings['schedule_enabled']) ? $settings['schedule_enabled'] : false;
    $schedule_interval = isset($settings['schedule_interval']) ? $settings['schedule_interval'] : 'hourly';
    
    // Get last sync result
    $last_sync = wdm_sync_get_last_result();
    ?>
    <div class="wrap">
        <h1>WDM Sync</h1>
        
        <form method="post" action="">
            <table class="form-table">
                <tr>
                    <th scope="row">
                        <label for="live_site_url">Live Site URL</label>
                    </th>
                    <td>
                        <input type="url" 
                               id="live_site_url" 
                               name="live_site_url" 
                               value="<?php echo esc_attr($live_site_url); ?>" 
                               class="regular-text"
                               placeholder="https://example.com" />
                        <p class="description">Enter the URL of your live/production site for synchronization.</p>
                    </td>
                </tr>
                
                <tr>
                    <th scope="row">
                        <label for="schedule_enabled">Enable Scheduled Sync</label>
                    </th>
                    <td>
                        <input type="checkbox" 
                               id="schedule_enabled" 
                               name="schedule_enabled" 
                               value="1" 
                               <?php checked($schedule_enabled, true); ?> />
                        <label for="schedule_enabled">Enable automatic scheduled synchronization</label>
                    </td>
                </tr>
                
                <tr>
                    <th scope="row">
                        <label for="schedule_interval">Sync Interval</label>
                    </th>
                    <td>
                        <select id="schedule_interval" name="schedule_interval">
                            <option value="hourly" <?php selected($schedule_interval, 'hourly'); ?>>Hourly</option>
                            <option value="twicedaily" <?php selected($schedule_interval, 'twicedaily'); ?>>Twice Daily</option>
                            <option value="daily" <?php selected($schedule_interval, 'daily'); ?>>Daily</option>
                            <option value="weekly" <?php selected($schedule_interval, 'weekly'); ?>>Weekly</option>
                        </select>
                        <p class="description">How often should the sync run automatically?</p>
                    </td>
                </tr>
            </table>
            
            <?php submit_button('Save Settings'); ?>
        </form>
        
        <hr>
        
        <h2>Sync Status</h2>
        <?php if (!empty($last_sync)): ?>
            <div class="notice notice-info">
                <h3>Last Sync Result:</h3>
                <p><strong>Status:</strong> <?php echo esc_html($last_sync['status']); ?></p>
                <p><strong>Message:</strong> <?php echo esc_html($last_sync['message']); ?></p>
                <p><strong>Timestamp:</strong> <?php echo esc_html($last_sync['timestamp']); ?></p>
                <p><strong>Live Site URL:</strong> <?php echo esc_html($last_sync['live_site_url']); ?></p>
            </div>
        <?php else: ?>
            <div class="notice notice-warning">
                <p>No sync has been performed yet. Use the API endpoint to start a sync.</p>
            </div>
        <?php endif; ?>
        
        <h3>API Endpoint</h3>
        <p>To trigger a sync, send a POST request to:</p>
        <code><?php echo esc_url(rest_url('wdm-sync/v1/start')); ?></code>
    </div>
    <?php
}
