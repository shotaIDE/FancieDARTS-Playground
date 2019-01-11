<?php
// Includes the files needed for the theme updater
if ( !class_exists( 'DP_Theme_Updater_Admin' ) ) {
	include( dirname( __FILE__ ) . '/theme-updater-admin.php' );
}

// Loads the updater classes
$dp_updater = new DP_Theme_Updater_Admin(

	// Config settings
	$config = array(
		'remote_api_url'=> 'https://digipress.info/',
		'item_name'		=> DP_THEME_NAME,
		'theme_slug'	=> DP_THEME_SLUG,
		'version' 		=> DP_OPTION_SPT_VERSION, 
		'author'		=> 'digistate co.,ltd.',
		'download_id'	=> '', // Optional, used for generating a license renewal link
		'renew_url'		=> '', // Optional, allows for a custom license renewal link
		'beta'			=> false, // Optional, set to true to opt into beta versions
		'copyright_elem'=> '#footer .copyright .inner'
	),
	// Strings
	$strings = array(
		'theme-license'             => __( 'Theme License', 'DigiPress' ),
		'license-page-title'		=> __( 'Theme License Options', 'DigiPress'),
		'enter-key'                 => __( 'Enter your license key.', 'DigiPress' ),
		'license-key'               => __( 'License Key', 'DigiPress' ),
		'license-action'            => __( 'License Action', 'DigiPress' ),
		'deactivate-license'        => __( 'Deactivate This License', 'DigiPress' ),
		'activate-license'          => __( 'Activate This License', 'DigiPress' ),
		'status-unknown'            => __( 'License status is unknown.', 'DigiPress' ),
		'renew'                     => __( 'Renew?', 'DigiPress' ),
		'unlimited'                 => __( 'unlimited', 'DigiPress' ),
		'license-key-is-active'     => '<p style="color:green;">'.__( 'License key is active.', 'DigiPress' ).'</p>',
		'expires%s'                 => __( 'Expiration date : %s', 'DigiPress' ),
		'expires-never'             => __( 'Expiration date : Lifetime License', 'DigiPress' ),
		'%1$s/%2$-sites'            => __( 'You have %1$s / %2$s sites activated.', 'DigiPress' ),
		'license-key-expired-%s'    => __( 'License key expired %s.', 'DigiPress' ),
		'license-key-expired'       => '<p style="color:red;">'.__( 'License key has expired.', 'DigiPress' ).'</p>',
		'license-keys-do-not-match' => '<p style="color:red;">'.__( 'License keys do not match.', 'DigiPress' ).'</p>',
		'license-is-inactive'       => '<p style="color:red;">'.__( 'License is inactive.', 'DigiPress' ).'</p>',
		'license-key-is-disabled'   => '<p style="color:red;">'.__( 'License key is disabled.', 'DigiPress' ).'</p>',
		'site-is-inactive'          => __( 'Site is inactive.', 'DigiPress' ),
		'license-status-unknown'    => __( 'License status is unknown.', 'DigiPress' ),
		'update-notice'             => __( "Updating this theme will lose any customizations you have made. 'Cancel' to stop, 'OK' to update.", 'DigiPress' ),
		'update-available'          => __('<strong>%1$s %2$s</strong> is available. <a href="%3$s" class="thickbox" title="%4s">Check out what\'s new</a> or <a href="%5$s"%6$s>update now</a>.', 'DigiPress' ),
		'deactivate-message'		=> __( 'All theme options will be cleared. Please backup the options, if you do this. Are you sure?', 'DigiPress' ),
		'theme-caption'				=> 'Highly Flexible WordPress Theme'
	)
);
