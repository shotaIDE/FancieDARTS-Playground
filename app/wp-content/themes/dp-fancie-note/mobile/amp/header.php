<?php 
global $options, $options_visual;
// Header flag
$main_wrap_class = "main-wrap no-header not-home";
$content_class = " not-home";
if ( is_home() && !is_paged() ) {
	$content_class = " home";
	if ($options_visual['dp_header_content_type'] === "none" ) {
		$main_wrap_class = "main-wrap no-header";
	} else {
		$main_wrap_class = "main-wrap home";
	}
}
// Custom CSS
require_once(TEMPLATEPATH."/inc/scr/create_css_amp.php");
?><!DOCTYPE html><html ⚡><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,minimum-scale=1,initial-scale=1" /><?php
// **********************************
// meta for robots
// **********************************
if ( (is_home() || is_archive()) && is_paged()) : ?>
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
// title
dp_site_title('<title>', '</title>');
// meta canonical
dp_show_canonical();
// show keyword and description
dp_meta_kw_desc();
// show OGP
dp_show_ogp(); ?>
<style amp-boilerplate>body{-webkit-animation:-amp-start 8s steps(1,end) 0s 1 normal both;-moz-animation:-amp-start 8s steps(1,end) 0s 1 normal both;-ms-animation:-amp-start 8s steps(1,end) 0s 1 normal both;animation:-amp-start 8s steps(1,end) 0s 1 normal both}@-webkit-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-moz-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-ms-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-o-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}</style><noscript><style amp-boilerplate>body{-webkit-animation:none;-moz-animation:none;-ms-animation:none;animation:none}</style></noscript>
<style amp-custom>@font-face{font-family:"dpicons";src:url('<?php echo DP_THEME_URI.'/css/'; ?>fonts/normal');src:url('<?php echo DP_THEME_URI.'/css/'; ?>fonts/normal?#iefix') format('embedded-opentype'),url('<?php echo DP_THEME_URI.'/css/'; ?>fonts/dpicons.woff') format('woff'),url('<?php echo DP_THEME_URI.'/css/'; ?>fonts/dpicons.ttf') format('truetype');font-weight:normal}@media screen and (-webkit-min-device-pixel-ratio:0){@font-face{font-family:"dpicons";src:url('<?php echo DP_THEME_URI.'/css/'; ?>fonts/dpicons.svg') format('svg')}}<?php

// include the css for AMP
include_once(TEMPLATEPATH . "/".DP_MOBILE_THEME_DIR."/css/amp.css");
// hook
do_action('dp_amp_custom_css');
// Display custom CSS (create_css_amp.php)
dp_custom_css_amp(); ?>
</style><?php
// **********************************
// JSON-LD for Structured Data
// **********************************
dp_json_ld();
do_action('dp_amp_library');
// Custom head
do_action('dp_amp_head');?>
</head>
<body id="main-body" <?php body_class('main-body'); ?>><?php
do_action('dp_amp_under_body'); ?>
<div id="main-wrap" class="<?php echo $main_wrap_class; ?>"><?php
// **********************************
// Site header
// **********************************
include_once(TEMPLATEPATH . "/".DP_MOBILE_THEME_DIR."/amp/site-header.php");
// **********************************
// Main content
// **********************************?>
<main id="content" class="content<?php echo $content_class; ?>"><?php
// **********************************
// Content header
// **********************************
if (!is_home()) {
	$hide_title_flag = false;
	$hd_title_code = '';
	$page_desc = '';
	$page_class = '';
	$cat_color = '';
	$meta_code = '';
	$ct_hd_eyecatch_code = '';
	// Background image
	if (is_category()) {
		global $cat;
		$cat_color = " cat-color".$cat;
	} else if (is_singular()) {
		global $page;
		$page_class = ' singular';
		$arr_http = array('http:','https:');
		$image_url_f 	= '';
		// Hide title flag
		$hide_title_flag = get_post_meta(get_the_ID(), 'dp_hide_title', true);
		// Show eyecatch on top
		$show_eyecatch_force 	= get_post_meta(get_the_ID(), 'dp_show_eyecatch_force', true);
		// Show eyecatch upper the title
		$eyecatch_on_container 	= get_post_meta(get_the_ID(), 'dp_eyecatch_on_container', true);
		if(has_post_thumbnail() && $show_eyecatch_force && $eyecatch_on_container && $page === 1) {
			$image_id_f = get_post_thumbnail_id();
			$image_data_f = wp_get_attachment_image_src($image_id_f, 'large', true);
			$image_url_f = is_ssl() ? str_replace($arr_http,'',$image_data_f[0]) : $image_data_f[0];
			$img_tag_f = '<amp-img layout="responsive" src="'.$image_url_f.'" width="'.$image_data_f[1].'" height="'.$image_data_f[2].'" class="wp-post-image aligncenter" alt="'.strip_tags(get_the_title()).'"  /></amp-img>';
			$ct_hd_eyecatch_code = '<div class="single-eyecatch-container">' . $img_tag_f . '</div>';
		}

		// Get meta info
		$arr_meta = get_post_meta_for_single_page();
		// Meta under the title
		$show_date_under_post_title = $options['show_date_under_post_title'];
		$show_views_under_post_title = $options['show_views_under_post_title'];
		$show_author_under_post_title = $options['show_author_under_post_title'];
		$show_cat_under_post_title 	= $options['show_cat_under_post_title'];

		// Single post
		if (is_single()) {
			// Hide category
			if ((bool)$show_cat_under_post_title && !empty($arr_meta['cat_one'])) {
				$page_desc = '<div class="title-desc">'.$arr_meta['cat_one'].'</div>';
			}
		}
		// Date
		if ((bool)$show_date_under_post_title && !empty($arr_meta['date'])) {
			$meta_code = '<div class="meta meta-date">'.$arr_meta['date'].$arr_meta['last_update'].'</div>';
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
			$meta_code = '<div class="meta-info">'.$meta_code.$arr_meta['edit_link'].'</div>';
		} else {
			if (empty($hd_title_code) && empty($page_desc)) {
				$page_class .= ' no-data';
			}
		}
	}
	// Display
	if (!(bool)$hide_title_flag) {
		$arr_title = dp_current_page_title();
		$hd_title_code = '<h1 class="hd-title"><span>'.$arr_title['title'].'</span></h1>';
		if (!empty($arr_title['desc'])) {
			$page_desc = '<div class="title-desc">'.$arr_title['desc'].'</div>';
		}
		echo '<section class="ct-hd'.$page_class.$cat_color.'">'.$ct_hd_eyecatch_code.$hd_title_code.$page_desc.$meta_code.'</section>';
	}
}
// **********************************
// Container top widget
// **********************************
if (is_active_sidebar('widget-top-container-amp')) : ?>
<div class="ctn-wdgt top clearfix"><?php dynamic_sidebar( 'widget-top-container-amp' ); ?></div><?php
endif;