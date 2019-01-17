<?php
/************************************
* the code below is just a standard
* options page. Substitute with 
* your own.
*************************************/
set_site_transient( 'update_plugins', null );
add_action('dp_admin_add_ons_panel', 'dp_ex_popular_posts_license_page');

function dp_ex_popular_posts_license_page() {
	$license 	= get_option( DP_EX_POPULAR_POSTS_LICENSE_KEY_PHRASE );
	$status 	= get_option( DP_EX_POPULAR_POSTS_LICENSE_KEY_PHRASE.'_status' );
	$payment_id = get_option( DP_EX_POPULAR_POSTS_LICENSE_KEY_PHRASE.'_payment_id' );
	$customer_name = get_option( DP_EX_POPULAR_POSTS_LICENSE_KEY_PHRASE.'_customer_name' );
	?>
	<div class="dp_ex_license_div wrap">
		<h3 class="dp_ex_license_title icon-triangle-right"><?php echo DP_EX_POPULAR_POSTS_NAME.' '.__('License Activation', DP_EX_POPULAR_POSTS_TEXT_DOMAIN); ?></h3>
		<form method="post" action="options.php">
		
			<?php settings_fields('dp_ex_popular_posts_license'); ?>
			
			<table class="form-table">
				<tbody>
					<tr valign="top">	
						<th scope="row" valign="top">
							<?php _e('License Key', DP_EX_POPULAR_POSTS_TEXT_DOMAIN); ?>
						</th>
						<td>
							<input id="<?php echo DP_EX_POPULAR_POSTS_LICENSE_KEY_PHRASE; ?>" name="<?php echo DP_EX_POPULAR_POSTS_LICENSE_KEY_PHRASE; ?>" type="text" class="regular-text" value="<?php echo esc_attr( $license ); ?>" />
							<label class="description" for="<?php echo DP_EX_POPULAR_POSTS_LICENSE_KEY_PHRASE; ?>"><?php _e('Enter your license key', DP_EX_POPULAR_POSTS_TEXT_DOMAIN); ?></label>
							<div style="font-size:12px;"><?php _e('* If you cange the license key, please save the key before activate.', DP_EX_POPULAR_POSTS_TEXT_DOMAIN); ?></div>
						</td>
					</tr>
					<?php if( false !== $license ) { ?>
						<tr valign="top">	
							<th scope="row" valign="top">
								<?php _e('Activate License', DP_EX_POPULAR_POSTS_TEXT_DOMAIN); ?>
							</th>
							<td>
								<?php 
								if( $status !== false && $status == 'valid' ) { ?>
									<input type="submit" class="button-secondary" style="vertical-align: middle;" name="dp_ex_popular_posts_license_deactivate" value="<?php _e('Deactivate This License', DP_EX_POPULAR_POSTS_TEXT_DOMAIN); ?>" />
									<?php wp_nonce_field( 'dp_ex_popular_posts_nonce', 'dp_ex_popular_posts_nonce' ); ?>
									<span style="color:green;padding-right:8px;"><?php _e('active', DP_EX_POPULAR_POSTS_TEXT_DOMAIN); ?></span>
								<?php 
								} else {
									wp_nonce_field( 'dp_ex_popular_posts_nonce', 'dp_ex_popular_posts_nonce' ); ?>
									<input type="submit" class="button-secondary" style="vertical-align: middle;" name="dp_ex_popular_posts_license_activate" value="<?php _e('Activate This License', DP_EX_POPULAR_POSTS_TEXT_DOMAIN); ?>" />
									<?php
									if( $status ) {
									?>
										<span style="color:red;padding-right:8px;"><?php _e($status, DP_EX_POPULAR_POSTS_TEXT_DOMAIN); ?></span>
									<?php 
									}
									else {
									?>
										<span style="color:red;padding-right:8px;"><?php _e('inactive', DP_EX_POPULAR_POSTS_TEXT_DOMAIN); ?></span>
									<?php
									}	
								} ?>
							</td>
						</tr>
						<?php if( $status !== false && $status == 'valid' ) { ?>
						<tr valign="top">
							<th scope="row" valign="top">
								<?php _e('Licensed Info', DP_EX_POPULAR_POSTS_TEXT_DOMAIN); ?>
							</th>
							<td>
								<p><?php echo __('Payment ID : ', DP_EX_POPULAR_POSTS_TEXT_DOMAIN).'#'.$payment_id; ?></p>
								<p><?php echo __('Licensed User : ', DP_EX_POPULAR_POSTS_TEXT_DOMAIN).$customer_name; ?></p>
							</td>
						</tr>
						<?php } ?>
					<?php } ?>
				</tbody>
			</table>	
			<?php submit_button(); ?>
		</form>
		<?php do_action('dp_ex_popular_posts_license_page_bottom', $status); ?>
	</div>
	<hr />
<?php
}

function dp_ex_popular_posts_register_option() {
	// creates our settings in the options table
	register_setting('dp_ex_popular_posts_license', DP_EX_POPULAR_POSTS_LICENSE_KEY_PHRASE, 'dp_ex_popular_posts_sanitize_license' );
}
add_action('admin_init', 'dp_ex_popular_posts_register_option');

function dp_ex_popular_posts_sanitize_license( $new ) {
	$old = get_option( DP_EX_POPULAR_POSTS_LICENSE_KEY_PHRASE );
	if( $old && $old != $new ) {
		delete_option( DP_EX_POPULAR_POSTS_LICENSE_KEY_PHRASE.'_status' ); // new license has been entered, so must reactivate
	}
	return $new;
}



/************************************
* this illustrates how to activate 
* a license key
*************************************/
function dp_ex_popular_posts_activate_license() {

	// listen for our activate button to be clicked
	if( isset( $_POST['dp_ex_popular_posts_license_activate'] ) ) {

		// run a quick security check 
	 	if( ! check_admin_referer( 'dp_ex_popular_posts_nonce', 'dp_ex_popular_posts_nonce' ) ) 	
			return; // get out if we didn't click the Activate button

		// retrieve the license from the database
		$license = trim( get_option( DP_EX_POPULAR_POSTS_LICENSE_KEY_PHRASE ) );
			

		// data to send in our API request
		$api_params = array( 
			'edd_action'=> 'activate_license', 
			'license' 	=> $license, 
			'item_name' => urlencode( DP_EX_POPULAR_POSTS_NAME ), // the name of our product in EDD
			'url'       => home_url()
		);
		
		// Call the custom API.
		$response = wp_remote_post(DP_EX_POPULAR_POSTS_STORE_URL, array( 'timeout' => 15, 'sslverify' => false, 'body' => $api_params ) );

		// make sure the response came back okay
		if ( is_wp_error( $response ) )
			return false;

		// decode the license data
		$license_data = json_decode( wp_remote_retrieve_body( $response ) );
		
		// $license_data->license will be either "valid" or "invalid"
		update_option( DP_EX_POPULAR_POSTS_LICENSE_KEY_PHRASE.'_status', $license_data->license );
		update_option( DP_EX_POPULAR_POSTS_LICENSE_KEY_PHRASE.'_payment_id', $license_data->payment_id );
		update_option( DP_EX_POPULAR_POSTS_LICENSE_KEY_PHRASE.'_customer_name', $license_data->customer_name );
	}
}
add_action('admin_init', 'dp_ex_popular_posts_activate_license');


/***********************************************
* Illustrates how to deactivate a license key.
* This will descrease the site count
***********************************************/

function dp_ex_popular_posts_deactivate_license() {

	// listen for our activate button to be clicked
	if( isset( $_POST['dp_ex_popular_posts_license_deactivate'] ) ) {

		// run a quick security check 
	 	if( ! check_admin_referer( 'dp_ex_popular_posts_nonce', 'dp_ex_popular_posts_nonce' ) ) 	
			return; // get out if we didn't click the Activate button

		// retrieve the license from the database
		$license = trim( get_option( DP_EX_POPULAR_POSTS_LICENSE_KEY_PHRASE ) );
			

		// data to send in our API request
		$api_params = array( 
			'edd_action'=> 'deactivate_license', 
			'license' 	=> $license, 
			'item_name' => urlencode( DP_EX_POPULAR_POSTS_NAME ), // the name of our product in EDD
			'url'       => home_url()
		);
		
		// Call the custom API.
		$response = wp_remote_post(DP_EX_POPULAR_POSTS_STORE_URL, array( 'timeout' => 15, 'sslverify' => false, 'body' => $api_params ) );

		// make sure the response came back okay
		if ( is_wp_error( $response ) )
			return false;

		// decode the license data
		$license_data = json_decode( wp_remote_retrieve_body( $response ) );
		
		// $license_data->license will be either "deactivated" or "failed"
		if( $license_data->license == 'deactivated' ) {
			delete_option( DP_EX_POPULAR_POSTS_LICENSE_KEY_PHRASE.'_status' );
			delete_option( DP_EX_POPULAR_POSTS_LICENSE_KEY_PHRASE.'_payment_id', $license_data->payment_id );
			delete_option( DP_EX_POPULAR_POSTS_LICENSE_KEY_PHRASE.'_customer_name', $license_data->customer_name );
		}

		// Do something
		do_action('dp_ex_popular_posts_deactivate_license');

		// delete transient
		delete_site_transient(DP_EX_POPULAR_POSTS_LICENSE_KEY_PHRASE);
	}
}
add_action('admin_init', 'dp_ex_popular_posts_deactivate_license');


/************************************
* this illustrates how to check if 
* a license key is still valid
* the updater does this for you,
* so this is only needed if you
* want to do something custom
*************************************/

function dp_ex_popular_posts_check_license() {
	global $wp_version;
	$license = trim( get_option( DP_EX_POPULAR_POSTS_LICENSE_KEY_PHRASE ) );
	$api_params = array( 
		'edd_action' => 'check_license', 
		'license' => $license, 
		'item_name' => urlencode( DP_EX_POPULAR_POSTS_NAME ),
		'url'       => home_url()
	);

	// Call the custom API.
	$response = wp_remote_post(DP_EX_POPULAR_POSTS_STORE_URL, array( 'timeout' => 15, 'sslverify' => false, 'body' => $api_params ) );

	if ( is_wp_error( $response ) )
		return false;

	$license_data = json_decode( wp_remote_retrieve_body( $response ) );

	if( $license_data->license == 'valid' ) {
		echo 'valid'; exit;
		// this license is still valid
	} else {
		echo 'invalid'; exit;
		// this license is no longer valid
	}
}


/***********************************************
* no activate user
***********************************************/
function dp_ex_popular_posts_status() {
	$transient = get_site_transient(DP_EX_POPULAR_POSTS_LICENSE_KEY_PHRASE);
	$status = get_option( DP_EX_POPULAR_POSTS_LICENSE_KEY_PHRASE.'_status' );
	$status = ((bool)$transient && $status === 'valid') ? true : false;
	return $status;
}
// add_action('plugins_loaded', 'dp_ex_popular_posts_status');


/***********************************************
* Admin message
***********************************************/
function dp_ex_popular_posts_noactivate_message() {
	$status 	= get_option( DP_EX_POPULAR_POSTS_LICENSE_KEY_PHRASE.'_status' );
	if ( $status != 'valid' ) {
		echo '<div id="message" class="error"><p>'.DP_EX_POPULAR_POSTS_NAME .' '.__('Your plugin license is invalid or inactive. Please', DP_EX_POPULAR_POSTS_TEXT_DOMAIN).' <input type="button" class="button" onclick="document.location.href=\'admin.php?page=digipress_add_ons\';" value="'.__('Activate your license.',DP_EX_POPULAR_POSTS_TEXT_DOMAIN).'" /></p></div>';
	}
}
add_action('admin_notices', 'dp_ex_popular_posts_noactivate_message');
?>