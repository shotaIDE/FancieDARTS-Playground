<?php
// **********************************
// Get header title image or text
// **********************************
$site_title_code 	= '';

if ($options_visual['h1title_as_what'] !== 'image') {
	$site_title_code = '<h1 class="hd_title txt"><a href="'.home_url().'/" title="'.get_bloginfo('name').'">'.dp_h1_title().'</a></h1>';
} else {
	$logo_img_url = is_ssl() ? str_replace('http:', 'https:', $options_visual['dp_title_img_mobile']) : $options_visual['dp_title_img_mobile'];
	$logo_img_size = ' width="200" height="30"';
	$logo_img_data = dp_get_image_size($logo_img_url);
	if ($logo_img_data && is_array($logo_img_data)) {
		$logo_img_size = ' width="'.$logo_img_data[0].'" height="'.$logo_img_data[1].'"';
	}
	$site_title_code = '<h1 class="hd_title img"><a href="'.home_url().'/" title="'.get_bloginfo('name').'" class="img_link"><amp-img src="'.$logo_img_url.'" layout="responsive" alt="'.dp_h1_title().'"'.$logo_img_size.' class="title_img"></amp-img></a></h1>';
}?>
<header class="site-bar header-bar"><?php echo $site_title_code; ?></header><?php
// **********************************
// Header image, slider on top page
// **********************************
// Top page
if (is_front_page() && !is_paged() && have_posts() && !isset( $_REQUEST['q']) ) :
	// Get header banner image
	if ($options_visual['dp_header_content_type_mobile'] == "none")  return;

	// Header banner outer tag
	$banner_sec_tag = '';
	/* ******************
	 * if title shows on header
	 * *****************/
	// CSS class
	$title_position_class = 'pos-c';
	// switch ($options['header_banner_title_position']) {
	// 	case 'left':
	// 		$title_position_class = 'pos-l';
	// 		break;
	// 	case 'right':
	// 		$title_position_class = 'pos-r';
	// 		break;
	// }
	// Title
	$header_title_code = '';
	if ( $options_visual['dp_slideshow_target_mobile'] === 'header_img') {
		if (!empty($options['header_img_h2'])) {
			$header_title_code = '<h2 class="hd-bn-h2">'.htmlspecialchars_decode($options['header_img_h2']).'</h2>';
		}
		// H3 title
		if (!empty($options['header_img_h3'])) {
			$header_title_code .= '<div class="hd-bn-h3">'.htmlspecialchars_decode($options['header_img_h3']).'</div>';
		}
		if (!empty($header_title_code)) {
			$header_title_code = '<div class="banner-title '.$title_position_class.'">'.$header_title_code.'</div>';
		}
	}
	// ****************
	// Get header slider or images
	// ****************
	$banner_contents_code = dp_banner_contents_amp();
	/* **********************
	 * Show header 
	 * *********************/
	$banner_sec_tag = '<section class="header-banner">'.$banner_contents_code.$header_title_code.'</section>';
	echo $banner_sec_tag;
endif; // end of (is_front_page() && !is_paged())