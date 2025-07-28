<?php
// Admin page content for WDM Sync
function wdm_sync_admin_page() {
    if (!current_user_can('manage_options')) {
        return;
    }
    ?>
    <div class="wrap">
        <h1>WDM Sync</h1>
    </div>
    <?php
}
