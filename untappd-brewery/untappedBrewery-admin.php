<?php
	if($_POST[utb_hidden'] == 'Y') {
		//Form data sent
		$utbclientid = $_POST['utb_clientid'];
		update_option('utb_clientid', $utbclientid);

		$utbsecret = $_POST['utb_secret'];
		update_option('utb_secret', $utbsecret);

		$utbredirecturi = $_POST['utb_redirecturi'];
		update_option('utb_redirecturi', $utbredirecturi);

		$utbbreweryid = $_POST['utb_breweryid'];
		update_option('utb_breweryid', $utbbreweryid);

		$utbvenueid = $_POST['utb_venueid'];
		update_option('utb_venueid', $utbvenueid);

?>

		<div class="updated"><p><strong><?php _e('Options saved.' ); ?></strong></p></div>

<?php

	} else {
		//Normal page display
		$utbclientid = get_option('utb_clientid');
		$utbsecret = get_option('utb_secret');
		$utbredirecturi = get_option('utb_redirecturi');
		$utbbreweryid = get_option('utb_breweryid');
		$utbvenueid = get_option('utb_venueid');
	}

?>

<div class="wrap tappd">
    <?php    echo "<div id='icon-tools' class='icon32'></div><h2>" . __( 'Untappd Brewery Plugin Settings', 'utb_utbdom' ) . "</h2>"; ?>
<p>To get an Untappd API Key, you must have an Untappd account registered and visit <a href="https://untappd.com/api/register">https://untappd.com/api/register</a>.</p>
    <form name="utbuntpd_form" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
        <input type="hidden" name="druntpd_hidden" value="Y">
        <table class="form-table">
            <tr valign="top"><th scope="row">
                <?php    echo "<h3 class='title'>" . __( 'Untappd API Settings', 'utb_utbdom' ) . "</h3>"; ?>
            </th></tr>
            <tr valign="top"><th scope="row">
                <label for="utb_clientid"><?php _e("Client ID: " ); ?></label>
            </th><td>
                <input type="text" name="utb_rclientid" id="utb_rclientid" value="<?php echo $utbclientid; ?>" size="50">
            </td></tr>
            <tr valign="top"><th scope="row">
                <label for="utb_secret"><?php _e("Client Secret: " ); ?></label>
            </th><td>
                <input type="text" name="utb_secret" id="utb_secret" value="<?php echo $utbsecret; ?>" size="50">
            </td></tr>
            <tr valign="top"><th scope="row">
                <?php    echo "<h3 class='title'>" . __( 'Tappd Feed Settings', 'utb_utbdom' ) . "</h3>"; ?>
            </th></tr>
            <tr valign="top"><th scope="row">
                <label for="utb_breweryid"><?php _e("Brewery ID: " ); ?></label>
            </th><td>
                <input type="text" name="utb_breweryid" id="utb_breweryid" value="<?php echo $utbbreweryid; ?>" size="50">
                <p class="description">(ex https://untappd.com/brewery/<strong style="color: #DB5A18;">94</strong>)</p>
            </td></tr>
        </table>
        <p class="submit"><input class="button-primary" type="submit" name="Submit" value="<?php _e('Update Options', 'utb_utbdom' ) ?>" /></p>
    </form>
</div>
</div>
