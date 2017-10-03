<?php 
function dp_headline($echo = true) {
	global $IS_MOBILE_DP, $options;

	if (!(bool)$options['show_headline_ticker']) return;
	if ( $IS_MOBILE_DP && (bool)$options['hide_headline_mobile'] ) return;
	if ( !(is_front_page() && !is_paged() && !isset( $_REQUEST['q'])) ) return;

	$main_title = '';
	$cat = '';
	$code = '';
	$cat_color_class = '';

	// Main Title
	$main_title = ( $options['headline_ticker_main_title'] && !$IS_MOBILE_DP ) ? '<div class="headline_main_title"><h1>'.htmlspecialchars_decode($options['headline_ticker_main_title']).'</h1></div>' : '';

	// Ticker code
	if ($options['headline_ticker_target'] === 'post') {
		// *******************
		// target : post
		// *******************
		global $post;
		// Query
		$posts = get_posts( array(
							'numberposts' => $options['headline_ticker_num'],
							'meta_key' => 'is_headline',
							'meta_value' => array('true',true),
							'orderby' => $options['headline_ticker_order'] // post_date or rand
							)
		);
		// Loop query posts
		foreach( $posts as $post ) {
			setup_postdata($post);
			// Category
			if ((bool)$options['headline_ticker_cat']){
				$cat = get_the_category();
				if ( $cat[0] ) {
					$cat_color_class = " cat-color".$cat[0]->cat_ID;
					$cat = '<a href="'.get_category_link( $category[0]->term_id ).'" class="cat_link'.$cat_color_class.'">'.$cat[0]->cat_name.'</a>';
				}
			}
			// target post
			if ($options['headline_ticker_date']) {
				$code .= '<li><time datetime="'. get_the_time('c').'" pubdate="pubdate">'.get_the_date().'</time>'.$cat.'<a href="'.get_permalink().'" class="title_link">'.get_the_title().'</a></li>';
			} else {
				$code .= '<li>'.$cat.'<a href="'.get_permalink().'">'.get_the_title().'</a></li>';
			}
		}
		// Reset query
		wp_reset_postdata();
	} else {
		// *******************
		// target : text
		// *******************
		// into array
		$slides = array();
		// Ticker code
		for ($i = 1; $i < 6; $i++) {
			if ($options['headline_ticker_text'.$i]) {
				$slides[] = htmlspecialchars_decode($options['headline_ticker_text'.$i]);
			}
		}
		// Shuffle
		if ($options['headline_ticker_shuffle']) shuffle($slides);
		// Get headline
		foreach ($slides as $slide) {
			$code .= '<li>'.$slide.'</li>';
		}
	}
	// html
	$code = '<section id="headline-sec" class="clearfix"><div class="inner">'.$main_title.'<div id="headline-ticker" class="ticker"><ul>'.$code.'</ul></div></div></section>';

	if ($echo) {
		echo $code;
	} else {
		return $code;
	}
}
function dp_headline_js() {
	global $options;
	if (!(bool)$options['show_headline_ticker']) return;
	if ( !(is_front_page() && !is_paged() && !isset( $_REQUEST['q'])) ) return;

	$code = 
"<script>j$(function() {j$.simpleTicker(j$('#headline-ticker'),{'effectType':'".$options['headline_ticker_fx']."','easing':'".$options['headline_ticker_easing']."','speed':".$options['headline_ticker_speed'].",'delay':".$options['headline_ticker_delay']."});});</script>";
	echo $code;
}