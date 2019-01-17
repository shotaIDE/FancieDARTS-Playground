<?php 
require_once(TEMPLATEPATH . "/".DP_MOBILE_THEME_DIR."/amp/comments.php");
// **********************************
// Header
// **********************************
include_once(TEMPLATEPATH . "/".DP_MOBILE_THEME_DIR."/amp/header.php");

//For thumbnail size
$width = 840;
$height = 620;
$arg_thumb = array('size'=>'large', "if_img_tag"=> true);

// **********************************
// Params
// **********************************
// Common Parameters
$show_eyecatch_first = $options['show_eyecatch_first'];

// Meta bottom
$show_date_on_post_meta = $options['show_date_on_post_meta'];
$show_author_on_post_meta = $options['show_author_on_post_meta'];

// show posts
if (have_posts()) {
	global $page;
	// Count Post View
	if (function_exists('dp_count_post_views')) {
		dp_count_post_views(get_the_ID(), true);
	}

	// get post type
	$post_type 	= get_post_type();
	// Post format
	$post_format = get_post_format();
	// Show eyecatch on top 
	$show_eyecatch_force 	= get_post_meta(get_the_ID(), 'dp_show_eyecatch_force', true);
	// Show eyecatch upper the title
	$eyecatch_on_container 	= get_post_meta(get_the_ID(), 'dp_eyecatch_on_container', true);

	// **********************************
	// Get post meta codes (Call this function written in "meta_info.php")
	// **********************************
	$date_code= '';
	$first_row= '';
	$sns_code = '';
	$meta_code_end 	= '';

	// **********************************
	//  Create meta data
	// **********************************
	if (!(bool)post_password_required()) {
		$arr_meta = get_post_meta_for_single_page();
		// **********************************
		//  Meta No.2
		// **********************************
		// Reset params
		$first_row= '';
		$second_row 	= '';
		$sns_code = '';
		// Date
		if ((bool)$show_date_on_post_meta && !empty($arr_meta['date'])) {
			$first_row = '<div class="meta meta-date">'.$arr_meta['date'].$arr_meta['last_update'].'</div>';
		}
		// Author
		if ((bool)$show_author_on_post_meta ) {
			$first_row .= $arr_meta['author'];
		}
		// edit link
		$first_row .= $arr_meta['edit_link'];
		// First row
		if (!empty($first_row)) {
			$first_row = '<div class="first_row">'.$first_row.'</div>';
		}
		// meta on bottom
		if (!empty($first_row) ) {
			$meta_code_end = '<footer class="single_post_meta bottom">'.$first_row.'</footer>';
		}
	}
	// ***********************************
	// Article area start
	// ***********************************
	while (have_posts()) : the_post(); ?>
<article id="<?php echo $post_type.'-'.get_the_ID(); ?>" <?php post_class('single-article'); ?>><?php 
		// ***********************************
		// Single header widget
		// ***********************************
		if (($post_type === 'page') && is_active_sidebar('under-post-title-amp') && !post_password_required()) { ?>
<div id="single-header-widget" class="clearfix"><?php dynamic_sidebar( 'under-post-title-amp' ); ?></div><?php
		}	// End of widget

		// ***********************************
		// Main entry
		// *********************************** ?>
<div class="entry entry-content"><?php
		// ***********************************
		// Show eyecatch image
		// ***********************************
		$flag_eyecatch_first = false;
		if ( has_post_thumbnail() && $post_type === 'page' && $page === 1 ) {
			 if ( $show_eyecatch_first ) {
			 	if ( !($show_eyecatch_force && $eyecatch_on_container) ) {
			 		$flag_eyecatch_first = true;
				}
			 } else {
				if ( $show_eyecatch_force && !$eyecatch_on_container ) {
					$flag_eyecatch_first = true;
				}
			 }
		}
		if ( $flag_eyecatch_first ) {
			$image_id	= get_post_thumbnail_id();
			$image_data	= wp_get_attachment_image_src($image_id, 'large', true);
			$image_url 	= is_ssl() ? str_replace('http:', 'https:', $image_data[0]) : $image_data[0];
			$img_tag	= '<amp-img layout="responsive" src="'.$image_url.'" width="'.$image_data[1].'" height="'.$image_data[2].'" class="wp-post-image aligncenter" alt="'.strip_tags(get_the_title()).'"  /></amp-img>';
			echo '<div class="eyecatch-under-title">' . $img_tag . '</div>';
		}
		// Content
		the_content();?>
</div><?php 	// End of class="entry"
		// ***********************************
		// Single footer widget
		// ***********************************
		if ( $post_type === 'page' && is_active_sidebar('bottom-of-post-amp') && !post_password_required()) { ?>
<div id="single-footer-widget" class="clearfix"><?php dynamic_sidebar( 'bottom-of-post-amp' ); ?></div><?php
		}
		// **********************************
		// Meta
		// **********************************
		echo $meta_code_end;?>
</article><?php 
	endwhile;	// End of (have_posts())
} else {	// have_posts()
	// Not found...
	include_once(TEMPLATEPATH .'/'.DP_MOBILE_THEME_DIR.'/amp/not-found.php');
}	// End of have_posts()
// Footer
include_once(TEMPLATEPATH .'/'.DP_MOBILE_THEME_DIR.'/amp/footer.php');