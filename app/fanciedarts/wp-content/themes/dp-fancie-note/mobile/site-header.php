<?php
// **********************************
// Get WP theme customizer objects(no use)
// **********************************
$custom_header 	= get_custom_header();
$custom_header_image = get_header_image();

// wow.js
$wow_title_css = '';
$attr_delay = '';
if (!(bool)$options['disable_wow_js_mb']) {
	$wow_title_css = ' wow fadeInDown';
	$attr_delay = ' data-wow-delay="0.3s"';
}

// **********************************
// Header main title
// **********************************
$site_title = '';
if (!empty($custom_header_image)) {
	$site_title = '<h1 class="hd_title img'.$wow_title_css.'"><a href="'.home_url().'/" title="'.get_bloginfo('name').'"><img src="'.$custom_header_image.'" height="'.$custom_header->height.'" width="'.$custom_header->width.'" alt="'.dp_h1_title().'" /></a></h1>';
} else {
	if ($options_visual['h1title_as_what'] !== 'image') {
		$site_title = '<h1 class="hd_title txt'.$wow_title_css.'"><a href="'.home_url().'/" title="'.get_bloginfo('name').'">'.dp_h1_title().'</a></h1>';
	} else {
		$logo_img_url = is_ssl() ? str_replace('http:', 'https:', $options_visual['dp_title_img_mobile']) : $options_visual['dp_title_img_mobile'];
		$site_title = '<h1 class="hd_title img'.$wow_title_css.'"><a href="'.home_url().'/" title="'.get_bloginfo('name').'"><img src="'.$logo_img_url.'" alt="'.dp_h1_title().'" /></a></h1>';
	}
}
// **********************************
// Display "$float_css" from header.php
// **********************************
echo '<header class="header_container mb'.$float_css.'">'.$site_title.'</header>';