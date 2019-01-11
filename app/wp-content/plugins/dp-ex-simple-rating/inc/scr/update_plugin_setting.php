<?php
// Save params
if (isset($_POST['dp_ex_simrat_options_save'])) {
	// Default
	$def_options = $this->def_options;
	// Current
	$options = $this->get_plugin_setting();

	// Expire minutes
	if(isset($_POST['expiretime'])) {
		$options['expiretime'] = mb_convert_kana($_POST['expiretime'],"n");
		if (!ctype_digit($options['expiretime'])) {
			$options['expiretime'] = 365;
		}
	}
	// Show bad button
	if (isset($_POST['show_bad'])) {
		$options['show_bad'] = (bool)true;
	} else {
		$options['show_bad'] = (bool)false;
	}
	// Like icon
	$options['like_icon'] = $_POST['like_icon'];
	// Bad icon
	$options['show_bad'] = $_POST['show_bad'];

	// Target
	if (isset($_POST['available_post'])) {
		$options['available_post'] = (bool)true;
	} else {
		$options['available_post'] = (bool)false;
	}
	if (isset($_POST['available_page'])) {
		$options['available_page'] = (bool)true;
	} else {
		$options['available_page'] = (bool)false;
	}

	// Display polls in archives
	if (isset($_POST['show_polls_top'])) {
		$options['show_polls_top'] = (bool)true;
	} else {
		$options['show_polls_top'] = (bool)false;
	}
	if (isset($_POST['show_polls_archive'])) {
		$options['show_polls_archive'] = (bool)true;
	} else {
		$options['show_polls_archive'] = (bool)false;
	}

	// Position
	$options['position'] = $_POST['position'];

	// alignment
	$options['alignment'] = $_POST['alignment'];

	// Caption
	$options['rate_caption'] = htmlspecialchars(stripslashes($_POST['rate_caption']));

	// Chart
	if (isset($_POST['show_chart'])) {
		$options['show_chart'] = (bool)true;
	} else {
		$options['show_chart'] = (bool)false;
	}
	// Chart color
	$options['liked_chart_color'] = empty($_POST['liked_chart_color']) ? $def_options['liked_chart_color'] : $_POST['liked_chart_color'];
	$options['bad_chart_color'] = empty($_POST['bad_chart_color']) ? $def_options['bad_chart_color'] : $_POST['bad_chart_color'];
	// Chart link code
	$options['chart_btn_code'] = htmlspecialchars(stripslashes($_POST['chart_btn_code']));
	

	// Update
	update_option($this->option_key, $options);
	// Message
	$notice_msg = __('Successfully updated.',DP_EX_SIMPLE_RATING_TEXT_DOMAIN);
	set_transient( 'dp-ex-simrat-notices', array($notice_msg), 5 );
	add_action( 'admin_notices', array($this, 'show_admin_notice_message') );

} else {
	$options = $this->get_plugin_setting();
}
