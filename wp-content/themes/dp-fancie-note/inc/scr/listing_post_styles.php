<?php 
/**
 * Listing article styles for the widgets in container in archive page
 */
/**
 * Params
 */
function dp_post_list_get_elements($args) {

	global $post, $IS_MOBILE_DP;

	/**
	 * Init params
	 */
	// For 'extract($args)' params
	$meta_key 			= '';
	$voted_count 		= '';
	// Title
	$post_title 		= '';
	// Share number
	$hatebuNumberCode 	= '';
	$tweetCountCode		= '';
	$fbLikeCountCode	= '';
	$sns_insert_content = '';
	$sns_share_code 	= '';
	$hatebu_num = false;
	$tweets_num = false;
	$likes_num = false;

	// Thumbnail
	$thumbnail_code	= '';
	//For thumbnail size
	$width = 800;
	$height = 800;
	// Slider js
	$js_slider = '';

	// Viewed rank counter
	$counter 		= 0;

	// For Meta
	$footer_code 	= '';
	$meta_code 		= '';
	$desc 			= '';
	$ranking_code 	= '';
	$vimeo_hash 	= '';
	$embed_code 	= '';
	$arr_meta 		= array();

	// Return elements
	$arr_post_ele 	= array();

	// Extract params
	extract($args);

	// Post format
	$post_format = get_post_format($post->ID);
	// Post type
	$post_type = get_post_type();
	// Tag of the post
	$post_label = get_post_meta(get_the_ID(), 'dp_post_tag', true);
	// Tag color of the post
	$label_color = get_post_meta(get_the_ID(), 'dp_post_tag_color', true);
	// Video ID(YouTube only)
	$videoID = get_post_meta($post->ID, 'item_video_id', true);
	// Target video service
	$video_service = get_post_meta($post->ID, 'video_service', true);
	// Slider images
	$sl_img1 = get_post_meta($post->ID, 'dp_archive_slider_img1', true);
	$sl_img2 = get_post_meta($post->ID, 'dp_archive_slider_img2', true);
	$sl_img3 = get_post_meta($post->ID, 'dp_archive_slider_img3', true);
	// Get icon class each post format
	$titleIconClass = post_format_icon($post_format);
	// For media icon
	$media_icon_code = '<div class="loop-media-icon"><i class="'.$titleIconClass.'"></i></div>';


	// Get post meta codes (Call this function written in "meta_info.php")
	$arr_meta = get_post_meta_for_listing_post_styles($args);

	// Ranking tag
	if (strpos($meta_key, 'post_views_count') !== false || !empty($voted_count) ) {
		$counter++;
		$ranking_code = '<span class="rank_label thumb">'.$counter.'</span>';
	}

	// ************* SNS sahre number *****************
	// hatebu
	if ((bool)$hatebu_num) {
		$hatebuNumberCode = '<div class="bg-hatebu"><i class="icon-hatebu"></i><span class="share-num"></span></div>';
	}	
	// Count tweets
	// if ((bool)$tweets_num) {
	// 	$tweetCountCode = '<div class="bg-tweets"><i class="icon-twitter"></i><span class="share-num"></span></div>';
	// }
	// Count Facebook Like 
	if ((bool)$likes_num) {
		$fbLikeCountCode = '<div class="bg-likes"><i class="icon-facebook"></i><span class="share-num"></span></div>';
	}
	/***
	 * Filter hook
	 */
	$sns_insert_content = apply_filters( 'dp_top_insert_sns_content', get_the_ID() );
	if ($sns_insert_content == get_the_ID() || !is_string($sns_insert_content)) {
		$sns_insert_content = '';
	}

	// Whole share code
	$sns_share_code = ($hatebu_num || $tweets_num || $likes_num || !empty($sns_insert_content)) ? '<div class="loop-share-num">'.$fbLikeCountCode.$tweetCountCode.$hatebuNumberCode.$arr_meta['comments'].$sns_insert_content.'</div>' : '';
	

	// Post title
	$post_title =  the_title('', '', false) ? the_title('', '', false) : 'No Title'; //__('No Title', 'DigiPress');
	// Title
	$post_title = (mb_strlen($post_title, 'utf-8') > $title_length) ? mb_substr($post_title, 0, $title_length, 'utf-8') . '...' : $post_title;

	//Post excerpt
	if ((int)$excerpt_length !== 0) {
		$desc = strip_tags(get_the_excerpt());
		$desc = (mb_strlen($desc,'utf-8') > $excerpt_length) ? mb_substr($desc, 0, $excerpt_length,'utf-8').'...' : $desc;
		$desc = !empty($desc) ? '<div class="loop-excerpt entry-summary">'.$desc.'</div>' : '';
	}

	// Post tag(custom field)
	if ( !empty($post_label) ) {
		if ( !empty($label_color) ) {
			$label_color = ' style="background-color:'.$label_color.';"';
		} else {
			$label_color = '';
		}
		$post_label = '<div class="label_ft"'.$label_color.'>'.$post_label.'</div>';
	}

	

	// Thumbail
	if (empty($videoID)) {
		if ($layout !== 'slider' &&  ($sl_img1 || $sl_img2 || $sl_img3)) {
			wp_enqueue_script('dp-bxslider', DP_THEME_URI . '/inc/js/jquery/jquery.bxslider.min.js', array('jquery', 'imagesloaded'),DP_OPTION_SPT_VERSION,true);
			$uid = 'asl'.dp_rand();
			$next_prev = 'controls:false,';
			if (!$IS_MOBILE_DP) {
				$next_prev = "nextText:'<i class=\"icon-right-open\"></i>',prevText:'<i class=\"icon-left-open\"></i>',";
			}
			if ($sl_img1) $sl_img1 = '<li><img src="'.$sl_img1.'" alt="slide image" /></li>';
			if ($sl_img2) $sl_img2 = '<li><img src="'.$sl_img2.'" alt="slide image" /></li>';
			if ($sl_img3) $sl_img3 = '<li><img src="'.$sl_img3.'" alt="slide image" /></li>';
			$thumbnail_code = '<ul id="'.$uid.'" class="aslider">'.$sl_img1.$sl_img2.$sl_img3.'</ul>';
			$js_slider = "j$(function(){
				j$('#".$uid."').bxSlider({
					speed:1200,
					pause:3000,
					mode:'horizontal',
					".$next_prev."
					pager:false,
					auto:true,
					autoHover:true,
					onSliderLoad:function(){
						j$('#".$uid."').css('opacity',1);
					}
				});
			});";
			$js_slider = str_replace(array("\r\n","\r","\n","\t"), '', $js_slider);
		} else {
			$thumbnail_code = show_post_thumbnail(array("width"=>$width, "height"=>$height, "size"=>"large"));
		}
	} else {
		switch ($video_service) {
			case 'Vimeo':
				if ( WP_Filesystem() ) {
					global $wp_filesystem;
					$vimeo_hash = unserialize($wp_filesystem->get_contents("https://vimeo.com/api/v2/video/".$videoID.".php"));
					if (!$vimeo_hash) {
						$thumbnail_code = show_post_thumbnail(array("width"=>$width, "height"=>$height, "size"=>"large", "if_img_tag"=>true));
						// $thumb_type = 'eyecatch';
					} else {
						$thumbnail_code = '<img src="'.$vimeo_hash[0]['thumbnail_large'].'" class="wp-post-image" />';
					}
				}
				$embed_code = '<iframe src="//player.vimeo.com/video/'.$videoID.'?title=0&amp;byline=0&amp;portrait=0&amp;badge=0" frameborder="0" webkitallowfullscreen="" mozallowfullscreen="" allowfullscreen="" class="emb"></iframe>';
				break;
			case 'YouTube':
			default:
				$thumbnail_code = '<img src="//img.youtube.com/vi/'.$videoID.'/0.jpg" class="wp-post-image" />';
				$embed_code 	= '<iframe class="emb" src="//www.youtube.com/embed/'.$videoID.'/?wmode=transparent&hd=1&autohide=1&rel=0" frameborder="0" allowfullscreen></iframe>';
				break;
		}
	}

	// Return
	$arr_post_ele = array(
		'thumbnail_code'=> $thumbnail_code,
		'js_slider' => $js_slider,
		'embed_code'	=> $embed_code,
		'media_icon_code' 	=> $media_icon_code,
		'post_title'	=> $post_title,
		'post_label'	=> $post_label,
		'ranking_code'	=> $ranking_code,
		'desc'			=> $desc,
		'meta_code'		=> $meta_code,
		'sns_share_code'	=> $sns_share_code,
		'arr_meta'		=> $arr_meta
		);

	return $arr_post_ele;
}
/*******************************************************
* Normal style
*******************************************************/
function dp_show_post_list_for_archive_normal($args = array(), $arr_post_ele = array()) {
	if (empty($args) && empty($arr_post_ele)) return;
	global $options, $post, $IS_MOBILE_DP;

	// init
	$desc 	= '';
	$title_line= '';
	$date_code = '';
	$has_date_class = '';
	$meta_code = '';
	$views_code = '';
	$sns_share_code = '';
	$more_code = '';
	$js_code = '';
	$loop_code = '';

	extract($args);
	extract($arr_post_ele);	// Including $arr_meta

	$post_url	= get_permalink();
	$esc_title	= the_title_attribute('before=&after=&echo=0');
	$cat_class	= $arr_meta['cat_color_class'];
	$views_code = $arr_meta['views'];
	$more_code = '<div class="more-link"><a href="'.$post_url.'" title="'.$esc_title.'"><span class="r-wrap">'.$read_more_str.'</span></a></div>';
	$js_code = '<script>j$(function(){get_sns_share_count("'.$post_url.'", "post-'.get_the_ID().'");});'.$js_slider.'</script>';

	/**
	 * Article Source
	 */
	// Date + title
	if (!empty($arr_meta['date'])) {
		$date_code = '<div class="loop-date designed"><time datetime="'.get_the_date('c').'" class="updated">'.$arr_meta['day_double'].$arr_meta['month_en'].'</time></div>';
		$has_date_class = ' has_date';
	}
	// title
	$title_line = '<div class="title-line">'.$date_code.'<h1 class="entry-title loop-title '.$layout.$has_date_class.'"><a href="'.$post_url.'" rel="bookmark" title="'.$esc_title.'">'.$post_title.'</a></h1></div>';

	// meta
	if (!empty($arr_meta['author']) || !empty($views_code) || !empty($arr_meta['cats'])) {
		$meta_code = '<div class="loop-meta clearfix">'.$arr_meta['author'].$arr_meta['cats'].$views_code.'</div>';
	}
	// Thumbnail
	if (!empty($embed_code)) {
		$eyecatch_code = '<div class="loop-post-embed '.$layout.'">'.$embed_code.$post_label.'</div>';
	} else if (!empty($js_slider)) {
		$eyecatch_code = '<div class="loop-post-thumb slider '.$layout.'" data-url="'.$post_url.'"><div class="r-wrap">'.$thumbnail_code.'</div>'.$post_label.'</div>';
	} else {
		$eyecatch_code = '<div class="loop-post-thumb '.$layout.'"><a href="'.$post_url.'" rel="bookmark" title="'.$esc_title.'" class="thumb-link r-wrap"><span>'.$thumbnail_code.'</span>'.$media_icon_code.'</a>'.$post_label.'</div>';
	}
	// Whole
	$loop_code .= 
'<article id="post-'.get_the_ID().'" class="loop-article'.$col_class.$cat_class.$wow_article_css.'"><div class="loop-col one">'.$eyecatch_code.'</div><div class="loop-col two"><div class="loop-article-content">'.$title_line.$meta_code.$desc.$sns_share_code.$more_code.'</div></div>'.$js_code.'</article>';
	
	return $loop_code;
}

/*******************************************************
* Normal style for mobile
*******************************************************/
function dp_show_post_list_for_archive_normal_mb($args = array(), $arr_post_ele = array()) {
	if (empty($args) && empty($arr_post_ele)) return;
	global $options, $post;

	// init
	$desc 	= '';
	$title_line= '';
	$date_code = '';
	$has_date_class = '';
	$meta_code = '';
	$views_code = '';
	$sns_share_code = '';
	$more_code = '';
	$js_code = '';
	$loop_code = '';

	extract($args);
	extract($arr_post_ele);	// Including $arr_meta

	$post_url	= get_permalink();
	$esc_title	= the_title_attribute('before=&after=&echo=0');
	$cat_class	= $arr_meta['cat_color_class'];
	$views_code = $arr_meta['views'];
	$js_code = '<script>j$(function(){get_sns_share_count("'.$post_url.'", "post-'.get_the_ID().'");});'.$js_slider.'</script>';

	/**
	 * Article Source
	 */
	// Date + title
	if (!empty($arr_meta['date'])) {
		$date_code = '<div class="loop-date designed"><time datetime="'.get_the_date('c').'" class="updated">'.$arr_meta['day_double'].$arr_meta['month_en'].'</time></div>';
		$has_date_class = ' has_date';
	}
	// title
	$title_line = '<h1 class="entry-title loop-title '.$layout.$has_date_class.'"><a href="'.$post_url.'" rel="bookmark" title="'.$esc_title.'">'.$post_title.'</a></h1>';

	// meta
	if (!empty($arr_meta['author']) || !empty($views_code) ) {
		$meta_code = '<div class="loop-meta clearfix">'.$arr_meta['author'].$views_code.'</div>';
	}
	// Thumbnail
	if (!empty($embed_code)) {
		$eyecatch_code = '<div class="loop-post-embed '.$layout.'">'.$embed_code.$post_label.'</div>';
	} else if (!empty($js_slider)) {
		$eyecatch_code = '<div class="loop-post-thumb slider '.$layout.'" data-url="'.$post_url.'">'.$thumbnail_code.$post_label.'</div>';
	} else {
		$eyecatch_code = '<div class="loop-post-thumb '.$layout.'"><a href="'.$post_url.'" rel="bookmark" title="'.$esc_title.'" class="thumb-link">'.$thumbnail_code.'</a>'.$post_label.'</div>';
	}
	// Whole
	$loop_code .= 
'<article id="post-'.get_the_ID().'" class="loop-article'.$cat_class.$wow_article_css.'"><div class="loop-col one">'.$eyecatch_code.'</div><div class="loop-col two">'.$date_code.$arr_meta['cats'].$title_line.'</div><div class="loop-col three">'.$meta_code.$sns_share_code.'</div>'.$js_code.'</article>';
	
	return $loop_code;
}

/*******************************************************
* Blog style
*******************************************************/
function dp_show_post_list_for_archive_blog($args = array(), $arr_post_ele = array()) {
	if (empty($args) && empty($arr_post_ele)) return;
	global $options, $post, $IS_MOBILE_DP;

	// init
	$desc 			= '';
	$title_line		= '';
	$date_code = '';
	$meta_code 	= '';
	$has_date_class = '';
	$views_code 	= '';
	$sns_share_code = '';
	$more_code		= '';
	$js_code 		= '';
	$loop_code 		= '';

	extract($args);
	extract($arr_post_ele);	// Including $arr_meta

	$post_url	= get_permalink();
	$esc_title	= the_title_attribute('before=&after=&echo=0');
	$cat_class	= $arr_meta['cat_color_class'];
	$views_code = $arr_meta['views'];
	$more_code = '<div class="more-link"><a href="'.$post_url.'" title="'.$esc_title.'"><span class="r-wrap">'.$read_more_str.'</span></a></div>';
	$js_code = '<script>j$(function(){get_sns_share_count("'.$post_url.'", "post-'.get_the_ID().'");});'.$js_slider.'</script>';
	/**
	 * Article Source
	 */
	// date
	if (!empty($arr_meta['date'])) {
		$date_code = '<div class="loop-date designed"><time datetime="'.get_the_date('c').'" class="updated">'.$arr_meta['day_double'].$arr_meta['month_en'].'</time></div>';
		$has_date_class = ' has_date';
	}
	// title
	$title_line = '<div class="title-line">'.$date_code.'<h1 class="entry-title loop-title '.$layout.$has_date_class.'"><a href="'.$post_url.'" rel="bookmark" title="'.$esc_title.'">'.$post_title.'</a></h1></div>';
	// meta
	if (!empty($arr_meta['author']) || !empty($views_code) || !empty($arr_meta['cats'])) {
		$meta_code = '<div class="loop-meta clearfix">'.$arr_meta['author'].$arr_meta['cats'].$views_code.'</div>';
	}
	// Thumbnail
	if (!empty($embed_code)) {
		$eyecatch_code = '<div class="loop-post-embed '.$layout.'">'.$embed_code.$post_label.'</div>';
	} else if (!empty($js_slider)) {
		$eyecatch_code = '<div class="loop-post-thumb slider '.$layout.'" data-url="'.$post_url.'"><div class="r-wrap">'.$thumbnail_code.'</div>'.$post_label.'</div>';
	} else {
		$eyecatch_code = '<div class="loop-post-thumb '.$layout.'"><a href="'.$post_url.'" rel="bookmark" title="'.$esc_title.'" class="thumb-link r-wrap"><span>'.$thumbnail_code.'</span>'.$media_icon_code.'</a>'.$post_label.'</div>';
	}
	// Whole
	$loop_code .= 
'<article id="post-'.get_the_ID().'" class="loop-article'.$col_class.$cat_class.$wow_article_css.'">'.$eyecatch_code.'<div class="loop-article-content">'.$title_line.$meta_code.$desc.$more_code.$sns_share_code.'</div>'.$js_code.'</article>';
	
	return $loop_code;
}

/*******************************************************
* Magazine style part 1
*******************************************************/
function dp_show_post_list_for_archive_magazine1($args = array(), $arr_post_ele = array()) {
	if (empty($args) && empty($arr_post_ele)) return;
	global $options, $post, $IS_MOBILE_DP;
	// init
	$desc 			= '';
	$title_line		= '';
	$date_code = '';
	$meta_code 	= '';
	$has_date_class = '';
	$views_code 	= '';
	$sns_share_code = '';
	$more_code		= '';
	$js_code 		= '';
	$loop_code 		= '';

	extract($args);
	extract($arr_post_ele);	// Including $arr_meta

	$post_url	= get_permalink();
	$esc_title	= the_title_attribute('before=&after=&echo=0');
	$cat_class	= $arr_meta['cat_color_class'];
	$views_code = $arr_meta['views'];
	$more_code = '<div class="more-link"><a href="'.$post_url.'" title="'.$esc_title.'"><span class="r-wrap">'.$read_more_str.'</span></a></div>';
	$js_code = '<script>j$(function(){get_sns_share_count("'.$post_url.'", "post-'.get_the_ID().'");});'.$js_slider.'</script>';
	/**
	 * Article Source
	 */
	// date
	if (!empty($arr_meta['date'])) {
		$date_code = '<div class="loop-date designed"><time datetime="'.get_the_date('c').'" class="updated">'.$arr_meta['day_double'].$arr_meta['month_en'].'</time></div>';
		$has_date_class = ' has_date';
	}
	// title
	$title_line = '<div class="title-line">'.$date_code.'<h1 class="entry-title loop-title '.$layout.$has_date_class.'"><a href="'.$post_url.'" rel="bookmark" title="'.$esc_title.'">'.$post_title.'</a></h1></div>';
	// meta
	if (!empty($arr_meta['author']) || !empty($views_code) || !empty($arr_meta['cats'])) {
		$meta_code = '<div class="loop-meta clearfix">'.$arr_meta['author'].$arr_meta['cats'].$views_code.'</div>';
	}
	// Thumbnail
	if (!empty($embed_code)) {
		$eyecatch_code = '<div class="loop-post-embed '.$layout.'">'.$embed_code.$post_label.'</div>';
	} else if (!empty($js_slider)) {
		$eyecatch_code = '<div class="loop-post-thumb slider '.$layout.'" data-url="'.$post_url.'"><div class="r-wrap">'.$thumbnail_code.'</div>'.$post_label.'</div>';
	} else {
		$eyecatch_code = '<div class="loop-post-thumb '.$layout.'"><a href="'.$post_url.'" rel="bookmark" title="'.$esc_title.'" class="thumb-link r-wrap"><span>'.$thumbnail_code.'</span>'.$media_icon_code.'</a>'.$post_label.'</div>';
	}
	//Whole
	$loop_code .= 
'<article id="post-'.get_the_ID().'" class="loop-article'.$col_class.$masonry_lines.$cat_class.$wow_article_css.'">'.$eyecatch_code.'<div class="loop-article-content">'.$title_line.'<div class="meta-wrapper">'.$meta_code.$desc.$more_code.$sns_share_code.'</div></div>'.$js_code.'</article>';
	
	return $loop_code;
}

/*******************************************************
* Magazine style part 2
*******************************************************/
function dp_show_post_list_for_archive_magazine_mb($args = array(), $arr_post_ele = array()) {
	if (empty($args) && empty($arr_post_ele)) return;
	global $options, $post;

	// init
	$desc 	= '';
	$title_line= '';
	$date_code = '';
	$big_class = '';
	$meta_code = '';
	$views_code = '';
	$sns_share_code = '';
	$more_code = '';
	$js_code = '';
	$loop_code = '';

	extract($args);
	extract($arr_post_ele);	// Including $arr_meta

	$post_url	= get_permalink();
	$esc_title	= the_title_attribute('before=&after=&echo=0');
	$cat_class	= $arr_meta['cat_color_class'];
	$views_code = $arr_meta['views'];
	$more_code = '<div class="more-link"><a href="'.$post_url.'" title="'.$esc_title.'"><span class="r-wrap">'.$read_more_str.'</span></a></div>';
	$js_code = '<script>j$(function(){get_sns_share_count("'.$post_url.'", "post-'.get_the_ID().'");});'.$js_slider.'</script>';

	/**
	 * Article Source
	 */
	// Date
	if (!empty($arr_meta['date'])) {
		$date_code = '<div class="loop-date designed"><time datetime="'.get_the_date('c').'" class="updated">'.$arr_meta['day_double'].$arr_meta['month_en'].'</time></div>';
		$has_date_class = ' has_date';
	}
	// title
	$title_line = '<div class="title-line">'.$date_code.'<h1 class="entry-title loop-title '.$layout.$has_date_class.'"><a href="'.$post_url.'" rel="bookmark" title="'.$esc_title.'">'.$post_title.'</a></h1></div>';
	// meta
	if (!empty($arr_meta['author']) || !empty($views_code) || !empty($arr_meta['cats'])) {
		$meta_code = '<div class="loop-meta clearfix">'.$arr_meta['author'].$views_code.'</div>';
	}
	// Big article
	if ((bool)$is_big) {
		$big_class = ' big';
		$meta_code = '<div class="meta-wrap">'.$arr_meta['cats'].$desc.$meta_code.$sns_share_code.$more_code.'</div>';
		$meta_code = '<div class="loop-col two"><div class="loop-article-content">'.$title_line.$meta_code.'</div></div>';
	} else {
		$big_class = ' small';
		$meta_code = '<div class="loop-col two"><div class="loop-article-content">'.$title_line.$arr_meta['cats'].'</div></div><div class="loop-col three">'.$meta_code.$sns_share_code.'</div>';
	}
	// Thumbnail
	if (!empty($embed_code)) {
		$eyecatch_code = '<div class="loop-post-embed '.$layout.'">'.$embed_code.$post_label.'</div>';
	} else if (!empty($js_slider)) {
		$eyecatch_code = '<div class="loop-post-thumb slider '.$layout.'" data-url="'.$post_url.'"><div class="r-wrap">'.$thumbnail_code.'</div>'.$post_label.'</div>';
	} else {
		$eyecatch_code = '<div class="loop-post-thumb '.$layout.'"><a href="'.$post_url.'" rel="bookmark" title="'.$esc_title.'" class="thumb-link r-wrap"><span>'.$thumbnail_code.'</span></a>'.$post_label.'</div>';
	}
	// Whole
	$loop_code .= 
'<article id="post-'.get_the_ID().'" class="loop-article'.$col_class.$big_class.$masonry_lines.$cat_class.$wow_article_css.'"><div class="loop-col one">'.$eyecatch_code.'</div>'.$meta_code.$js_code.'</article>';
	
	return $loop_code;
}

/*******************************************************
* Slider style
*******************************************************/
function dp_show_post_list_for_archive_slider($args = array(), $arr_post_ele = array()) {
	if (empty($args) && empty($arr_post_ele)) return;
	global $options, $post, $IS_MOBILE_DP;

	// init
	$overlay_color = '';
	$eyecatch_code = '';
	$post_label = '';
	$views_code = '';
	$sns_share_code = '';
	$loop_code = '';

	extract($args);
	extract($arr_post_ele);	// Including $arr_meta

	$post_url = get_permalink();
	$esc_title	= the_title_attribute('before=&after=&echo=0');
	$no_cat_flag = empty($arr_meta['cats']) ? ' no-cat' : '';
	$js_code 	= '<script>j$(function(){get_sns_share_count("'.$post_url.'", "post-'.get_the_ID().'");});'.$js_slider.'</script>';
	$h1_fix_class = '';

	// overlay color is category color
	if ($overlay_color === 'cat_color') {
		$overlay_color = $arr_meta['cat_color_class'];
	}

	// Thumbnail
	$eyecatch_code = '<a href="'.$post_url.'" rel="bookmark" class="thumb-link r-wrap"><span>'.$thumbnail_code.'</span>'.$media_icon_code.'</a>';
	
	// For fix centering class
	if (!empty($arr_meta['author'])) {
		$h1_fix_class = ' fix-v';
	}
	/**
	 * Article Source
	 */
	$loop_code .= 
'<li id="post-'.get_the_ID().'" class="loop-article'.$col_class.$no_cat_flag.$overlay_color.'"><div class="loop-post-thumb '.$layout.$overlay_color.'">'.$eyecatch_code.'<div class="loop-article-content">'.$arr_meta['cats'].'<div class="loop-table"><div class="loop-header">'.$arr_meta['date'].'<h1 class="entry-title loop-title '.$layout.$h1_fix_class.'"><a href="'.$post_url.'" rel="bookmark" title="'.$esc_title.'">'.$post_title.'</a></h1></div></div>'.$arr_meta['author'].$sns_share_code.$post_label.$arr_meta['views'].'</div></div>'.$js_code.'</li>';

	return $loop_code;
}
/*******************************************************
* News style
*******************************************************/
function dp_show_post_list_for_archive_news($args = array(), $arr_post_ele = array()) {
	if (empty($args) && empty($arr_post_ele)) return;
	global $options, $post, $IS_MOBILE_DP;
	// init
	$loop_code = '';
	extract($args);
	extract($arr_post_ele);	// Including $arr_meta
	$post_url	= get_permalink();
	$esc_title	= the_title_attribute('before=&after=&echo=0');
	/**
	 * Article Source
	 */
	$loop_code .= 
'<article id="post-'.get_the_ID().'" class="loop-article'.$wow_article_css.'">'.$arr_meta['date'].'<h1 class="entry-title loop-title '.$layout.'"><a href="'.$post_url.'" rel="bookmark" title="'.$esc_title.'">'.$post_title.'</a></h1>'.$arr_meta['author'].'</article>';

	return $loop_code;
}