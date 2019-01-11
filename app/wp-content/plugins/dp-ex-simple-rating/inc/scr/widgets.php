<?php
/*******************************************************
* Original Widget
*******************************************************/
class DP_EX_SIMRAT_BEST_RATED_WIDGET extends WP_Widget {
	/**
	 * Private params
	 *
	 */
	private $meta_key  		= 'dp_ex_sr_votes_like_count';
	private $text_domain 	= DP_EX_SIMPLE_RATING_TEXT_DOMAIN;

	/**
	 * Class constructor.
	 *
	 * @return void
	 */
	function __construct() {
		$widget_opts = array('classname' 	=> 'dp_ex_simrat_best_rated_widget', 
							 'description' 	=> __('Best rated posts by DigiPress Ex - Simple Rating', $this->text_domain) );
		$control_opts = array('width' => 300, 'height' => 300);
		parent::__construct('DP_EX_SIMRAT_BEST_RATED_WIDGET', 
						__('DP - Best Rated Posts',  $this->text_domain), 
						$widget_opts, 
						$control_opts);
	}

	// Input widget form in Admin panel.
	function form($instance) {
		// default value
		$instance = wp_parse_args((array)$instance, array('title' => __('Best Rated Posts', $this->text_domain), 
														  'number' => 5,
														  'cat'		=> null,
														  'thumbnail' => true,
														  'comment'	=> false,
														  'views'	=> false,
														  'year'	=> '',
														  'month'	=> '',
														  'pub_date'	=> 'show',
														  'post_type'	=> 'post',
														  'hatebuNumber' => true,
														  'tweetsNumber' => false,
														  'likesNumber' => false,
														  'icon' => 'icon-heart'));
		$form_code = '';

		// get values
		$title		= strip_tags($instance['title']);
		$number		= $instance['number'];	
		$thumbnail	= $instance['thumbnail'];
		$comment	= $instance['comment'];
		$views		= $instance['views'];
		$year		= $instance['year'];
		$month		= $instance['month'];
		$hatebuNumber = $instance['hatebuNumber'];
		$tweetsNumber = $instance['tweetsNumber'];
		$likesNumber = $instance['likesNumber'];
		$post_type	= $instance['post_type'];
		$pub_date 	= $instance['pub_date'];
		$icon 		= $instance['icon'];

		$titlename	= $this->get_field_name('title');
		$titleid	= $this->get_field_id('title');
		$numbername	= $this->get_field_name('number');
		$numberid	= $this->get_field_id('number');
		$thumbnailname	= $this->get_field_name('thumbnail');
		$thumbnailid	= $this->get_field_id('thumbnail');
		$commentName	= $this->get_field_name('comment');
		$commentId		= $this->get_field_id('comment');
		$viewsName		= $this->get_field_name('views');
		$viewsId		= $this->get_field_id('views');
		$yearName		= $this->get_field_name('year');
		$yearId			= $this->get_field_id('year');
		$monthName		= $this->get_field_name('month');
		$monthId		= $this->get_field_id('month');
		$catName		= $this->get_field_name('cat');
		$catId			= $this->get_field_id('cat');
		$hatebuNumberName	= $this->get_field_name('hatebuNumber');
		$tweetsNumberName	= $this->get_field_name('tweetsNumber');
		$likesNumberName	= $this->get_field_name('likesNumber');
		$hatebuNumberId = $this->get_field_id('hatebuNumber');
		$tweetsNumberId = $this->get_field_id('tweetsNumber');
		$likesNumberId = $this->get_field_id('likesNumber');
		$pub_date_name	= $this->get_field_name('pub_date');
		$pub_date_id	= $this->get_field_id('pub_date');
		$icon_name	= $this->get_field_name('icon');
		$icon_id	= $this->get_field_id('icon');
		$post_type_name	= $this->get_field_name('post_type');
		$post_type_id	= $this->get_field_id('post_type');


		$thumbeCheck = '';
		if ($thumbnail) $thumbeCheck = 'checked';
		
		$commentCheck = '';
		if ($comment) $commentCheck = 'checked';
		
		$viewsCheck = '';
		if ($views) $viewsCheck = 'checked';
		
		$hatebuNumberCheck = '';
		if ($hatebuNumber) $hatebuNumberCheck = 'checked';

		$tweetsNumberCheck = '';
		if ($tweetsNumber) $tweetsNumberCheck = 'checked';

		$likesNumberCheck = '';
		if ($likesNumber) $likesNumberCheck = 'checked';

		$pub_dateCheck = '';
		if ($pub_date === 'show') $pub_dateCheck = 'checked';

		// Post type
		$check_post = $post_type === 'post' ? ' checked' : '';
		$check_page = $post_type === 'page' ? ' checked' : '';

		// Show form
		$form_code = '<p><label for="'.$titleid.'">'.__('Title','DigiPress').':</label><br />';
		$form_code .= '<input type="text" name="'.$titlename.'" id="'.$titleid.'" value="'.$title.'" style="width:100%;" /></p>';
		$form_code .= '<p><label for="'.$numberid.'">'.__('Number to display','DigiPress').':</label>
			 <input type="text" name="'.$numbername.'" id="'.$numberid.'" value="'.$number.'" style="width:60px;" /></p>';
		$form_code .= '<p><input name="'.$thumbnailname.'" id="'.$thumbnailid.'" type="checkbox" value="on" '.$thumbeCheck.' />
			 <label for="'.$thumbnailid.'">'.__('Show Thumbnail','DigiPress').'</label></p>';
		$form_code .= '<p><input name="'.$pub_date_name.'" id="'.$pub_date_id.'" type="checkbox" value="show" '.$pub_dateCheck.' />
			 <label for="'.$pub_date_id.'">'.__('Show date','DigiPress').'</label></p>';
		$form_code .= '<p><input name="'.$commentName.'" id="'.$commentId.'" type="checkbox" value="on" '.$commentCheck.' />
			 <label for="'.$commentId.'">'.__('Show the number of comment(s).', 'DigiPress').'</label></p>';
		$form_code .= '<p><input name="'.$viewsName.'" id="'.$viewsId.'" type="checkbox" value="on" '.$viewsCheck.' />
			 <label for="'.$viewsId.'">'.__('Show views.', 'DigiPress').'</label></p>';
		$form_code .= '<p><input name="'.$hatebuNumberName.'" id="'.$hatebuNumberId.'" type="checkbox" value="on" '.$hatebuNumberCheck.' />
			 <label for="'.$hatebuNumberId.'">'.__('Show Hatebu bookmarked number.', 'DigiPress').'</label></p>';
		$form_code .= '<p><input name="'.$tweetsNumberName.'" id="'.$tweetsNumberId.'" type="checkbox" value="on" '.$tweetsNumberCheck.' />
			 <label for="'.$tweetsNumberId.'">'.__('Show tweets number.', 'DigiPress').'</label></p>';
		$form_code .= '<p><input name="'.$likesNumberName.'" id="'.$likesNumberId.'" type="checkbox" value="on" '.$likesNumberCheck.' />
			 <label for="'.$likesNumberId.'">'.__('Show Facebook likes number.', 'DigiPress').'</label></p>';
		$form_code .= '<p><label for="'.$post_type_id.'" style="margin-right:20px;">'.__('Post type',$this->text_domain).': </label><input type="radio" id="'.$post_type_id.'1" name="'.$post_type_name.'" value="post"'.$check_post.' /><label for="'.$post_type_id.'1" style="margin-right:20px;">'.__('Post',$this->text_domain).'</label>
		<input type="radio" id="'.$post_type_id.'2" name="'.$post_type_name.'" value="page"'.$check_page.' /><label for="'.$post_type_id.'2">'.__('Page',$this->text_domain).'</label></p>';
		$form_code .= '<p><label for="'.$icon_id.'">'.__('Icon class',$this->text_domain).':</label><br /><input type="text" name="'.$icon_name.'" id="'.$icon_id.'" value="'.$icon.'" style="width:60%;" />
			 <span style="font-size:12px;"><a href="https://digipress.digi-state.com/manual/icon-font-map/" target="_blank" class="icon-new-tab">'.__('Available icons', $this->text_domain).'</a></span></p>';
		$form_code .= '<p><label for="'.$yearId.'">'.__('Target Year(Optional)','DigiPress').':</label><br />
			 <input type="text" name="'.$yearName.'" id="'.$yearId.'" value="'.$year.'" style="width:100%;" /><br />
			 <span style="font-size:11px;">'.__('*Ex: 2013', 'DigiPress').'</span></p>';
		$form_code .= '<p><label for="'.$monthId.'">'.__('Target Month(Optional)','DigiPress').':</label><br />
			 <input type="text" name="'.$monthName.'" id="'.$monthId.'" value="'.$month.'" style="width:100%;" /><br />
			 <span style="font-size:11px;">'.__('*Note: Insert 1 to 12.', 'DigiPress').'</span></p>';
		$form_code .= '<p><label for="'.$catId.'">'.__('Target Category(Optional)','DigiPress').':</label><br />
			 <input type="text" name="'.$catName.'" id="'.$catId.'" value="'.$cat.'" style="width:100%;" /><br />
			 <span style="font-size:11px;">'.__('*Note: Multiple categories are available.(ex. 2,4,12)', 'DigiPress').'</span></p>';

		echo $form_code;
	}

	// Save Function
	// $new_instance : Input value
	// $old_instance : Exist value
	// Return : New values
	function update($new_instance, $old_instance) {
		$instance['title']		= strip_tags($new_instance['title']);
		$instance['number']		= $new_instance['number'];
		$instance['thumbnail']	= $new_instance['thumbnail'];
		$instance['comment']	= $new_instance['comment'];
		$instance['views']		= $new_instance['views'];
		$instance['year']		= $new_instance['year'];
		$instance['month']		= $new_instance['month'];
		$instance['cat']		= $new_instance['cat'];
		$instance['hatebuNumber']	= $new_instance['hatebuNumber'];
		$instance['tweetsNumber']	= $new_instance['tweetsNumber'];
		$instance['likesNumber']	= $new_instance['likesNumber'];
		$instance['pub_date']	= $new_instance['pub_date'];
		$instance['icon']		= $new_instance['icon'];
		$instance['post_type']	= $new_instance['post_type'];

		// Check errors
		if (!$instance['title']) {
			$before_title 	= '';
			$after_title	= '';
		}
		return $instance;
	}

	// Display to theme
	// $args : output array
	// $instance : exist array
	function widget($args, $instance) {
		extract($args);
		$instance = wp_parse_args((array)$instance, array('title' => '',
														  'number' => 5, 
														  'thumbnail' => true,
														  'comment'	=> false,
														  'views'	=> false,
														  'year'	=> '',
														  'month'	=> '',
														  'cat'		=> null,
														  'post_type' => 'post',
														  'pub_date'	=> 'show',
														  'hatebuNumber' => true,
														  'tweetsNumber' => false,
														  'likesNumber' => false,
														  'icon' => 'icon-heart'));
		
		$title = $instance['title'];
		$title = apply_filters('widget_title', $title);
		$number = $instance['number'];
		$thumbnail	= $instance['thumbnail'];
		$comment	= $instance['comment'];
		$views		= $instance['views'];
		$year		= $instance['year'];
		$month		= $instance['month'];
		$cat		= $instance['cat'];
		$hatebuNumber = $instance['hatebuNumber'];
		$tweetsNumber = $instance['tweetsNumber'];
		$likesNumber = $instance['likesNumber'];
		$pub_date 	= $instance['pub_date'];
		$icon 	= $instance['icon'];
		$post_type 	= $instance['post_type'];

		// Display widget
		echo $before_widget;
		if ($instance['title']) {
			echo $before_title.$title.$after_title;
		}
		
		if (function_exists('DP_GET_POSTS_BY_QUERY')) {
			DP_GET_POSTS_BY_QUERY(array(
					'number'	=> $number,
					'comment'	=> $comment,
					'views' 	=> $views,
					'thumbnail'	=> $thumbnail,
					'cat_id'	=> str_replace("\s", "", $cat),
					'year'		=> $year,
					'month'		=> $month,
					'hatebuNumber'	=> $hatebuNumber,
					'tweetsNumber'	=> $tweetsNumber,
					'likesNumber'	=> $likesNumber,
					'post_type' => $post_type,
					'meta_key'	=> $this->meta_key,
					'order_by'	=> 'meta_value_num',
					'pub_date'	=> $pub_date,
					'voted_icon' => $icon,
					'voted_count' => true
					)
			);
		}
		echo $after_widget;
	}
}
