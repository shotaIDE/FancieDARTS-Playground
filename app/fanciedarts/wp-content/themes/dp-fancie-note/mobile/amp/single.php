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
$next_prev_in_same_cat = $options['next_prev_in_same_cat'];
$show_pubdate_on_meta 	= $options['show_pubdate_on_meta'];

// Meta bottom
$show_date_on_post_meta = $options['show_date_on_post_meta'];
$show_views_on_post_meta = $options['show_views_on_post_meta'];
$show_author_on_post_meta = $options['show_author_on_post_meta'];
$show_cat_on_post_meta = $options['show_cat_on_post_meta'];

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
	// Hide author prof
	$hide_author_prof 	= get_post_meta(get_the_ID(), 'dp_hide_author_prof', true);

	// **********************************
	// Get post meta codes (Call this function written in "meta_info.php")
	// **********************************
	$date_code= '';
	$first_row= '';
	$second_row= '';
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
		// Categories
		if ((bool)$show_cat_on_post_meta && !empty($arr_meta['cats'])) {
			$first_row = $arr_meta['cats'];
		}
		// Tags
		if (!empty($arr_meta['tags'])) {
			$first_row .= $arr_meta['tags'];
		}
		// Third row
		if (!empty($first_row)) {
			$first_row = '<div class="first_row">'.$first_row.'</div>';
		}
		// Date
		if ((bool)$show_date_on_post_meta && !empty($arr_meta['date'])) {
			$second_row = '<div class="meta meta-date">'.$arr_meta['date'].$arr_meta['last_update'].'</div>';
		}
		// Author
		if ((bool)$show_author_on_post_meta ) {
			$second_row .= $arr_meta['author'];
		}
		// Comment
		$second_row .= $arr_meta['comments'].$arr_meta['post_comment'];
		// Views
		if ((bool)$show_views_on_post_meta ) {
			$second_row .= $arr_meta['views'];
		}
		// edit link
		$second_row .= $arr_meta['edit_link'];
		// First row
		if (!empty($second_row)) {
			$second_row = '<div class="second_row">'.$second_row.'</div>';
		}
		// meta on bottom
		if (!empty($first_row) || !empty($second_row) ) {
			$meta_code_end = '<footer class="single_post_meta bottom">'.$first_row.$second_row.'</footer>';
		}
		// **********************************
		// Get author codes (Call this function written in "get_author_info.php")
		// **********************************
		$author_code 	= '';
		if (!(bool)$options['hide_author_info'] && !(bool)$hide_author_prof && $post_type === 'post') {
			// Import require functions
			require_once(DP_THEME_DIR . "/inc/scr/get_author_info.php");
			// Params
			$args = array('return_array'=> true,
						'is_microdata'	=> true,
						'avatar_size' 	=> 240);
			$author_info_title = !empty($options['author_info_title']) ? $options['author_info_title'] : __('About The Author', 'DigiPress');

			$arr_author 	= dp_get_author_info($args);
			$author_code 	= '<div itemscope itemtype="http://data-vocabulary.org/Person" class="author_info"><h3 class="inside-title"><span>'.$author_info_title.'</span></h3><div class="author_col1">'.$arr_author['profile_img'].'</div><div class="author_col2">'.$arr_author['author_roles'].$arr_author['author_desc'].'</div><div>'.$arr_author['author_url'].$arr_author['author_sns_code'].'</div>'.dp_get_author_posts().'</div>';
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
		if (($post_type === 'post') && is_active_sidebar('under-post-title-amp') && !post_password_required()) { ?>
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
		if ( has_post_thumbnail() && $post_type === 'post' && $page === 1 ) {
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
		if ( $post_type === 'post' && is_active_sidebar('bottom-of-post-amp') && !post_password_required()) { ?>
<div id="single-footer-widget" class="clearfix"><?php dynamic_sidebar( 'bottom-of-post-amp' ); ?></div><?php
		}
		// **********************************
		// Meta
		// **********************************
		echo $meta_code_end.$author_code;?>
</article><?php 
		// ***********************************
		// Related posts
		// ***********************************
		dp_get_related_posts();
		// **********************************
		// Prev / Next post navigation
		// **********************************
		if ($post_type !== $options['news_cpt_slug_id']) {
			// Prev next post navigation link
			$in_same_cat = (bool)$next_prev_in_same_cat;
			// Next post title
			$next_post = get_next_post($in_same_cat);
			// Previous post title
			$prev_post = get_previous_post($in_same_cat);
			// Date format
			$df = get_option("date_format");

			$nav_img_code 	= '';
			$nav_date_code 	= '';
			$nav_url 		= '';
			$nav_title 		= '';

			if ($prev_post || $next_post) {?>
<div class="single-nav dp_related_posts thumb"><ul class="clearfix"><?php
				// Prev post
				if ($prev_post) {
					if ($post_type === 'post') {
						$arg_thumb 		= array('width' => 240, 'height' => 160, "size"=>"dp-archive-thumb", "if_img_tag"=> true, "post_id" => $prev_post->ID);
						$nav_url 		= get_permalink($prev_post->ID);
						$nav_img_code 	= DP_Post_Thumbnail::get_post_thumbnail($arg_thumb);
						$nav_title 		= get_the_title($prev_post->ID);
						$nav_hide_date 	= get_post_meta($prev_post->ID, 'dp_hide_date', true);
						$nav_date_code 	= (!(bool)$nav_hide_date && (bool)$show_pubdate_on_meta) ? '<div class="rel-pub-date">'.get_the_date($df, $prev_post->ID).'</div>' : '';

						if ($nav_img_code) {
							$nav_img_code = '<div class="loop-post-thumb"><a href="'.$nav_url.'" title="'.$nav_title.'">'.$nav_img_code.'<i class="icon-left-open"></i></a></div>';
						}
					}
					echo '<li class="left">'.$nav_img_code.'<div class="excerpt_div has_thumb">'.$nav_date_code.'<h4 class="entry-title"><a href="'.$nav_url.'">'.$nav_title.'</a></h4></div></li>';
				}
				// Next post
				if ($next_post) {
					if ($post_type === 'post') {
						$arg_thumb 		= array('width' => 240, 'height' => 160, "size"=>"dp-archive-thumb", "if_img_tag"=> true, "post_id" => $next_post->ID);
						$nav_url 		= get_permalink($next_post->ID);
						$nav_img_code 	= DP_Post_Thumbnail::get_post_thumbnail($arg_thumb);
						$nav_title 		= get_the_title($next_post->ID);
						$nav_hide_date 	= get_post_meta($next_post->ID, 'dp_hide_date', true);
						$nav_date_code 	= (!(bool)$nav_hide_date && (bool)$show_pubdate_on_meta) ? '<div class="rel-pub-date">'.get_the_date($df, $next_post->ID).'</div>' : '';

						if ($nav_img_code) {
							$nav_img_code = '<div class="loop-post-thumb right"><a href="'.$nav_url.'" title="'.$nav_title.'">'.$nav_img_code.'<i class="icon-right-open"></i></a></div>';
						}
					}
					echo '<li class="right"><div class="excerpt_div has_thumb right">'.$nav_date_code.'<h4 class="entry-title"><a href="'.$nav_url.'">'.$nav_title.'</a></h4></div>'.$nav_img_code.'</li>';
				}?>
</ul></div><?php
			}	// End of ($prev_post || $next_post)
			// ***********************************
			// Comments
			// ***********************************
			dp_amp_show_comment_list();
			dp_amp_facebook_comment_box();
		} // end of $post_type !== $options['news_cpt_slug_id']
	endwhile;	// End of (have_posts())
} else {	// have_posts()
	// Not found...
	include_once(TEMPLATEPATH .'/'.DP_MOBILE_THEME_DIR.'/amp/not-found.php');
}	// End of have_posts()
// Footer
include_once(TEMPLATEPATH .'/'.DP_MOBILE_THEME_DIR.'/amp/footer.php');