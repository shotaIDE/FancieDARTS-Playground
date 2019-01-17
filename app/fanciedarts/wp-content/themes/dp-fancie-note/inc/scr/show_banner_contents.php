<?php
/** ===================================================
* Echo slider Javascript
*
* @return strings
*/
function make_slider_js($slider_id = '#hd_slideshow') {
	if ( !(is_home() && !is_paged()) ) return;
	
	global $options_visual, $IS_MOBILE_DP;

	$js_code = "";
	$suffix_class = "";
	$random_start = '';
	if ($IS_MOBILE_DP && is_front_page() && !is_paged()) {
		$suffix_class = '_mobile';
	}
	if (is_archive() || is_search()) {
		$suffix_class = '_archive';
	}

	$type = $options_visual['dp_header_content_type'.$suffix_class];
	$target = $options_visual['dp_slideshow_target'.$suffix_class];

	if ($type !== "2") return;

	// Javascript
	$transition_time 	= (int)$options_visual['dp_slideshow_transition_time'.$suffix_class];
	$interval 	= (int)$options_visual['dp_slideshow_speed'.$suffix_class] + $transition_time;
	$mode = $options_visual['dp_slideshow_effect'.$suffix_class];
	$mode = 'mode:"'.$mode.'",';
	if ($options_visual['dp_slideshow_order'] === 'rand') {
		$random_start = 'randomStart:true,';
	}
	// Thumbnail pager for post slider
	$thumb_pager_selector = "#hd_slider_thumb_pager";
	$page = '';
	$pager_custom = '';
	$thumb_pager_code = '';
	if ($target === 'post' || $target === 'page'){
		$mode = 'mode:"horizontal",moveSlides:1,slideMargin:1,autoReload:true,breaks:[{screen:0, slides:1},{screen:568,slides:2},{screen:880,slides:3},{screen:1025,slides:4}],';
		// $pager_custom = "pagerCustom:'".$thumb_pager_selector."',";
		// $next_prev = 'controls:false,';
// 		$thumb_pager_code = 
// "thumb_slider=j$('".$thumb_pager_selector."').bxSlider({
// 	pager:false,
// 	minSlides:3,
// 	maxSlides:5,
// 	slideWidth:90,
// 	slideMargin:1, 
// 	nextSelector:'#hd_sl_nav_next',
// 	prevSelector:'#hd_sl_nav_prev'
// });";
	}
	$next_prev = empty($options_visual['dp_slideshow_nav_button']) || (bool)$IS_MOBILE_DP ? 'controls:false,' : "nextText:'<i class=\"icon-right-open\"></i>',prevText:'<i class=\"icon-left-open\"></i>',";
	$pager = empty($options_visual['dp_slideshow_control_button']) || (bool)$IS_MOBILE_DP ? 'pager:false,' : '';

	// Callbacks
	$on_load_fn = '';
	$on_before_fn = '';
	if ((bool)$IS_MOBILE_DP) {
		$on_load_fn = 
",onSliderLoad:function(){
	j$('".$slider_id."').css('opacity',1);
}";
	}

	// Js
	$js_code = 
"<script>
var hd_slider,sl_timer;
j$(function(){
	hd_slider=j$('".$slider_id."').bxSlider({
		".$mode."
		speed:".$transition_time.",
		pause:".$interval.","
		.$random_start.$pager.$pager_custom.$next_prev.
		"video:true,
		auto:true,
		autoHover:false,
		adaptiveHeight:true".$on_load_fn.$on_before_fn."
	});".$thumb_pager_code."
});</script>";

	$js_code = str_replace(array("\r\n","\r","\n","\t"), '', $js_code);
	return $js_code;
}

/** ===================================================
* Create slideshow source
*
*/
function dp_slideshow_source( $params = array(
								'width' => 1680, 
								'height' => 1200,
								'navigation_class'=> 'hd-slide-nav',
								'control_class' => 'hd-slide-control' )) {

	global $options, $options_visual, $IS_MOBILE_DP;

	extract($params);
	$i = 0;
	$arr_http = array('http:','https:');
	$slider_id = 'hd_slideshow';
	$suffix_class = (bool)$IS_MOBILE_DP && is_front_page() && !is_paged() ? '_mobile' : '';

	if (is_archive() || is_search()) {
		$suffix_class = '_archive';
		$target = 'header_img';
	} else {
		$target = $options_visual['dp_slideshow_target'.$suffix_class];
		$orderby = $options_visual['dp_slideshow_order'.$suffix_class];
	}
	$num = $options_visual['dp_number_of_slideshow'.$suffix_class];
	$mode = $options_visual['dp_slideshow_effect'.$suffix_class];
	$upload_dir = 'header';

	// Thumbnail size
	if ((bool)$IS_MOBILE_DP) {
		$width 	= 818;
		$height = 680;
		$upload_dir = 'header/mobile';
		$slider_id = $slider_id.'_mb';
	}

	$slideshow_code = '';
	$thumbnav_code = '';

	switch ($target) {
		case 'header_img':	// Slideshow with Heade image
			$arrImages = array();
			// Get images
			$images = dp_get_uploaded_images($upload_dir);
			$images = str_replace($arr_http,'',$images[0]);
			$cnt = count($images);

			// Move image to the array
			if (0 < $cnt && $cnt <= $num) {
				$arrImages = $images;
			} else if ($cnt > $num) {
				for ($i=0; $i < $num; ++$i) {
					if (!empty($images[$i])) {
						array_push($arrImages, $images[$i]);
					}
				}
			}
			// Loop each images
			foreach ($arrImages as $image) {
				// Slideshow code
				$slideshow_code .= '<li class="slide"><img src="'.$image.'" class="sl-img" alt="slide image" /></li>';
			}
			break;

		case 'post':	// Slideshow with articles
		case 'page':
			$mode = 'carousel';
			// meta settings
			$show_meta_flg = $options_visual['dp_slideshow_post_show_meta_always'] ? ' always' : ' onhover';

			global $post;
			// Query
			$posts = get_posts( array(
									'numberposts'	=> $num,
									'post_type'=> $target,
									'meta_key'=> 'is_slideshow',
									'meta_value'	=> array("true", true),
									'orderby'=> $orderby // or rand
									)
			);

			// Loop query posts
			foreach( $posts as $post ) : setup_postdata($post);
				// Reset
				$lp_class= '';
				$slide_image 	= '';
				$date_code = '';
				$meta_code = '';
				$cats 	= '';
				$cats_code = '';
				$cat_class = '';
				$p_id 	= get_the_ID();
				$post_url = get_permalink();
				$title 	= get_the_title();
				$title 	= (mb_strlen($title, 'utf-8') > 30) ? mb_substr($title, 0, 30, 'utf-8') . '...' : $title;
				// Video ID(YouTube only)
				$videoID= get_post_meta($p_id, 'item_video_id', true);
				// Target video service
				$video_service	= get_post_meta($p_id, 'video_service', true);
					
				// Date
				if (!(bool)get_post_meta($p_id, 'dp_hide_date', true) && ($target === 'post' && (bool)$options['show_pubdate_on_meta'])) {
					if (isset($options['date_eng_mode']) && (bool)$options['date_eng_mode']) {
						$date_code 	= '<div class="sl-date'.$show_meta_flg.'"><span class="date_day">'.get_post_time('j').'</span> <span class="date_month_en">'.get_post_time('F').'</span>, <span class="date_year">'.get_post_time('Y').'</span></div>';
					} else {
						$date_code = '<div class="sl-date'.$show_meta_flg.'">'.get_the_date().'</div>';
					}
				}

				// Category
				if ( !(bool)get_post_meta($p_id, 'dp_hide_cat', true) ) {
					$cats = get_the_category();
					if ($cats) {
						$cats = $cats[0];
						$cat_class = " cat-color".$cats->cat_ID;
						$cats_code = '<div class="sl-cat'.$show_meta_flg.'">' .$cats->cat_name.'</div>';
					}
				}

				// Get post image
				$slide_image 	= get_post_meta($p_id, 'slideshow_image_url', true);
				$slide_image = str_replace($arr_http,'',$slide_image);

				if (empty($slide_image)) {
					if( has_post_thumbnail() ) {
						$image_id 	= get_post_thumbnail_id();
						$image_data = wp_get_attachment_image_src($image_id, array($width, $height), true); 
						$image_url 	= str_replace($arr_http,'',$image_data[0]);
						$slide_image 	= $image_url;
						
					} else {
						preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"]/i', get_post($p_id)->post_content, $got_image);
						if ( $got_image[1][0] ) {
							// Add image
							$slide_image = str_replace($arr_http,'',$got_image[1][0]);
							
						} else {
							$strPattern	=	'/(\.gif|\.jpg|\.jpeg|\.png)$/';
							
							if ($handle = opendir(DP_THEME_DIR . '/img/slideshow')) {
								$def_image;
								$cnt = 0;
								while (false !== ($file = readdir($handle))) {
									if ($file != "." && $file != "..") {
										//Image file only
										if (preg_match($strPattern, $file)) {
											$def_image[$cnt] = DP_THEME_URI . '/img/slideshow/'.$file;
											//count
											$cnt ++;
										}
									}
								}
								closedir($handle);
							}
							if ($cnt > 0) {
								$randInt = rand(0, $cnt - 1);
								// Add image
								$slide_image = str_replace($arr_http,'',$def_image[$randInt]);
							}
						}
					}
				}

				// Slideshow code
				$slideshow_code .= '<li class="slide"><div class="r-wrap"><a href="'.$post_url.'" class="sl-img-anchor" title="'.$title.'"><img src="'.$slide_image.'" class="sl-img" alt="eyecatch" /><div class="sl-meta'.$show_meta_flg.'">'.$cats_code.$date_code.'<div class="sl-title'.$show_meta_flg.'">'.$title.'</div></div></a></div></li>';
				$i++;
			endforeach;
			// Reset Query
			wp_reset_postdata();
			break;
	}
	// Close ul
	$slideshow_code = '<ul id="'.$slider_id.'" class="'.$slider_id.' loop-slider '.$mode.'">'.$slideshow_code.'</ul>';
	// Display
	return array('slideshow_code' => $slideshow_code,
				 'thumbnav_code' => $thumbnav_code);
}


/** ===================================================
* Show the Banner Contents
*
* @return	none
*/
function dp_banner_contents() {
	if ( !(is_home() && !is_paged()) ) return;

	global $options,$options_visual,$IS_MOBILE_DP;

	$has_widget_class 	= '';
	$prefix_code = '';
	$banner_contents 	= '';
	$thumbnav_code = '';
	$title_position_class = 'pos-c';
	$header_title_code 	= '';
	$header_id 	= '';
	$header_class = '';
	$upload_dir = 'header';
	$single_img = '';
	$float_css = '';
	$suffix_mb = '';

	$suffix_class = (bool)$IS_MOBILE_DP && is_front_page() && !is_paged() ? '_mobile' : '';
	if (is_archive() || is_search()) {
		$suffix_class = '_archive';
	}

	//Get options
	$type= $options_visual['dp_header_content_type'.$suffix_class];
	$header_img = $options_visual['dp_header_img'.$suffix_class];
	$target = $options_visual['dp_slideshow_target'.$suffix_class];

	if ($type === 'none') return;

	// For Mobile
	if ($IS_MOBILE_DP) {
		$suffix_mb = '_mb';
		$upload_dir = 'header/mobile';
	}
	// Fixed class
	if ($options['fixed_header_bar'.$suffix_mb]) {
		$float_css = ' float';
	}

	// Has widget?
	if (is_active_sidebar('widget-on-top-banner') && !$IS_MOBILE_DP ) {
		$has_widget_class = ' has_widget';
	} else {
		$has_widget_class = ' no_widget';
	}

	// Top page
	switch ($type) {
		case 1:	// Header image
			if ($header_img === 'random') {
				// Get images
				$images = dp_get_uploaded_images($upload_dir);
				$images = $images[0];
				$cnt = count($images);

				if ($cnt > 0) {
					//show image
					$rnd = rand(0, $cnt - 1);
					$banner_contents = '<img src="'.$images[$rnd].'" class="static_img" />';
				} else {
					$banner_contents = '<img src="'.DP_THEME_URI.'/img/sample/header/header1.jpg" class="static_img" />';
				}
			} else {
				if ($header_img === 'none' || !$options_visual['dp_header_img'.$suffix_class]) {
					$banner_contents = '<img src="'.DP_THEME_URI.'/img/sample/header/header1.jpg" class="static_img" />';
				} else {
					$banner_contents = '<img src="'.$options_visual['dp_header_img'.$suffix_class].'" class="static_img" />';
				}
			}
			$header_class = ' hd_img';
			break;

		case 2:	// Slideshow
			// bxSlider
			wp_enqueue_script('dp-bxslider', DP_THEME_URI . '/inc/js/jquery/jquery.bxslider.min.js', array('jquery','fitvids','imagesloaded'),false,true);
			// Get slideshow source
			$slideshow = dp_slideshow_source();
			$banner_contents = $slideshow['slideshow_code'];
			// Class
			$header_class = ' slideshow';
			if ($target === 'post' || $target === 'page') {
				$header_class .= ' post-slider';
				$thumbnav_code = $slideshow['thumbnav_code'];
			}
			break;

		case 3:	// Fullscreen bg movie
			$banner_contents = '';
			break;

		case 'single':
			$banner_contents = $single_img;
			break;
	}

	// **********************************
	// Prefix Code
	// **********************************
	$prefix_code = '<section id="header-banner-outer" class="header-banner-outer'.$float_css.'"><div id="header-banner-inner" class="header-banner-inner'.$header_class.$has_widget_class.'">';
	// **********************************
	// Display 
	// ********************************** 
	echo $prefix_code.$banner_contents;

	// Title & caption
	if (is_front_page() && !is_paged()) {
		if ( $type === "1" || $type === "3" || ($type === "2" && ($target !== 'post' && $target !== 'page' ) ) ) {
			/* ******************
			 * if title shows on header
			 * *****************/
			$wow_title_css = '';
			$wow_desc_css = '';
			$wow_widget = '';
			$wow_delay_1 = '';
			$wow_delay_2 = '';
			$wow_delay_3 = '';
			if (!(bool)$options['disable_wow_js'.$suffix_mb]) {
				$wow_title_css = ' class="wow fadeInDown"';
				$wow_desc_css = ' class="wow fadeInUp"';
				$wow_widget = ' wow fadeIn';
				if ($IS_MOBILE_DP) {
					$wow_delay_1 = ' data-wow-delay="0.4s"';
					$wow_delay_2 = ' data-wow-delay="0.7s"';
					$wow_delay_3 = ' data-wow-delay="1.0s"';
				} else {
					$wow_delay_1 = ' data-wow-delay="2.0s"';
					$wow_delay_2 = ' data-wow-delay="2.3s"';
					$wow_delay_3 = ' data-wow-delay="2.6s"';
				}
			}
			
			// Title
			if (!empty($options['header_img_h2'])) {
				$header_title_code = '<h2 id="banner_title"'.$wow_title_css.$wow_delay_1.'>'.htmlspecialchars_decode($options['header_img_h2']).'</h2>';
			}
			// H3 title
			if (!empty($options['header_img_h3'])) {
				$header_title_code .= '<div id="banner_caption"'.$wow_desc_css.$wow_delay_2.'><span>'.htmlspecialchars_decode($options['header_img_h3']).'</span></div>';
			}
			if (!empty($header_title_code)) {
				$header_title_code = '<header class="'.$title_position_class.'">'.$header_title_code.'</header>';
			}

			// *********** Display header ************
			$header_title_code = '<div id="header-banner-container" class="header-banner-container'.$float_css.'"><div class="header-banner-content clearfix">' . $header_title_code;

			// **********************************
			// Display title
			// **********************************
			echo $header_title_code;

			// **********************************
			// Header widget
			// **********************************
			if (is_active_sidebar('widget-on-top-banner') && !$IS_MOBILE_DP ) {
				echo '<div class="widget-on-top-banner'.$wow_widget.'"'.$wow_delay_3.'>';
				dynamic_sidebar( 'widget-on-top-banner' );
				echo '</div>';
			}
			echo '</div></div>';	//  Close ".header-banner-container" and ".header-banner-content"
		}
	}

	echo '</div>'.$thumbnav_code.'</section>';	//  Close ".header-banner-inner" and ".header-banner-outer"
}
/**
* Show the Banner Contents for Mobile theme
*
* @return strings
*/
function dp_banner_contents_amp() {
	global $options,$options_visual;
	//Get options
	$type	= $options_visual['dp_header_content_type_mobile'];
	$copy_size = array();
	$banner_contents 	= '';

	// Top page
	switch ($type) {
		case 1:	// Header image
			$hd_img_class = "header_img";

			if ($options_visual['dp_header_img_mobile'] === 'random') {
				$images = dp_get_uploaded_images("header/".DP_MOBILE_THEME_DIR);
				$images = $images[0];
				$cnt = count($images);
				if ($cnt > 0) {
					$rnd = rand(0, $cnt - 1);
					$size = dp_get_image_size($images[$rnd]);
					if (is_array($size)) {
						$banner_contents = '<amp-img src="'.$images[$rnd].'" class="'.$hd_img_class.'" width="'.$size[0].'" height="'.$size[1].'" layout="responsive" alt="Header Image"></amp-img>';
					}
				} else {
					$size = dp_get_image_size(DP_THEME_URI.'/img/header/header1.jpg');
					if (is_array($size)) {
						$banner_contents = '<amp-img src="'.DP_THEME_URI.'/img/header/header1.jpg" class="'.$hd_img_class.'" width="'.$size[0].'" height="'.$size[1].'" layout="responsive" alt="Header Image"></amp-img>';
					}
				}
			} else {
				if ($options_visual['dp_header_img_mobile'] === 'none' || !$options_visual['dp_header_img_mobile']) {
					$size = dp_get_image_size(DP_THEME_URI.'/img/header/header1.jpg');
					if (is_array($size)) {
						$banner_contents = '<amp-img src="'.DP_THEME_URI.'/img/header/header1.jpg" class="'.$hd_img_class.'" width="'.$size[0].'" height="'.$size[1].'" layout="responsive" alt="Header Image"></amp-img>';
					}
				} else {
					$size = dp_get_image_size($options_visual['dp_header_img_mobile']);
					if (is_array($size)) {
						$banner_contents = '<amp-img src="'.$options_visual['dp_header_img_mobile'].'" class="'.$hd_img_class.'" width="'.$size[0].'" height="'.$size[1].'" layout="responsive" alt="Header Image"></amp-img>';
					}
				}
			}
			break;

		case 2:	// Slideshow
			$slideshow_code = '';
			$arrImages = array();
			$arr_http = array('http:','https:');
			$num = $options_visual['dp_number_of_slideshow_mobile'];
			$orderby = $options_visual['dp_slideshow_order_mobile'];

			// Target
			$target = $options_visual['dp_slideshow_target_mobile'];
			switch ($target) {
				case 'header_img':
					// Get images
					$images = dp_get_uploaded_images("header/".DP_MOBILE_THEME_DIR);
					$images = $images[0];
					$cnt = count($images);
					if (0 < $cnt && $cnt <= $num) {
						$arrImages = $images;
					} else if ($cnt > $num) {
						for ($i=0; $i < 7; ++$i) {
							array_push($arrImages, $images[$i]);
						}
					}
					// Loop each images
					foreach ($arrImages as $value) {
						$size = dp_get_image_size($value);
						if (is_array($size)) {
							$copy_size = $size;
							$slideshow_code .= '<amp-img src="'.$value.'" width="'.$size[0].'" height="'.$size[1].'" layout="responsive" alt="Slide Image" class="sl-img"></amp-img>';
						}
						$size = null;
					}
					break;

				case 'post':
				case 'page':
					global $post;
					$i = 0;
					$width 	= 980;
					$height = 680;
					$sl_posts = get_posts( array(
									'numberposts' => $num,
									'post_type' => $target,
									'meta_key' => 'is_slideshow',
									'meta_value' => array("true", true),
									'orderby' => $orderby // or rand
									)
					);
					// Loop query posts
					foreach( $sl_posts as $post ) : setup_postdata($post);
						$i ++;

						// Reset
						$slide_image 	= '';
						$date_code 	= '';
						$cats = '';
						$cats_code = '';
						$cat_class = '';
						$post_url = get_permalink();
						$title = get_the_title();
						$title = (mb_strlen($title, 'utf-8') > 36) ? mb_substr($title, 0, 36, 'utf-8') . '...' : $title;

						// Date
						if (!(bool)get_post_meta(get_the_ID(), 'dp_hide_date', true) && ($target === 'post' && (bool)$options['show_pubdate_on_meta']) ) {
							$year = '<span class="date_year">'.get_post_time('Y').'</span>';
							$month_en = '<span class="date_month_en">'.get_post_time('M').'</span>';
							$day = '<span class="date_day">'.get_post_time('j').'</span>';
							$date_code = '<div class="post-date"><time datetime="'.get_the_date('c').'" class="updated">'.$day.' '.$month_en.', '.$year.'</time></div>';
						}

						// Get post image
						$slide_image 	= get_post_meta(get_the_ID(), 'slideshow_image_url', true);

						if (!empty($slide_image)) {
							// If HTTPS
							$slide_image 	= str_replace($arr_http,'',$slide_image);
						} else {
							if( has_post_thumbnail() ) {
								$thumb_img_id = get_post_thumbnail_id();
								$thumb_img_url = wp_get_attachment_image_src($thumb_img_id, array($width, $height), true); 
								// Add image
								$slide_image = $thumb_img_url[0];
							} else {
								preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"]/i', get_post(get_the_ID())->post_content, $got_image);
								if ( isset($got_image[1][0]) && !empty($got_image[1][0]) ) {
									// Add image
									$slide_image = str_replace($arr_http,'',$got_image[1][0]);
								} else {
									$strPattern	=	'/(\.gif|\.jpg|\.jpeg|\.png)$/';
									if ($handle = opendir(DP_THEME_DIR . '/img/sample/header')) {
										$def_image;
										$cnt = 0;
										while (false !== ($file = readdir($handle))) {
											if ($file != "." && $file != "..") {
												//Image file only
												if (preg_match($strPattern, $file)) {
													$def_image[$cnt] = DP_THEME_URI . '/img/sample/header/'.$file;
													//count
													$cnt ++;
												}
											}
										}
										closedir($handle);
									}
									if ($cnt > 0) {
										$randInt = rand(0, $cnt - 1);
										// Add image
										$slide_image = str_replace($arr_http,'',$def_image[$randInt]);
									}
								}
							}
						}
						// Category 
						if ( !(bool)get_post_meta(get_the_ID(), 'dp_hide_cat', true) ) {
							$cats = get_the_category();
							if ($cats) {
								$cats = $cats[0];
								$cat_class = " cat-color".$cats->cat_ID;
								$cats_code = '<div class="meta-cat"><a href="'.get_category_link($cats->cat_ID).'" rel="tag" class="'.$cat_class.'">' .$cats->cat_name.'</a></div>';
							}
						}
						// get image
						$size = dp_get_image_size($slide_image);
						if (is_array($size)) {
							$copy_size = $size;
							// Slideshow code
							$slideshow_code .= '<div class="c-wrapper"><a href="'.$post_url.'" title="'.$title.'" class="img-link"><amp-img src="'.$slide_image.'" class="sl-img" width="'.$size[0].'" height="'.$size[1].'" layout="responsive" alt="eyecatch"></amp-img></a><div class="sl-meta">'.$cats_code.$date_code.'<h2 class="sl-title"><a href="'.$post_url.'" class="title-link" title="'.$title.'">'.$title.'</a></h2></div></div>';
						}
						$size = null;
					endforeach;
					// Reset Query
					wp_reset_postdata();
					break;
			}
			// Slideshow source
			$banner_contents = '<amp-carousel width="'.$copy_size[0].'" height="'.$copy_size[1].'" delay="'.$options_visual['dp_slideshow_speed_mobile'].'" type="slides" autoplay loop layout="responsive">'.$slideshow_code.'</amp-carousel>';
			break;
	}

	// Display
	return $banner_contents;
}