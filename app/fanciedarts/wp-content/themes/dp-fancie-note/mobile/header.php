<?php
global $options, $options_visual;?><!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js"><?php
if ( is_singular() ) : ?>
<head prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# article: http://ogp.me/ns/article#"><?php
else: ?>
<head prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# blog: http://ogp.me/ns/website#"><?php
endif; ?>
<meta charset="UTF-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,user-scalable=yes" /><?php
if ( (is_front_page() || is_archive()) && is_paged()) : ?>
<meta name="robots" content="noindex,follow" /><?php
elseif ( is_singular() ) :
	if (get_post_meta(get_the_ID(), 'dp_noindex', true) && 
		get_post_meta(get_the_ID(), 'dp_nofollow', true) && 
		get_post_meta(get_the_ID(), 'dp_noarchive', true)) :?>
<meta name="robots" content="noindex,nofollow,noarchive" /><?php
	elseif (get_post_meta(get_the_ID(), 'dp_noindex', true) && 
		get_post_meta(get_the_ID(), 'dp_nofollow', true) && 
		!get_post_meta(get_the_ID(), 'dp_noarchive', true)) : ?>
<meta name="robots" content="noindex,nofollow" /><?php
	elseif (get_post_meta(get_the_ID(), 'dp_noindex', true) && 
		!get_post_meta(get_the_ID(), 'dp_nofollow', true) && 
		!get_post_meta(get_the_ID(), 'dp_noarchive', true)) :?>
<meta name="robots" content="noindex" /><?php
	elseif (!get_post_meta(get_the_ID(), 'dp_noindex', true) && 
		get_post_meta(get_the_ID(), 'dp_nofollow', true) && 
		get_post_meta(get_the_ID(), 'dp_noarchive', true)) :?>
<meta name="robots" content="nofollow,noarchive" /><?php
	elseif (!get_post_meta(get_the_ID(), 'dp_noindex', true) && 
		!get_post_meta(get_the_ID(), 'dp_nofollow', true) && 
		get_post_meta(get_the_ID(), 'dp_noarchive', true)) :?>
<meta name="robots" content="noarchive" /><?php
	elseif (!get_post_meta(get_the_ID(), 'dp_noindex', true) && 
		get_post_meta(get_the_ID(), 'dp_nofollow', true) && 
		!get_post_meta(get_the_ID(), 'dp_noarchive', true)) :?>
<meta name="robots" content="nofollow" /><?php
	elseif (get_post_meta(get_the_ID(), 'dp_noindex', true) && 
		!get_post_meta(get_the_ID(), 'dp_nofollow', true) && 
		get_post_meta(get_the_ID(), 'dp_noarchive', true)) :?>
<meta name="robots" content="noindex,noarchive" /><?php
	endif;
endif;

// show keyword and description
dp_meta_kw_desc();
// show OGP
dp_show_ogp();
// Canonical
dp_show_canonical();?>
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" /><?php
// **********************************
// WordPress header
// **********************************
wp_head();
// **********************************
// Custom header
// **********************************
echo $options['custom_head_content'];?>
<script>j$=jQuery;</script>
</head><?php
// **********************************
// Main Body
// **********************************
// @ params
// class for body tag 
$body_class = 'mb-theme';
$has_header_class = '';
$cur_page_class = "";
// Front page
if (is_front_page() && !is_paged() && !isset( $_REQUEST['q']) ) :
	if ( $options_visual['dp_header_content_type_mobile'] === "none") :
		$has_header_class = ' no-header';
	else :
		$has_header_class = ' show-header';
	endif;
	$cur_page_class = ' home';
elseif (is_singular()) :
	$cur_page_class = ' not-home singular';
else :
	$cur_page_class = ' not-home';
endif;
// share count JS
if ( isset($options['disable_sns_share_count']) && !empty($options['disable_sns_share_count'])) {
	$body_class .= ' no-sns-count';
}?>
<body <?php body_class($body_class); ?>>
<div id="main-wrap" class="main-wrap<?php echo $has_header_class; ?>"><?php
// Header bar floating flag
$float_css = '';
if ($options['fixed_header_bar_mb']){
	$float_css = ' float';
}
// **********************************
// Site header
// **********************************
include_once(TEMPLATEPATH."/".DP_MOBILE_THEME_DIR."/site-header.php");
// **********************************
// Full screen site banner
// **********************************
dp_banner_contents();
// **********************************
// Site container
// **********************************?>
<div id="container" class="dp-container content clearfix<?php echo $cur_page_class.$has_header_class.$float_css; ?>"><?php
// **********************************
// Show eyecatch on container 
// **********************************
$arr_http = array('http:','https:');
$plx_bg_code = '';
$plx_bg_img_class = ' no_bgimg';
$image_css = '';
$image_url_f = '';
if (is_single() || is_page()) {
	// get post type
	$post_type 			= get_post_type();
	// Show eyecatch on top 
	$show_eyecatch_force 	= get_post_meta(get_the_ID(), 'dp_show_eyecatch_force', true);
	$eyecatch_on_container 	= get_post_meta(get_the_ID(), 'dp_eyecatch_on_container', true);
	// Show eyecatch upper the title
	if( has_post_thumbnail() && $show_eyecatch_force && $eyecatch_on_container ) {
		$width_f = 1680;
		$height_f = 1200;

		$image_id_f		= get_post_thumbnail_id();
		$image_data_f	= wp_get_attachment_image_src($image_id_f, array($width_f, $height_f), true);
		$image_url_f 	= str_replace($arr_http,'',$image_data_f[0]);
		$image_css 		= ' style="background-image:url('.$image_url_f.')"';
		$plx_bg_code	= '<div class="plx_bg"'.$image_css.'></div>';
		$plx_bg_img_class = ' bgimg';
	}
}
// ************************
// Main title (Except top page)
// ************************
$hd_title_show 	= true;
$hd_title_code 	= '';
$page_desc = '';
$meta_code = '';
$hd_title_data	= '';
$hd_desc_data	= '';
$hd_title_plx_class	= '';
$hd_desc_plx_class = '';
$cat_color 	= '';
$page_class	= '';
// effect
if (!(bool)$options['parallax_disable_mobile']) {
	$hd_title_data = ' data-wow-delay="0.6s"';
	$hd_desc_data = ' data-wow-delay="1.1s"';
	$hd_title_plx_class = ' wow fadeInLeft';
	$hd_desc_plx_class = ' wow fadeInUp';
}
// Show title and description
if (!is_home()) {
	// Category
	if (is_category()) {
		global $cat;
		$cat_color = " cat-color".$cat;
	}
	// Single pge
	if (is_singular()) {
		$hd_title_plx_class = ' wow fadeInDown';
		$meta_info_data = ' data-wow-delay="1.4s"';
		$page_class = ' singular';
		// Eye catch flag
		$eyecatch_flag = get_post_meta(get_the_ID(),'dp_eyecatch_on_container',true );
		// Hide title flag
		$hide_title_flag = get_post_meta(get_the_ID(), 'dp_hide_title', true);
		// Title
		if ((bool)$hide_title_flag) :
			$hd_title_code = '';
			if (!(bool)$eyecatch_flag) :
				$hd_title_show = false;
			endif;
		else :
			// Title
			$hd_title_code = the_title_attribute('before=&after=&echo=0');
			$hd_title_code = $hd_title_code ? $hd_title_code : __('No Title', 'DigiPress');
			$hd_title_code = '<h1 class="hd-title single-title'.$hd_title_plx_class.'"'.$hd_title_data.'><span>'.$hd_title_code.'</span></h1>';
		endif;
		// Get meta info
		$arr_meta = get_post_meta_for_single_page();
		// Meta under the title
		$show_date_under_post_title = $options['show_date_under_post_title'];
		$show_views_under_post_title = $options['show_views_under_post_title'];
		$show_author_under_post_title = $options['show_author_under_post_title'];
		$show_cat_under_post_title 	= $options['show_cat_under_post_title'];

		// Single post
		if (is_single()) {
			// Category color
			$cat_color = $arr_meta['cat_color_class'];
			// Hide category
			if ((bool)$show_cat_under_post_title && !empty($arr_meta['cat_one'])) {
				$page_desc = '<div class="title-desc'.$hd_desc_plx_class.'"'.$hd_desc_data.'>'.$arr_meta['cat_one'].'</div>';
			}
		}
		// Date
		if ((bool)$show_date_under_post_title && !empty($arr_meta['date'])) {
			$meta_code = '<div class="meta meta-date">'.$arr_meta['date'].'</div>';
		}
		// Author
		if ((bool)$show_author_under_post_title ) {
			$meta_code .= $arr_meta['author'];
		}
		// Views
		if ((bool)$show_views_under_post_title ) {
			$meta_code .= $arr_meta['views'];
		}
		// Time for reading
		$meta_code .= $arr_meta['time_for_reading'];

		// Whole meta info
		if (!empty($meta_code)) {
			$meta_code = '<div class="meta-info'.$hd_desc_plx_class.'"'.$meta_info_data.'>'.$meta_code.$arr_meta['edit_link'].'</div>';
		} else {
			if (empty($hd_title_code) && empty($page_desc)) {
				$page_class .= ' no-data';
			}
		}
	} else {
		// Get title description (create_title_desc.php)
		$arr_title = dp_current_page_title();
		// Not single page
		$hd_title_code = '<h1 class="hd-title'.$hd_title_plx_class.'"'.$hd_title_data.'><span>'.$arr_title['title'].'</span></h1>';
		if (!empty($arr_title['desc'])) {
			$page_desc = '<div class="title-desc'.$hd_desc_plx_class.'"'.$hd_desc_data.'>'.$arr_title['desc'].'</div>';
		}
	}
	// Display
	if ((bool)$hd_title_show) {
		echo '<section class="ct-hd'.$cat_color.$plx_bg_img_class.$page_class.'">'.$plx_bg_code.$hd_title_code.$page_desc.$meta_code.'</section>';
	}
};
// **********************************
// Container widget
// **********************************
if (!is_404() && is_active_sidebar('widget-container-top-mb')) : ?>
<div class="widget-container top mobile clearfix<?php echo $cur_page_class; ?>"><?php dynamic_sidebar( 'widget-container-top-mb' ); ?></div><?php
endif;