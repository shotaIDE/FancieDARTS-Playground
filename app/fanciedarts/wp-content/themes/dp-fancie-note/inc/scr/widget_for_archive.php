<?php
/**
 * Widgets for archive (post listing)
 * 
 */
/*******************************************************
* Get posts by get_posts Query
*******************************************************/
function DP_LISTING_POST_EACH_STYLES ($args = array(
									'echo'=> true,
									'number' 	=> 5,
									'show_cat'	=> true,
									'views'=> false,
									'author'	=> true,
									'comment'	=> false,
									'orderby'	=> 'date',
									'order'=> 'DESC',
									'hatebu_num'	=> false,
									'tweets_num'	=> false,
									'likes_num'	=> false,
									'masonry_lines' => ' three_lines',
									'cat_id'	=> '',
									'authors_id'=> '',
									'tag_slug' => '',
									'keyword' => '',
									'meta_key'	=> '',
									'meta_value' => '',
									'post_type'	=> 'post',
									'year'=> '',
									'month'=> '',
									'pub_date'	=> false,
									'type'=> '',
									'more_text'	=> 'More',
									'read_more_str' => 'Read More',
									'voted_icon' => '',
									'voted_count' => false,
									'layout'	=> 'normal',
									'excerpt_length' => 120,
									'one_col'	=> false,
									'overlay_color'=> 'default',
									'slider_mode' => 'horizontal',
									'slider_speed' => 500,
									'slider_duration' => 3000,
									'slider_navigation' => false,
									'slider_control_btn' => true
									)
) {
	// Import require functions
	require_once(DP_THEME_DIR . "/inc/scr/listing_post_styles.php");

	global $options, $post, $COLUMN_NUM, $IS_MOBILE_DP, $IS_AMP_DP;
	extract($args);

	// Params
	$number = isset($number) && !is_null($number) ? $number : 5;
	$show_cat = isset($show_cat) ? true : false;
	$views 	= isset($views) ? true : false;
	$author = isset($author) ? true : false;
	$comment = isset($comment) ? true : false;
	$orderby = isset($orderby) ? $orderby : 'date';
	$order	= isset($order) ? $order : 'DESC';
	$hatebu_num = isset($hatebu_num) ? true : false;
	$tweets_num = isset($tweets_num) ? true : false;
	$likes_num = isset($likes_num) ? true : false;
	$cat_id = isset($cat_id) && !empty($cat_id) ? str_replace(array("\"","'"), "", $cat_id) : '';
	$meta_key= isset($meta_key) && !empty($meta_key) ? $meta_key : '';
	$meta_value= isset($meta_value) && !empty($meta_value) ? $meta_value : '';
	$post_type = isset($post_type) && !empty($post_type) ? $post_type : 'post';
	$year= isset($year) && !empty($year) ? (int)$year : null;
	$month= isset($month) && !empty($month) ? (int)$month : null;
	$type = isset($type) && !empty($type) ? $type : '';
	$more_text = isset($more_text) && !empty($more_text) ? $more_text : '';
	$voted_count = isset($voted_count) ? true : false;
	$one_cal = isset($one_cal) ? true : false;
	$layout= isset($layout) && !empty($layout) ? $layout : 'normal';
	$slider_mode = isset($slider_mode) && !empty($slider_mode) ? $slider_mode : 'horizontal';
	$slider_speed = isset($slider_speed) && preg_match("/^[0-9]+$/",$slider_speed) ? $slider_speed : 500;
	$slider_duration = isset($slider_duration) && preg_match("/^[0-9]+$/",$slider_duration) ? $slider_duration : 3000;
	$slider_navigation = isset($slider_navigation) ? true : false;
	$slider_control_btn = isset($slider_control_btn)? true : false;
	$excerpt_length = isset($excerpt_length) && preg_match("/^[0-9]+$/",$excerpt_length) ? $excerpt_length : 120;
	$masonry_lines= isset($masonry_lines) && !empty($masonry_lines) ? $masonry_lines : ' three_lines';

	$html_code = '';
	$loop_code = '';
	$col_class = ' two-col';
	$cat_name = '';
	$pager = '';
	$pager_param = '';
	$mode_param = '';
	$carousel_param= '';
	$more_url = '';
	$js_slider = '';
	$mb_suffix= '';
	$pattern_class = '';
	$masony_gutter = '<div class="gutter_size"></div>';
	// Reverse thumbnail position when standard
	$rev_thumb_class = '';

	//Unique ID
	$uid = 'loop_div'.dp_rand();

	// Counter
	$i = 0;

	// *********************
	// Category ID & name
	// *********************
	if ( $post_type !== 'post' && $post_type !== 'page') {
		$cat_id = null;
		// } else if ( is_category() && !$args['cat_id'] ) {
		// 	$cat_id = get_query_var('cat');
	}
	// Add category name
	if (!preg_match("/^[0-9]+$/",$cat_id)) {
		// $cat_name = $cat_id;
		// $args += array('cat_name' => $cat_name);
	}

	// *********************
	// Add meta key to array (If not empty) 
	// *********************
	if (!empty($meta_key)) {
		$args['meta_key_views_count'] =  $meta_key;
	}

	// orderby type
	$args['orderby'] = $orderby;

	// *********************
	// More link
	// *********************
	if ( ($type === 'recent' || $type === 'custom')  && $orderby === 'date' && !empty($more_text)) {
		if ($post_type === $options['news_cpt_slug_id']) {
			$more_url = get_post_type_archive_link($post_type);
		} else if ($post_type == 'post') {
			if ($cat_id) {
				$arr_cat_id 	= explode(",", $cat_id);
				$more_url = get_category_link($arr_cat_id[0]);
			} else {
				if (is_home()) {
					$more_url = esc_url(get_pagenum_link(2));
				} else {
					// All posts and not home
					$more_url = home_url('/');
				}
			}
		}
		if ( !empty($more_url) ) {
			$more_url = '<div class="more-entry-link"><a href="'.$more_url.'"><span>'.$more_text.'</span></a></div>';
		}
	}
	// ********************
	// Column
	// ********************
	if ($COLUMN_NUM == 1 || $one_col) {
		$col_class = ' one-col';
	} else if ($COLUMN_NUM == 3) {
		$col_class = ' three-col';
	}
	$args['col_class'] = $col_class;
	// ********************
	// Mobile 
	// ********************
	if ($IS_MOBILE_DP){
		$mb_suffix = '_mb';
		$col_class = '';
		$slider_mode = 'carousel';
		$masonry_lines	= ' one_line';
		if (strpos($layout, 'magazine') !== false) {
			$layout = 'magazine mobile';
		} else if (strpos($layout, 'slider') !== false) {
			$layout = 'slider mobile';
		} else if (strpos($layout, 'news') !== false) {
			$layout = 'news mobile';
		} else {
			$layout = 'normal mobile';
		}
	}
	// ********************
	// wow.js
	// ********************
	$wow_title_css 	= '';
	$wow_article_css = '';
	if (!(bool)$options['disable_wow_js'.$mb_suffix] && !$IS_AMP_DP){
		$wow_title_css= ' wow fadeInLeft';
		$wow_article_css 	= ' wow fadeInUp';
	}
	$args['wow_article_css'] = $wow_article_css;
	// AMP
	if ($IS_AMP_DP) {
		$layout = 'normal mobile';
	}

	/**
	* Javascript for masonry
	*/
	if ($layout === 'slider' || $layout === 'slider mobile') {
		// Load bxslider module
		wp_enqueue_script('dp-bxslider', DP_THEME_URI . '/inc/js/jquery/jquery.bxslider.min.js', array('jquery', 'imagesloaded'),DP_OPTION_SPT_VERSION,true);
		// navigation
		if ( !(bool)$slider_navigation || $IS_MOBILE_DP ) {
			$pager = 'pager:false,';
		}
		if ( !(bool)$slider_control_btn || $IS_MOBILE_DP ) {
			$controls = 'controls:false,';
		} else {
			$controls = "nextText:'<i class=\"icon-right-open\"></i>',prevText:'<i class=\"icon-left-open\"></i>',";
		}
		// Check the slider mode
		if ($slider_mode === 'carousel') {
			if ($IS_MOBILE_DP) {
				$slide_margin = 'slideMargin:10';
			} else {
				$slide_margin = 'slideMargin:1';
			}
			$mode_param = 'mode:"horizontal",moveSlides:1,'.$slide_margin.',autoReload:true,breaks:[{screen:0, slides:1},{screen:568,slides:2},{screen:880,slides:3},{screen:1025,slides:4}],';

		} else {
			$mode_param = 'mode:"'.$slider_mode.'",';
		}
	
		// Js
		$js_slider = "<script>j$(function(){
			var ".$uid."_sl = j$('#".$uid." .loop-slider').bxSlider({
				".$mode_param."
				speed:".$slider_speed.",
				pause:".$slider_duration.","
				.$carousel_param.$pager.$controls.
				"auto:true,
				autoHover:true,
				onSliderLoad:function(){
					j$('#".$uid." .loop-slider').css('opacity',1);
				}
			});
		});</script>";
		$js_slider = str_replace(array("\r\n","\r","\n","\t"), '', $js_slider);
	}

	/**
	 * Main query
	 * @var array
	 */
	$arg_query = array(
			'numberposts'	=> $number,
			'category'=> $cat_id,
			'tag' => $tag_slug,
			'author' => $authors_id,
			's' => $keyword,
			'post_type'=> $post_type,
			'meta_key'=> $meta_key,
			'meta_value'	=> $meta_value,
			'orderby'=> $orderby, // or rand
			'date_query' => array(
				array(
					'year'		=> $year,
					'monthnum'	=> $month
					)),
			'order'	=> $order
			);
	$posts = get_posts($arg_query);

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
			$rev_thumb_class = (bool)$rev_thumb_odd_even ? ' rev_thumb' : '';
			// No masonry
			$masony_gutter = '';
			// Get articles
			foreach( $posts as $post ) : setup_postdata($post);
				// Comment opened?
				if ((bool)$comment && comments_open()) {
					$args['comment'] = true;
				} else {
					$args['comment'] = false;
				}
				// Get post element (listing_post_styles.php)
				$arr_post_ele = dp_post_list_get_elements($args);
				// Get article html and display (listing_post_styles.php)
				$loop_code .= dp_show_post_list_for_archive_normal($args, $arr_post_ele);
			endforeach;
			// Reset Query
			wp_reset_postdata();
			break;
		case 'normal mobile':
			$args['title_length'] = 58;
			$rev_thumb_class = (bool)$options[$top_or_archive.'_rev_thumb_odd_even'] ? ' rev_thumb' : '';
			$masony_gutter = '';
			foreach( $posts as $post ) : setup_postdata($post);
				$args['comment'] = comments_open();
				$arr_post_ele = dp_post_list_get_elements($args);
				$loop_code .= dp_show_post_list_for_archive_normal_mb($args, $arr_post_ele);
			endforeach;
			wp_reset_postdata();
			break;
		case 'blog':
			// Title length
			$args['title_length'] = 120;
			// No masonry
			$masony_gutter = '';
			// Get articles
			foreach( $posts as $post ) : setup_postdata($post);
				// Comment opened?
				if ((bool)$comment && comments_open()) {
					$args['comment'] = true;
				} else {
					$args['comment'] = false;
				}
				// Get post element (listing_post_styles.php)
				$arr_post_ele = dp_post_list_get_elements($args);
				// Get article html and display (listing_post_styles.php)
				$loop_code .= dp_show_post_list_for_archive_blog($args, $arr_post_ele);
			endforeach;
			// Reset Query
			wp_reset_postdata();
			break;
		case 'slider mobile':
			$args['title_length'] = 48;
			foreach( $posts as $post ) : setup_postdata($post);
				if ((bool)$comment && comments_open()) {
					$args['comment'] = true;
				} else {
					$args['comment'] = false;
				}
				$arr_post_ele = dp_post_list_get_elements($args);
				$loop_code .= dp_show_post_list_for_archive_portfolio_mb($args, $arr_post_ele);
			endforeach;
			wp_reset_postdata();
			break;
		case 'magazine one':
			// Title length
			$args['title_length'] = 82;
			// Get articles
			foreach( $posts as $post ) : setup_postdata($post);
				if ((bool)$comment && comments_open()) {
					$args['comment'] = true;
				} else {
					$args['comment'] = false;
				}
				$arr_post_ele = dp_post_list_get_elements($args);
				$loop_code .= dp_show_post_list_for_archive_magazine1($args, $arr_post_ele);
			endforeach;
			wp_reset_postdata();
			break;
		case 'magazine mobile':
			$args['title_length'] = 60;
			foreach( $posts as $post ) : setup_postdata($post);
				$args['is_big'] = (++$i % 4 === 1) ? true : false;
				if ((bool)$comment && comments_open()) {
					$args['comment'] = true;
				} else {
					$args['comment'] = false;
				}
				$arr_post_ele = dp_post_list_get_elements($args);
				$loop_code .= dp_show_post_list_for_archive_magazine_mb($args, $arr_post_ele);
			endforeach;
			wp_reset_postdata();
			break;
		case 'slider':
			$args['title_length'] = 42;
			foreach( $posts as $post ) : setup_postdata($post);
				if ((bool)$comment && comments_open()) {
					$args['comment'] = true;
				} else {
					$args['comment'] = false;
				}
				$arr_post_ele = dp_post_list_get_elements($args);
				$loop_code .= dp_show_post_list_for_archive_slider($args, $arr_post_ele);
			endforeach;
			wp_reset_postdata();
			break;
		case 'news':
		case 'news mobile':
			// Title length
			$args['title_length'] = 100;
			// Get articles
			foreach( $posts as $post ) : setup_postdata($post);
				$arr_post_ele = dp_post_list_get_elements($args);
				$loop_code .= dp_show_post_list_for_archive_news($args, $arr_post_ele);
			endforeach;
			wp_reset_postdata();
			break;
	}

	// For Slider style ( = portfolio)
	if ($layout == 'slider' || $layout == 'slider mobile') {
		$loop_code = '<ul class="loop-slider '.$slider_mode.'">'.$loop_code.'</ul>';
		$layout .= ' portfolio one';
	}

	/**
	 * Artcle list section source
	 */
	$html_code = '<section class="loop-section '.$layout.$col_class.$masonry_lines.$no_padding_class.$rev_thumb_class.' clearfix">';
	
	// Whole html code
	$html_code .= '<div id="'.$uid.'" class="loop-div clearfix'.$col_class.'">'.$masony_gutter.$loop_code.'</div>'.$more_url.$js_slider.'</section>';

	// Display
	if ((bool)$echo) {
		echo $html_code;
	} else {
		return $html_code;
	}
}


/*******************************************************
* Recent Posts widget
*******************************************************/
class DP_RecentPosts_Widget_For_Archive extends WP_Widget {
	function __construct() {
		global $col_class;

		// Widget meta
		$widget_opts = array('classname' 	=> 'loop-div'.$col_class, 
							 'description' 	=> __('Enhanced Recent Posts widget for archive page. Use this only in conent area  of archive page', 'DigiPress')
							 );
		parent::__construct(
			false, 
			__('DP - New Posts for Archive', 'DigiPress'), 
			$widget_opts
			);
	}

	// Input widget form in Admin panel.
	function form($instance) {
		$form_code = '';
		$orderby_form = '';
		$layout_form = '';
		$lines_form = '';
		$slider_mode_form = '';

		$arr_orderby = array(
				'date',
				'modified',
				'most_viewed',
				'comment_count',
				'ID',
				'rand',
				'author');

		$arr_layout = array(
				'normal',
				'blog',
				'magazine one',
				'slider');

		$arr_lines = array('two_lines', 'three_lines', 'four_lines');

		$arr_slider_mode = array('horizontal', 'vertical', 'fade', 'carousel');

		// default value
		$instance = wp_parse_args(
			(array)$instance, 
			array('title' => __('Recent Posts','DigiPress'), 
				  'number' => 5,
				  'cat'=> null,
				  'tag' => null,
				  'authors' => null,
				  'keyword' => null,
				  'show_cat' => true,
				  'comment'	=> false,
				  'meta_key' => 'post_views_count',
				  'post_type' => 'post',
				  'author' 	=> true,
				  'views'	=> false,
				  'orderby'	=> 'date',
				  'pub_date'	=> true,
				  'hatebu_num' => true,
				  'tweets_num' => false,
				  'likes_num' => false,
				  'masonry_lines' => 'three_lines',
				  'more_text'	=> 'More',
				  'read_more_str' => 'Read More',
				  'layout'	=> 'normal',
				  'rev_thumb_odd_even' => true,
				  'excerpt_length' => 120,
				  'overlay_color'	=> 'default',
				  'one_col'	=> false,
				  'slider_mode' => 'horizontal',
				  'slider_speed' => 1200,
				  'slider_duration' => 3000,
				  'slider_navigation' => false,
				  'slider_control_btn' => true
				  )
			);

		// get values
		$title= strip_tags($instance['title']);
		$number= $instance['number'];	
		$show_cat	= $instance['show_cat'];
		$comment	= $instance['comment'];
		$author 	= $instance['author'];
		$views= $instance['views'];
		$hatebu_num = $instance['hatebu_num'];
		$tweets_num = $instance['tweets_num'];
		$likes_num 	= $instance['likes_num'];
		$masonry_lines 	= $instance['masonry_lines'];
		$cat= $instance['cat'];
		$tag	= $instance['tag'];
		$authors	= $instance['authors'];
		$keyword	= $instance['keyword'];
		$pub_date 	= $instance['pub_date'];
		$more_text 	= $instance['more_text'];
		$read_more_str 	= $instance['read_more_str'];
		$layout 	= $instance['layout'];
		$orderby 	= $instance['orderby'];
		$excerpt_length = $instance['excerpt_length'];
		$rev_thumb_odd_even = $instance['rev_thumb_odd_even'];
		$meta_key 	= $instance['meta_key'];
		$post_type 	= $instance['post_type'];
		$one_col 	= $instance['one_col'];
		$overlay_color= $instance['overlay_color'];
		$slider_mode = $instance['slider_mode'];
		$slider_speed = $instance['slider_speed'];
		$slider_duration = $instance['slider_duration'];
		$slider_navigation = $instance['slider_navigation'];
		$slider_control_btn = $instance['slider_control_btn'];

		$title_name	= $this->get_field_name('title');
		$title_id	= $this->get_field_id('title');
		$number_name	= $this->get_field_name('number');
		$number_id	= $this->get_field_id('number');
		$show_cat_name	= $this->get_field_name('show_cat');
		$show_cat_id	= $this->get_field_id('show_cat');
		$comment_name	= $this->get_field_name('comment');
		$comment_id= $this->get_field_id('comment');
		$views_name= $this->get_field_name('views');
		$views_id= $this->get_field_id('views');
		$cat_name= $this->get_field_name('cat');
		$cat_id	= $this->get_field_id('cat');
		$authors_name	= $this->get_field_name('authors');
		$authors_id		= $this->get_field_id('authors');
		$tag_name	= $this->get_field_name('tag');
		$tag_id		= $this->get_field_id('tag');
		$keyword_name	= $this->get_field_name('keyword');
		$keyword_id		= $this->get_field_id('keyword');
		$hatebu_num_name	= $this->get_field_name('hatebu_num');
		$tweets_num_name	= $this->get_field_name('tweets_num');
		$likes_num_name	= $this->get_field_name('likes_num');
		$hatebu_num_id = $this->get_field_id('hatebu_num');
		$tweets_num_id = $this->get_field_id('tweets_num');
		$likes_num_id = $this->get_field_id('likes_num');
		$pub_date_name	= $this->get_field_name('pub_date');
		$pub_date_id	= $this->get_field_id('pub_date');
		$more_text_name	= $this->get_field_name('more_text');
		$more_text_id	= $this->get_field_id('more_text');
		$read_more_str_name	= $this->get_field_name('read_more_str');
		$read_more_str_id	= $this->get_field_id('read_more_str');
		$orderby_name	= $this->get_field_name('orderby');
		$orderby_id= $this->get_field_id('orderby');
		$layout_name	= $this->get_field_name('layout');
		$layout_id= $this->get_field_id('layout');
		$excerpt_length_name	= $this->get_field_name('excerpt_length');
		$excerpt_length_id= $this->get_field_id('excerpt_length');
		$rev_thumb_odd_even_name	= $this->get_field_name('rev_thumb_odd_even');
		$rev_thumb_odd_even_id= $this->get_field_id('rev_thumb_odd_even');
		$author_name	= $this->get_field_name('author');
		$author_id= $this->get_field_id('author');
		$meta_key_name	= $this->get_field_name('meta_key');
		$meta_key_id= $this->get_field_id('meta_key');
		$post_type_name	= $this->get_field_name('post_type');
		$post_type_id= $this->get_field_id('post_type');
		$masonry_lines_name	= $this->get_field_name('masonry_lines');
		$masonry_lines_id	= $this->get_field_id('masonry_lines');
		$one_col_name	= $this->get_field_name('one_col');
		$one_col_id= $this->get_field_id('one_col');
		$overlay_color_name	= $this->get_field_name('overlay_color');
		$overlay_color_id= $this->get_field_id('overlay_color');
		$slider_mode_name	= $this->get_field_name('slider_mode');
		$slider_mode_id= $this->get_field_id('slider_mode');
		$slider_speed_name	= $this->get_field_name('slider_speed');
		$slider_speed_id	= $this->get_field_id('slider_speed');
		$slider_duration_name 	= $this->get_field_name('slider_duration');
		$slider_duration_id 	=$this->get_field_id('slider_duration');
		$slider_navigation_name = $this->get_field_name('slider_navigation');
		$slider_navigation_id 	=$this->get_field_id('slider_navigation');
		$slider_control_btn_name = $this->get_field_name('slider_control_btn');
		$slider_control_btn_id 	=$this->get_field_id('slider_control_btn');

		$show_cat_check;
		if ((bool)$show_cat) $show_cat_check = 'checked';

		$comment_check;
		if ((bool)$comment) $comment_check = 'checked';

		$views_check;
		if ((bool)$views) $views_check = 'checked';

		$hatebu_num_check;
		if ((bool)$hatebu_num) $hatebu_num_check = 'checked';

		$tweets_num_check;
		if ((bool)$tweets_num) $tweets_num_check = 'checked';

		$likes_num_check;
		if ((bool)$likes_num) $likes_num_check = 'checked';

		$pub_date_check = '';
		if ((bool)$pub_date) $pub_date_check = 'checked';

		$author_check;
		if ((bool)$author) $author_check = 'checked';

		$one_col_check;
		if ((bool)$one_col) $one_col_check = 'checked';

		$slider_navigation_check;
		if ((bool)$slider_navigation) $slider_navigation_check = 'checked';

		$slider_control_btn_check;
		if ((bool)$slider_control_btn) $slider_control_btn_check = 'checked';

		$rev_thumb_odd_even_check;
		if ((bool)$rev_thumb_odd_even) $rev_thumb_odd_even_check = 'checked';

		// Order by
		foreach ($arr_orderby as $val) {
			if ($val === $orderby) {
				$orderby_form .= '<option value="'.$val.'" selected>'.__($val,'DigiPress').'</option>';
			} else {
				$orderby_form .= '<option value="'.$val.'">'.__($val,'DigiPress').'</option>';
			}
		}

		// Layout
		foreach ($arr_layout as $val) {
			if ($val === $layout) {
				$layout_form .= '<option value="'.$val.'" selected>'.__($val,'DigiPress').'</option>';
			} else {
				$layout_form .= '<option value="'.$val.'">'.__($val,'DigiPress').'</option>';
			}
		}

		// Lines
		foreach ($arr_lines as $val) {
			if ($val === $masonry_lines) {
				$lines_form .= '<option value="'.$val.'" selected>'.__($val,'DigiPress').'</option>';
			} else {
				$lines_form .= '<option value="'.$val.'">'.__($val,'DigiPress').'</option>';
			}
		}

		foreach ($arr_slider_mode as $val) {
			if ($val === $slider_mode) {
				$slider_mode_form .= '<option value="'.$val.'" selected>'.__($val,'DigiPress').'</option>';
			} else {
				$slider_mode_form .= '<option value="'.$val.'">'.__($val,'DigiPress').'</option>';
			}
		}

		/**
		 * Apply filter(For Popular Posts plugin)
		 */
		$term_code = apply_filters( 'dp_most_viewed_widget_form', 
			array(
				'post',
				$meta_key, 
				$meta_key_name, 
				$meta_key_id)
			);

		// Overlay color
		$arr_overlay_color = array('default','black','white');
		foreach ($arr_overlay_color as $val) {
			if ($val === $overlay_color) {
				$overlay_color_form .= '<option value="'.$val.'" selected>'.__($val,'DigiPress').'</option>';
			} else {
				$overlay_color_form .= '<option value="'.$val.'">'.__($val,'DigiPress').'</option>';
			}
		}
		$overlay_color_form = '<select id="'.$overlay_color_id.'" name="'.$overlay_color_name.'" size=1 style="width:150px;">'.$overlay_color_form.'</select>';

		// Custom Post type
		$all_custom_post_types = get_post_types(
					array(
						'public'	=> true,
						'_builtin'	=> false
					), 'objects');
		$form_post_type_code = ($post_type === 'post') ? '<option value="post" selected>'.__('Posts', 'default').'</option>' : '<option value="post">'.__('Posts', 'default').'</option>';
		foreach ($all_custom_post_types as $cpt ) {
			if ($post_type === $cpt->name) {
				$form_post_type_code .= '<option value="'.$cpt->name.'" selected>'.$cpt->label.'</option>';
			} else {
				$form_post_type_code .= '<option value="'.$cpt->name.'">'.$cpt->label.'</option>';
			}
		}
		$form_post_type_code = '<select name="'.$post_type_name.'" id="'.$post_type_id.'">'.$form_post_type_code.'</select>';

		// Show target
		$arr_show_target = array('nothing','top page','archive','category','author','date archive','post','page');
		foreach ($arr_show_target as $val) {
			if ($val === $show_target) {
				$show_target_form .= '<option value="'.$val.'" selected>'.__($val,'DigiPress').'</option>';
			} else {
				$show_target_form .= '<option value="'.$val.'">'.__($val,'DigiPress').'</option>';
			}
		}
		$show_target_form = '<select id="'.$show_target_id.'" name="'.$show_target_name.'" size=1>'.$show_target_form.'</select>';

		// Show form
		$form_code = '<p>'.__('*Note : This widget is suitable for container or content area.','DigiPress').'</p><hr />';
		$form_code .= '<p><label for="'.$title_id.'">'.__('Title','DigiPress').':</label><br /><input type="text" name="'.$title_name.'" id="'.$title_id.'" value="'.$title.'" style="width:100%;" /></p>';
		$form_code .= '<p><label for="'.$number_id.'">'.__('Number to display','DigiPress').': </label><input type="number" min=1 name="'.$number_name.'" id="'.$number_id.'" value="'.$number.'" style="width:60px;" /></p>';
		$form_code .= '<p><label for="'.$post_type_id.'">'.__('Target post type','DigiPress').' :</label> '.$form_post_type_code.'</p>';
		$form_code .= '<p><label for="'.$layout_id.'">'.__('Archive layout','DigiPress').' :</label> <select id="'.$layout_id.'" name="'.$layout_name.'" size=1 style="min-width:50%;">'.$layout_form.'</select></p>';
		$form_code .= '<div class="dp_widget_for_archive_slider_option">
						<div class="opt_title">'.__('Slider option(*Hover open)','DigiPress').'</div>
							<div class="option_table"><hr /><table><tbody>
							<tr>
							<td><label for="'.$slider_mode_id.'">'.__('- Slider mode : ', 'DigiPress').'</label></td>
							<td><select id="'.$slider_mode_id.'" name="'.$slider_mode_name.'" size=1>'.$slider_mode_form.'</select></td>
							</tr>
							<tr>
							<td><label for="'.$slider_speed_id.'">'.__('- Transition speed : ', 'DigiPress').'</label></td>
							<td><input id="'.$slider_speed_id.'" name="'.$slider_speed_name.'" type="text" value="'.$slider_speed.'" size=4 />'.__('ms', 'DigiPress').'</td>
							</tr>
							<tr>
							<td><label for="'.$slider_duration_id.'">'.__('- Duration : ', 'DigiPress').'</label></td>
							<td><input id="'.$slider_duration_id.'" name="'.$slider_duration_name.'" type="text" value="'.$slider_duration.'" size=4 />'.__('ms', 'DigiPress').'</td>
							</tr>
							<tr>
							<td><label for="'.$slider_control_btn_id.'">'.__('- Show prev/next : ', 'DigiPress').'</label></td>
							<td><input id="'.$slider_control_btn_id.'" name="'.$slider_control_btn_name.'" type="checkbox" value="true" '.$slider_control_btn_check.' /></td>
							</tr>
							<tr>
							<td><label for="'.$slider_navigation_id.'">'.__('- Show navigation : ', 'DigiPress').'</label></td>
							<td><input id="'.$slider_navigation_id.'" name="'.$slider_navigation_name.'" type="checkbox" value="true" '.$slider_navigation_check.' /></td>
							</tr>
							</tbody></table></div>
						</div>';
		$form_code .= '<p><label for="'.$masonry_lines_id.'">'.__('Column :','DigiPress').'</label> <select id="'.$masonry_lines_id.'" name="'.$masonry_lines_name.'" size=1 style="min-width:50%;">'.$lines_form.'</select><br /><span style="font-size:11px;">'.__('*Note: This is for Portfolio and Magazine.','DigiPress').'</span></p>';
		$form_code .= '<p><label for="'.$orderby_id.'">'.__('Order by','DigiPress').' :</label> <select id="'.$orderby_id.'" name="'.$orderby_name.'" size=1 style="min-width:50%;">'.$orderby_form.'</select></p>';
		if (is_string($term_code)) {
			$form_code .= $term_code;
		}
		$form_code .= '<p><input name="'.$pub_date_name.'" id="'.$pub_date_id.'" type="checkbox" value="show" '.$pub_date_check.' /><label for="'.$pub_date_id.'">'.__('Show date','DigiPress').'</label></p>';
		$form_code .= '<p><input name="'.$author_name.'" id="'.$author_id.'" type="checkbox" value="show" '.$author_check.' /><label for="'.$author_id.'">'.__('Show author','DigiPress').'</label></p>';
		$form_code .= '<p><input name="'.$show_cat_name.'" id="'.$show_cat_id.'" type="checkbox" value="true" '.$show_cat_check.' /><label for="'.$show_cat_id.'">'.__('Show category','DigiPress').'</label></p>';
		$form_code .= '<p><input name="'.$views_name.'" id="'.$views_id.'" type="checkbox" value="on" '.$views_check.' /><label for="'.$views_id.'">'.__('Show views.', 'DigiPress').'</label></p>';
		$form_code .= '<p><input name="'.$comment_name.'" id="'.$comment_id.'" type="checkbox" value="on" '.$comment_check.' /><label for="'.$comment_id.'">'.__('Show the number of comment(s).', 'DigiPress').'</label></p>';
		$form_code .= '<p><input name="'.$hatebu_num_name.'" id="'.$hatebu_num_id.'" type="checkbox" value="on" '.$hatebu_num_check.' /><label for="'.$hatebu_num_id.'">'.__('Show Hatebu bookmarked number.', 'DigiPress').'</label></p>';
		$form_code .= '<p><input name="'.$likes_num_name.'" id="'.$likes_num_id.'" type="checkbox" value="on" '.$likes_num_check.' /><label for="'.$likes_num_id.'">'.__('Show Facebook likes number.', 'DigiPress').'</label></p>';
		$form_code .= '<p><input name="'.$tweets_num_name.'" id="'.$tweets_num_id.'" type="checkbox" value="on" '.$tweets_num_check.' /><label for="'.$tweets_num_id.'">'.__('Show tweets number.', 'DigiPress').'</label></p>';
		$form_code .= '<p><input name="'.$rev_thumb_odd_even_name.'" id="'.$rev_thumb_odd_even_id.'" type="checkbox" value="on" '.$rev_thumb_odd_even_check.' /><label for="'.$rev_thumb_odd_even_id.'">'.__('Show left and right thumbnails are alternately repeated.', 'DigiPress').'</label><br /><span style="font-size:11px;">'.__('*Note: For Standard', 'DigiPress').'</span></p>';
		$form_code .= '<p><label for="'.$overlay_color_id.'">'.__('Overlay color','DigiPress').'</label> : '.$overlay_color_form.'<br /><span style="font-size:11px;">'.__('*Note: For Portfolio. Default = key color', 'DigiPress').'</span></p>';
		$form_code .= '<p><label for="'.$excerpt_length_id.'">'.__('Excerpt length:','DigiPress').' </label><input type="number" min=0 max=160 name="'.$excerpt_length_name.'" id="'.$excerpt_length_id.'" value="'.$excerpt_length.'" style="width:60px;" />'.__('strings', 'DigiPress').'</p>';
		$form_code .= '<p><input name="'.$one_col_name.'" id="'.$one_col_id.'" type="checkbox" value="true" '.$one_col_check.' /><label for="'.$one_col_id.'">'.__('Show on full width.', 'DigiPress').'</label></p>';
		$form_code .= '<p><label for="'.$read_more_str_id.'">'.__('More label for each posts','DigiPress').':</label><br /><input type="text" name="'.$read_more_str_name.'" id="'.$read_more_str_id.'" value="'.$read_more_str.'" style="width:100%;" /></p>';
		$form_code .= '<p><label for="'.$more_text_id.'">'.__('More label to the archive','DigiPress').':</label><br /><input type="text" name="'.$more_text_name.'" id="'.$more_text_id.'" value="'.$more_text.'" style="width:100%;" /></p>';
		$form_code .= '<p><label for="'.$cat_id.'">'.__('Target Category(ID or slug)','DigiPress').':</label><br /><input type="text" name="'.$cat_name.'" id="'.$cat_id.'" value="'.htmlspecialchars($cat).'" style="width:100%;" /><br />
			 <span style="font-size:11px;">'.__('*Note: Specific category ID or slug are available.(ex. 2,4,12,"cat-slug")', 'DigiPress').'</span></p>';
		$form_code .= '<p><label for="'.$tag_id.'">'.__('Target Tag\'s Slug(Optional)','DigiPress').':</label><br />
			 <input type="text" name="'.$tag_name.'" id="'.$tag_id.'" value="'.$tag.'" style="width:100%;" /><br />
			 <span style="font-size:11px;">'.__('*Note: Multiple tags are available.(ex. bread,baking)', 'DigiPress').'</span></p>';
		$form_code .= '<p><label for="'.$authors_id.'">'.__('Target Author\'s ID(Optional)','DigiPress').':</label><br />
			 <input type="text" name="'.$authors_name.'" id="'.$authors_id.'" value="'.$authors.'" style="width:100%;" /><br />
			 <span style="font-size:11px;">'.__('*Note: Multiple authors are available.(ex. 2,6)', 'DigiPress').'</span></p>';
		$form_code .= '<p><label for="'.$keyword_id.'">'.__('Search word(Optional)','DigiPress').':</label><br />
			 <input type="text" name="'.$keyword_name.'" id="'.$keyword_id.'" value="'.$keyword.'" style="width:100%;" /><br />
			 <span style="font-size:11px;">'.__('*Note: Insert the seach words to get related posts of the specific words.', 'DigiPress').'</span></p>';

		// Display
		echo $form_code;
	}

	// Save Function
	// $new_instance : Input value
	// $old_instance : Exist value
	// Return : New values
	function update($new_instance, $old_instance) {
		$instance['title']= strip_tags($new_instance['title']);
		$instance['number']= $new_instance['number'];
		$instance['show_cat']	= $new_instance['show_cat'];
		$instance['comment']	= $new_instance['comment'];
		$instance['author']= $new_instance['author'];
		$instance['views']= $new_instance['views'];
		$instance['cat']= $new_instance['cat'];
		$instance['authors']	= $new_instance['authors'];
		$instance['tag']	= $new_instance['tag'];
		$instance['keyword']	= $new_instance['keyword'];
		$instance['hatebu_num']	= $new_instance['hatebu_num'];
		$instance['tweets_num']	= $new_instance['tweets_num'];
		$instance['likes_num']	= $new_instance['likes_num'];
		$instance['masonry_lines']	= $new_instance['masonry_lines'];
		$instance['orderby']	= $new_instance['orderby'];
		$instance['pub_date']	= $new_instance['pub_date'];
		$instance['more_text']	= $new_instance['more_text'];
		$instance['read_more_str']	= $new_instance['read_more_str'];
		$instance['layout']= $new_instance['layout'];
		$instance['excerpt_length']	= $new_instance['excerpt_length'];
		$instance['meta_key']	= $new_instance['meta_key'];
		$instance['post_type']	= $new_instance['post_type'];
		$instance['one_col']	= $new_instance['one_col'];
		$instance['overlay_color']	= $new_instance['overlay_color'];
		$instance['rev_thumb_odd_even']	= $new_instance['rev_thumb_odd_even'];
		$instance['slider_mode']	= $new_instance['slider_mode'];
		$instance['slider_speed']	= $new_instance['slider_speed'];
		$instance['slider_duration']= $new_instance['slider_duration'];
		$instance['slider_control_btn']= $new_instance['slider_control_btn'];
		$instance['slider_navigation']= $new_instance['slider_navigation'];

		// Check errors
		if (!$instance['title']) {
			$before_title 	= '';
			$after_title	= '';
			/*$instance['title'] = strip_tags($old_instance['title']);
			$this->m_error = '<span style="color:#ff0000;">'.__('The title is blank. It will be reset to old value.', 'DigiPress').'</span>';*/
		}
		return $instance;
	}

	// Display to theme
	// $args : output array
	// $instance : exist array
	function widget($args, $instance) {
		extract($args);
		$instance = wp_parse_args(
			(array)$instance, 
			array('title' => '',
				  'number' => 5, 
				  'show_cat' => true,
				  'comment'	=> false,
				  'author'	=> true,
				  'views'	=> false,
				  'cat'=> null,
				  'tag'		=> null,
				  'authors'	=> null,
				  'keyword'	=> null,
				  'orderby'	=> 'date',
				  'pub_date' => true,
				  'meta_key' => 'post_views_count',
				  'post_type' => 'post',
				  'hatebu_num' => true,
				  'tweets_num' => false,
				  'likes_num' => false,
				  'masonry_lines' => 'three_lines',
				  'more_text'	=> 'More',
				  'read_more_str' => 'Read More',
				  'layout'=> 'normal',
				  'excerpt_length' => 120,
				  'one_col' 	=> false,
				  'overlay_color'	=> 'default',
				  'rev_thumb_odd_even' => true,
				  'slider_mode' => 'horizontal',
				  'slider_speed' => 1200,
				  'slider_duration' => 3000,
				  'slider_control_btn' => true,
				  'slider_navigation' => false,
				  'show_target'	=> '',
				  'target_id'	=> '')
			);

		$title = $instance['title'];
		$title = apply_filters('widget_title', $title);
		$number = $instance['number'];
		$show_cat	= $instance['show_cat'];
		$comment	= $instance['comment'];
		$author= $instance['author'];
		$views= $instance['views'];
		$cat= $instance['cat'];
		$tag		= $instance['tag'];
		$authors	= $instance['authors'];
		$keyword	= $instance['keyword'];
		$hatebu_num = $instance['hatebu_num'];
		$tweets_num = $instance['tweets_num'];
		$likes_num = $instance['likes_num'];
		$masonry_lines = $instance['masonry_lines'];
		$orderby	= $instance['orderby'];
		$pub_date 	= $instance['pub_date'];
		$more_text 	= $instance['more_text'];
		$read_more_str 	= $instance['read_more_str'];
		$layout 	= $instance['layout'];
		$excerpt_length = $instance['excerpt_length'];
		$one_col 	= $instance['one_col'];
		$overlay_color = $instance['overlay_color'];
		$rev_thumb_odd_even = $instance['rev_thumb_odd_even'];
		$slider_mode 	= $instance['slider_mode'];
		$slider_speed 	= $instance['slider_speed'];
		$slider_duration= $instance['slider_duration'];
		$slider_control_btn = $instance['slider_control_btn'];
		$slider_navigation = $instance['slider_navigation'];
		$meta_key 	= $instance['meta_key'] ? $instance['meta_key'] : 'post_views_count';
		$post_type 	= $instance['post_type'] ? $instance['post_type'] : 'post';

		// IF order by custom field
		if ($orderby === 'most_viewed') {
			$orderby 	= 'meta_value_num';
		}

		// Display widget
		echo $before_widget;
		if ($instance['title']) {
			echo $before_title.$title.$after_title;
		}
		
		DP_LISTING_POST_EACH_STYLES(array(
					'echo'=> true,
					'number'	=> $number,
					'comment'	=> $comment,
					'author'	=> $author,
					'views' 	=> $views,
					'show_cat'	=> $show_cat,
					'cat_id'	=> str_replace("\s", "", $cat),
					'tag_slug'	=> str_replace("\s", "", $tag),
					'authors_id'=> str_replace("\s", "", $authors),
					'keyword' => $keyword,
					'hatebu_num'=> $hatebu_num,
					'tweets_num'=> $tweets_num,
					'likes_num'	=> $likes_num,
					'masonry_lines' => ' '.$masonry_lines,
					'orderby'	=> $orderby,
					'order'=> 'DESC',
					'pub_date'	=> $pub_date,
					'more_text'	=> $more_text,
					'read_more_str'	=> $read_more_str,
					'type'=> 'recent',
					'layout'	=> $layout,
					'excerpt_length' => $excerpt_length,
					'meta_key'	=> $meta_key,
					'post_type'	=> $post_type,
					'one_col'	=> $one_col,
					'overlay_color'=> ' '.$overlay_color,
					'rev_thumb_odd_even' => $rev_thumb_odd_even,
					'slider_mode' => $slider_mode,
					'slider_speed' => $slider_speed,
					'slider_duration' => $slider_duration,
					'slider_control_btn' => $slider_control_btn,
					'slider_navigation' => $slider_navigation
					)
		);
		echo $after_widget;
	}
}
// widgets_init
add_action('widgets_init', function(){register_widget('DP_RecentPosts_Widget_For_Archive');});