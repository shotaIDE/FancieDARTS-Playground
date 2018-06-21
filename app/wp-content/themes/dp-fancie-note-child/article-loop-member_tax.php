<?php 
// ***********************************
// Get archive style (/inc/scr/get_archive_style.php)
// return {$top_or_archive, $archive_type, $layout}
//params before extraction
$top_or_archive = '';
extract($ARCHIVE_STYLE);

// Articles
if (is_home() && !(bool)$options['show_'.$top_or_archive.'_under_content']) return;

// Counter
$i = 0;

// Params
$html_code = '';
$loop_code = '';
$cat_name = '';
$more_url = '';
$page_desc = '';
$suffix_mb = '';
$masonry_lines	= '';
$masony_gutter 	= '<div class="gutter_size"></div>';
$read_more_str = empty($options[$top_or_archive.'_readmore_str']) ? 'Read More' : $options[$top_or_archive.'_readmore_str'];

// Column
$col_class = ' two-col';
if ($COLUMN_NUM == 1 ) {
	$col_class = ' one-col';
} else if ($COLUMN_NUM == 3) {
	$col_class = ' three-col';
}

// Mobile or PC
if ($IS_MOBILE_DP){
	$suffix_mb	= '_mb';
	$col_class = '';
	$masonry_lines	= ' one_line';
	if (strpos($layout, 'magazine') !== false) {
		$layout = 'magazine mobile';
	} else if (strpos($layout, 'news') !== false) {
		$layout = 'news mobile';
	} else {
		$layout = 'normal mobile';
	}
} else {
	if ($COLUMN_NUM !== 1 && $options[$top_or_archive.'_masonry_lines'] === 'four_lines') {
		$masonry_lines= ' three_lines';
	} else {
		$masonry_lines= ' '.$options[$top_or_archive.'_masonry_lines'];
	}
}
// AMP
if ($IS_AMP_DP) {
	$layout = 'normal mobile';
}
// wow.js
$wow_title_css 	= '';
$wow_article_css = '';
if (!(bool)$options['disable_wow_js'.$suffix_mb] && !$IS_AMP_DP){
	$wow_title_css = ' wow fadeInLeft';
	$wow_article_css 	= ' wow fadeInUp';
}

// Description
$arr_title = dp_current_page_title();
if (!empty($arr_title['desc'])) {
	$page_desc = '<div class="title-desc">'.$arr_title['desc'].'</div>';
}

// Excerpt length
$excerpt_length	= $options[$top_or_archive.'_normal_excerpt_length'];
// Autopager flag
$autopager_class = (bool)$options['autopager'.$suffix_mb] ? ' autopager' : '';
// Reverse thumbnail position when standard
$rev_thumb_class = '';
// Overlay color
$overlay_color = ' '.$options[$top_or_archive.'_overlay_color'];

// Params
$args = array(
		'pub_date'=> $options[$top_or_archive.'_archive_list_date'],
		'show_cat'=> $options[$top_or_archive.'_archive_list_cat'],
		'views'=> $options[$top_or_archive.'_archive_list_views'],
		'author'=> $options[$top_or_archive.'_archive_list_author'],
		'hatebu_num'=> $options['hatebu_number_after_title_'.$top_or_archive],
		'likes_num'=> $options['likes_number_after_title_'.$top_or_archive],
		'tweets_num'=> $options['tweets_number_after_title_'.$top_or_archive],
		'masonry_lines'=> $masonry_lines,
		'post_type'=> get_post_type(),
		'col_class' => $col_class,
		'type'=> '',
		'more_text'=> '',
		'voted_icon' => '',
		'voted_count' => false,
		'layout'=> $layout,
		'excerpt_length' => $excerpt_length,
		'overlay_color'=> $overlay_color,
		'read_more_str' => $read_more_str,
		'wow_article_css'=> $wow_article_css
		);

// Settings for infeed ads
$infeed_ads_flg = false;
$infeed_ads_code = '';
$infeed_ads_order = null;
if (!empty($options[$top_or_archive.'_infeed_ads_code'.$suffix_mb]) && !empty($options[$top_or_archive.'_infeed_ads_order'.$suffix_mb]) && !$IS_AMP_DP) {
	$infeed_ads_flg = true;
	$infeed_ads_code = $options[$top_or_archive.'_infeed_ads_code'.$suffix_mb];
	$infeed_ads_order = explode(",", $options[$top_or_archive.'_infeed_ads_order'.$suffix_mb]);
}
/**
 * Infeed ads
 * @return string            infeed ads code
 */
function dp_get_infeed_ads($arr_params = array(
	'top_or_archive' => '',
	'suffix_mb' => '',
	'post_num' => '',
	'infeed_ads_order' => '',
	'infeed_ads_code' => '',
	'wrapper_before' => '<div class="loop-article">',
	'wrapper_after' => '</div>')){
	extract($arr_params);

	if (is_array($infeed_ads_order)) {
		foreach ($infeed_ads_order as $ads_num) {
			if ($ads_num == $post_num) {
				return $wrapper_before.$infeed_ads_code.$wrapper_after;
			}
		}
	} else if ($post_num == $infeed_ads_order) {
		return $wrapper_before.$infeed_ads_code.$wrapper_after;
	}
}
// ***********************************
// for FancieDARTS [START]: タクソノミーの説明を表示
// ***********************************
$term_code = '';
$term = get_queried_object();
$term_name = $term->name;
$term_description = term_description(); // 整形されたデータ取得のため、$term->description は利用しない
if ($term_description !== '') {
	$term_code = sprintf('<div class="widget-content bottom clearfix"><div id="dprecentcustompostswidget-2" class="widget-box dp_recent_custom_posts_widget slider_fx"><h3 class="inside-title wow fadeInLeft" style="visibility: visible; animation-name: fadeInLeft;"><span>%s</span></h3>
		<div class="entry entry-content" style="padding-left: 10px">%s</div></div></div>',
		$term_name,
		$term_description);
}
echo $term_code;
// ***********************************
// for FancieDARTS [END]: タクソノミーの説明を表示
// ***********************************
/**
 * Show post list
 *
 * Call the function written in "listing_post_styles.php"
 */
switch ($layout) {
	case 'normal':
		// Title length
		$args['title_length'] = 100;
		// Reverse thumbnail position when standard
		$rev_thumb_class = (bool)$options[$top_or_archive.'_rev_thumb_odd_even'] ? ' rev_thumb' : '';
		// No masonry
		$masony_gutter = '';
		// Get articles
		foreach( $posts as $post_num => $post ) : setup_postdata($post);
			if ($infeed_ads_flg){
				$loop_code .= dp_get_infeed_ads(array(
					'top_or_archive' => $top_or_archive,
					'suffix_mb' => $suffix_mb,
					'post_num' => $post_num + 1,
					'infeed_ads_order' => $infeed_ads_order,
					'infeed_ads_code' => $infeed_ads_code,
					'wrapper_before' => '<div class="loop-article'.$col_class.$wow_article_css.'">',
					'wrapper_after' => '</div>'
					));
			}
			// Comment opened?
			$args['comment'] = comments_open();
			// Get post element (listing_post_styles.php)
			$arr_post_ele = dp_post_list_get_elements($args);
			// Get article html and display (listing_post_styles.php)
			$loop_code .= dp_show_post_list_for_archive_normal($args, $arr_post_ele);
		endforeach;
		wp_reset_postdata();
		break;
	case 'normal mobile':
		$args['title_length'] = 58;
		$rev_thumb_class = (bool)$options[$top_or_archive.'_rev_thumb_odd_even'] ? ' rev_thumb' : '';
		$masony_gutter = '';
		foreach( $posts as $post_num => $post ) : setup_postdata($post);
			if ($infeed_ads_flg){
				$loop_code .= dp_get_infeed_ads(array(
					'top_or_archive' => $top_or_archive,
					'suffix_mb' => $suffix_mb,
					'post_num' => $post_num + 1,
					'infeed_ads_order' => $infeed_ads_order,
					'infeed_ads_code' => $infeed_ads_code,
					'wrapper_before' => '<div class="loop-article'.$wow_article_css.'">',
					'wrapper_after' => '</div>'
					));
			}
			$args['comment'] = comments_open();
			$arr_post_ele = dp_post_list_get_elements($args);
			$loop_code .= dp_show_post_list_for_archive_normal_mb($args, $arr_post_ele);
		endforeach;
		wp_reset_postdata();
		break;
	case 'blog':
		$args['title_length'] = 120;
		$masony_gutter = '';
		foreach( $posts as $post_num => $post ) : setup_postdata($post);
			if ($infeed_ads_flg){
				$loop_code .= dp_get_infeed_ads(array(
					'top_or_archive' => $top_or_archive,
					'suffix_mb' => $suffix_mb,
					'post_num' => $post_num + 1,
					'infeed_ads_order' => $infeed_ads_order,
					'infeed_ads_code' => $infeed_ads_code,
					'wrapper_before' => '<div class="loop-article'.$col_class.$wow_article_css.'">',
					'wrapper_after' => '</div>'
					));
			}
			$args['comment'] = comments_open();
			$arr_post_ele = dp_post_list_get_elements($args);
			$loop_code .= dp_show_post_list_for_archive_blog($args, $arr_post_ele);
		endforeach;
		wp_reset_postdata();
		break;
	case 'magazine one':
		$args['title_length'] = 82;
		foreach( $posts as $post_num => $post ) : setup_postdata($post);
			if ($infeed_ads_flg){
				$loop_code .= dp_get_infeed_ads(array(
					'top_or_archive' => $top_or_archive,
					'suffix_mb' => $suffix_mb,
					'post_num' => $post_num + 1,
					'infeed_ads_order' => $infeed_ads_order,
					'infeed_ads_code' => $infeed_ads_code,
					'wrapper_before' => '<div class="loop-article'.$col_class.$masonry_lines.$wow_article_css.'">',
					'wrapper_after' => '</div>'
					));
			}
			$args['comment'] = comments_open();
			$arr_post_ele = dp_post_list_get_elements($args);
			$loop_code .= dp_show_post_list_for_archive_magazine1($args, $arr_post_ele);
		endforeach;
		wp_reset_postdata();
		break;
	case 'magazine mobile':
		$args['title_length'] = 60;
		foreach( $posts as $post_num => $post ) : setup_postdata($post);
			if ($infeed_ads_flg){
				$loop_code .= dp_get_infeed_ads(array(
					'top_or_archive' => $top_or_archive,
					'suffix_mb' => $suffix_mb,
					'post_num' => $post_num + 1,
					'infeed_ads_order' => $infeed_ads_order,
					'infeed_ads_code' => $infeed_ads_code,
					'wrapper_before' => '<div class="loop-article'.$col_class.$big_class.$masonry_lines.$wow_article_css.'">',
					'wrapper_after' => '</div>'
					));
			}
			$args['is_big'] = ++$i === 1 ? true : false;
			$args['comment'] = comments_open();
			$arr_post_ele = dp_post_list_get_elements($args);
			$loop_code .= dp_show_post_list_for_archive_magazine_mb($args, $arr_post_ele);
		endforeach;
		wp_reset_postdata();
		break;
	case 'news':
	case 'news mobile':
		$args['title_length'] = 100;
		foreach( $posts as $post_num => $post ) : setup_postdata($post);
			$arr_post_ele = dp_post_list_get_elements($args);
			$loop_code .= dp_show_post_list_for_archive_news($args, $arr_post_ele);
		endforeach;
		wp_reset_postdata();
		break;
}
/**
 * Artcle list section source
 */
$html_code = '<section class="loop-section '.$layout.$col_class.$masonry_lines.$rev_thumb_class.' clearfix">';
// ************************
// Main title (Only Top page)
// ************************
if (is_home()) {
	if ($options['top_posts_list_title'] && !is_paged()) {
		$html_code .= '<header class="loop-sec-header"><h1 class="inside-title'.$wow_title_css.'"><span>'.$options['top_posts_list_title'].'</span></h1></header>';
	} else {
		$html_code .= '<header class="loop-sec-header"><h1 class="inside-title'.$wow_title_css.'"><span>'.$arr_title['title'].'</span></h1></header>';
	}
}
// Whole html code
$html_code .= '<div class="loop-div autopager'.$col_class.' clearfix">'.$masony_gutter.$loop_code.'</div></section>';
// Display
echo $html_code;


// ***********************************
// Navigation
// ***********************************
$navigation_text_to_2page = isset($options['navigation_text_to_2page_'.$top_or_archive]) && !empty($options['navigation_text_to_2page_'.$top_or_archive]) ? $options['navigation_text_to_2page_'.$top_or_archive] : '';
$next_page_link = is_ssl() ? str_replace('http:', 'https:', get_next_posts_link('<span class="r-wrap">'.$navigation_text_to_2page.'</span>')) : get_next_posts_link('<span class="r-wrap">'.$navigation_text_to_2page.'</span>');
$prev_page_link = is_ssl() ? str_replace('http:', 'https:', get_previous_posts_link()) : get_previous_posts_link();

if (!empty($next_page_link)) :
	// 1st page
	if ( !is_paged() || ((bool)$options['autopager'.$suffix_mb] && !$IS_AMP_DP) ) :
		if (!empty($navigation_text_to_2page)) :?>
<nav class="navigation clearfix"><div class="nav_to_paged"><?php echo $next_page_link; ?></div></nav><?php
		endif;
	else: // Paged
		if (function_exists('wp_pagenavi')) : ?>
<nav class="navigation clearfix"><?php wp_pagenavi(); ?></nav><?php
		else :
			if ($options['pagenation']) :
					dp_pagenavi('<nav class="navigation clearfix">', '</nav>');
			else : ?>
<nav class="navigation clearfix">
<div class="navialignleft"><?php previous_posts_link(__('<span class="nav-arrow r-wrap"><i class="icon-left-open"></i></span>', '')) ?></div>
<div class="navialignright"><?php next_posts_link(__('<span class="nav-arrow r-wrap"><i class="icon-right-open"></i></span>', '')) ?></div>
</nav><?php
			endif; 	// end of $options['pagenation']
		endif;	// end of function_exists('wp_pagenavi')
	endif;	// $options['autopager'.$suffix_mb] || (is_front_page() && !is_paged())
else :	// !empty($next_page_link)
	// Last page
	if (!empty($prev_page_link)) :
		if (!(bool)$options['autopager'.$suffix_mb] || $IS_AMP_DP) :
			if ((function_exists('wp_pagenavi'))) : ?>
<nav class="navigation clearfix"><?php wp_pagenavi(); ?></nav><?php
			else :
				if ($options['pagenation']) :
					dp_pagenavi('<nav class="navigation clearfix">', '</nav>');
				else :?>
<nav class="navigation clearfix"><div class="navialignleft"><?php previous_posts_link(__('<span class="nav-arrow r-wrap"><i class="icon-left-open"></i></span>', '')) ?></div></nav><?php
				endif; 	// end of $options['pagenation']
			endif;	// end of function_exists('wp_pagenavi')
		endif;	// end of $options['autopager'.$suffix_mb
	endif; // $prev_page_link
endif;	// !empty($next_page_link)