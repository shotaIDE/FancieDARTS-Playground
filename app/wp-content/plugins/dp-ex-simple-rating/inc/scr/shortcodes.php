<?php
/**
 * Display best rated posts
 * 
 * @return string;
 */
function dp_ex_simrat_shortcode_best_rated($atts) {
	if (is_admin()) return;
	if (!class_exists('DP_EX_SIMPLE_RATING')) return;
	if (!function_exists('dp_ex_simple_rating_status')) return;

	// Status check
	$status = dp_ex_simple_rating_status();
	if (!$status) return;

	// Meta key
	$voted_like_count_key = DP_EX_SIMPLE_RATING::$voted_like_count_key;

	// Add shortcode to the theme
	extract(shortcode_atts(array(
		'num'		=> '5',
		'date'		=> 1,
		'views'		=> 0,
		'hatebu'	=> 0,
		'tweets'	=> 0,
		'likes'		=> 0,
		'excerpt'	=> 0,
		'thumb' 	=> 1,
		'thumbwidth' => 90,
		'thumbheight' => 53,
		'ranking' 	=> 1,
		'ratecount' => 1,
		'icon'  	=> 'icon-heart',
		'year'		=> '',
		'month'		=> '',
		'cat'		=> '',
		'type'		=> ''
	), $atts));

	global $post;

	// Category
	$cat = str_replace("\s", "", $cat);

	// Get posts
	$best_posts = get_posts(array(
					'numberposts'	=> $num,
					'category'		=> $cat,
					'post_type'		=> $type,
					'meta_key'		=> $voted_like_count_key,
					'orderby'		=> 'meta_value_num',
					'year'			=> $year,
					'month'			=> $month
					)
	);

	$result  = '';	
	$viewsCode = '';
	$thumb_code = '';
	$hatebuNumberCode = '';
	$tweetCountCode = '';
	$fbLikeCountCode = '';
	$sns_share_code = '';
	$post_title = '';
	$unique_id = '';
	$js_code = '';
	$date_code = '';
	$desc = '';
	$counter = 0;

	foreach($best_posts as $post) :
		setup_postdata($post);

		$post_title = '';
		$unique_id = '';
		$js_code = '';
		$voted_code = '';

		// Title
		$post_title =  the_title('', '', false) ? the_title('', '', false) : __('No Title', 'DigiPress');

		// Date
		if ( ((bool)$date) ) {
			$date_code = '<span class="ft12px">' . get_the_date() . '</span> : ';
		}

		// Ranking tag
		if ((bool)$ranking) {
			$counter++;
			if ((bool)$thumb) {
				$ranking_code = '<div class="rank_label thumb">'.$counter.'</div>';
			} else {
				$ranking_code = '<div class="rank_label no-thumb">'.$counter.'</div>';
			}
		}

		// Voted count
		if ( (bool)$ratecount ) {
			if (!empty($icon)) {
				$voted_icon_class = ' class="'.$icon.'"';
			}
			$voted_code = get_post_meta(get_the_ID(), $voted_like_count_key, true);
			$voted_code = '<div'.$voted_icon_class.'><span class="share-num">'.$voted_code.'</span></div>';
		}


		// Excerpt
		if ((bool)$excerpt) {
			$desc = get_the_excerpt();
			if (mb_strlen($desc) > 64) $desc = mb_substr($desc, 0, 64).'…';
			$desc = '<div class="ft11px mg10px-btm">'.$desc.' <br /><div class="entrylist-cat"><a href="'.get_permalink().'" title="'.__('Read more', 'DigiPress').'">'.__('Read more', 'DigiPress').'</a></div></div>';
		}

		$viewsCode = ((bool)$views) ? '<span class="mg5px-l ft12px meta_views">'.dp_get_post_views(get_the_ID(), $term).' views</span>' : '';

		// ************* SNS sahre number *****************
		// hatebu
		if ((bool)$hatebu) {
			$hatebuNumberCode = '<div class="bg-hatebu icon-hatebu"><span class="share-num"></span></div>';
		}
			
		// Count tweets
		if ((bool)$tweets) {
			$tweetCountCode = '<div class="bg-tweets icon-twitter"><span class="share-num"></span></div>';
		}

		// Count Facebook Like 
		if ((bool)$likes) {
			$fbLikeCountCode = '<div class="bg-likes icon-facebook"><span class="share-num"></span></div>';
		}
		if ((bool)$hatebu || (bool)$tweets || (bool)$likes || (bool)$ratecount) {
			// Get Unique ID
			$unique_id 			= 'scmvp-'.dp_rand().'-'.get_the_ID();
			$sns_share_code = '<div class="loop-share-num in-blk">'.$hatebuNumberCode.$tweetCountCode.$fbLikeCountCode.$voted_code.'</div>';
			$js_code = '<div><script>j$(function() {get_sns_share_count("'.get_permalink().'", "'.$unique_id.'");});</script></div>';
		}
		// ************* SNS sahre number *****************

		// Thumbnail
		if ((bool)$thumb) {
			$thumbwidth = (int)$thumbwidth ? $thumbwidth : 90;
			$thumbheight = (int)$thumbheight ? $thumbheight : 90;
			$thumb_code = show_post_thumbnail($thumbwidth, $thumbheight, $post->ID);

			$result .= '<li id="'.$unique_id.'" class="clearfix"><div class="widget-post-thumb">'.$thumb_code.$ranking_code.'</div><div class="excerpt_div"><div class="excerpt_title_div">'.$date_code.'<h4 class="excerpt_title_wid"><a href="'.get_permalink().'" title="'. get_the_title() .'">' . $post_title .'</a></h4> ' . $sns_share_code . $viewsCode .'</div>'.$desc.'</div>'.$js_code.'</li>';
		} else {
			$result .= '<li id="'.$unique_id.'" class="clearfix"><div class="excerpt_div"><div class="excerpt_title_div">'.$date_code.'<h4 class="excerpt_title_wid"><a href="'.get_permalink().'" title="'. get_the_title() .'">' . $post_title .'</a></h4> ' . $sns_share_code . $viewsCode .'</div>'.$desc.'</div>'.$ranking_code.$js_code.'</li>';
		}

	endforeach;

	if ((bool)$thumb) {
		$result = '<ul class="recent_entries thumb">'.$result.'</ul>';
	} else {
		$result = '<ul class="recent_entries">'.$result.'</ul>';
	}
	// Reset Query
	wp_reset_query();

	return $result;
}

// Register shortcode
add_shortcode("bestratedposts", "dp_ex_simrat_shortcode_best_rated");
