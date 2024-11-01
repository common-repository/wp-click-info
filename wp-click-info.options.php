<?php
if (isset($_POST["cls"])){
	global $wpdb;
	$wpdb->query("DELETE FROM ".$wpdb->saqClicks);
?>
<div class="update-nag"><br>
    <strong>Statistics successfully deleted.</strong>
</div>
<?php
}
?>
<?php
	include('wp-click-info.thank-you.php');
?>
<div class="wrap">
<h2>WP Click Info Options</h2>
<form method="post" action="options.php">
    <?php settings_fields( 'wpclickinfoOptions' ); ?>
    <table class="form-table">
        <tr valign="top" rowspan="2">
            <th scope="row" ><strong></strong></th>
        </tr>
        <tr valign="top">
            <td>
                    <span>Hide external link icons</span>
                    <input type="checkbox" name="hideExternalLinkIco" value="checkbox" <?php if (get_option('hideExternalLinkIco',false)) echo "checked='checked'"; ?> />
            </td>
            <td>
                    <span>Do not open a new window</span>
                    <input type="checkbox" name="doNotOpenNewWindow" value="checkbox" <?php if (get_option('doNotOpenNewWindow',false)) echo "checked='checked'"; ?> />
                    <i>To avoid waiting times for your visitors it is recommended that this option is disabled (default).</i>
            </td>
            <td>
                    <span>Hide statistcs in external link titles</span>
                    <input type="checkbox" name="hideStats" value="checkbox" <?php if (get_option('hideStats',false)) echo "checked='checked'"; ?> />
            </td>
        </tr>
        <tr valign="top">
            <td>
                <span>Record Admin User Clicks</span>
                <input type="checkbox" name="recordAdminUser" value="checkbox" <?php if (get_option('recordAdminUser',true)) echo "checked='checked'"; ?> />
            </td>
            <td>
                <span>Record Registered User Clicks</span>
                <input type="checkbox" name="recordRegisteredUser" value="checkbox" <?php if (get_option('recordRegisteredUser',true)) echo "checked='checked'"; ?> />
            </td>
            <td>
            </td>
        </tr>
    </table>

    <p class="submit">
		<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
    </p>
</form>
<form method="post" action="">
	<span>Clear Statistics:</span>

    <p class="submit">
		<input type="submit" class="button-primary" value="Reset Database Records" name="cls" id="cls"/>
    </p>
</form>
</div>