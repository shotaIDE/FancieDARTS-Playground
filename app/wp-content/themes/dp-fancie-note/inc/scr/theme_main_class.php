<?php
/*******************************************************
* DigiPress Theme Option Class
*******************************************************/
class digipress_options {
	const OPTION_NAME	= 'digipress';
	const OPTION_VISUAL	= 'digipress_visual';
	const OPTION_CONTROL	= 'digipress_control';
	const OPTION_DELETE	= 'digipress_delete_file';
	const OPTION_IMG_EDIT	= 'digipress_edit_images';
	const OPTION_ADD_ONS	= 'digipress_add_ons';
	/* ==================================================
	* Save the theme settings for visual
	* ==================================================
	* @param	none
	* @return	array	$options_visual
	*/
	//Get Options
	public static function getOptions_visual() {
		//Global scope
		global $def_visual;
		
		$options_visual = get_option('dp_options_visual');
		if (!is_array($options_visual)) {
			//Set default
			$options_visual = $def_visual;
			//Update
			update_option('dp_options_visual', $options_visual);
		}
		return $options_visual;
	}
	//Update visual settings
	static function update_visual() {
		if(isset($_POST['dp_save_visual'])) {
			//Get default settings
			global $def_visual;
			//Set default
			$options_visual = digipress_options::getOptions_visual();

			$arr_http = array('http:','https:');

			//Column type
			$options_visual['dp_column']	= $_POST['dp_column'];
			//Sidebar type
			$options_visual['dp_theme_sidebar']	= $_POST['dp_theme_sidebar'];
			// $options_visual['dp_theme_sidebar2']	= $_POST['dp_theme_sidebar2'];
			// 1Column only top page
			if (isset($_POST['dp_1column_only_top'])) {
				$options_visual['dp_1column_only_top'] 	= (bool)true;
			} else {
				$options_visual['dp_1column_only_top'] 	= (bool)false;
			}

			$options_visual['header_banner_font_color']	= ($_POST['header_banner_font_color'] && $_POST['header_banner_font_color'] !== '#') ? $_POST['header_banner_font_color'] : $def_visual['header_banner_font_color'];


			if (isset($_POST['header_banner_text_shadow_enable'])) {
				$options_visual['header_banner_text_shadow_enable']	= (bool)true;
			}  else {
				$options_visual['header_banner_text_shadow_enable']	= (bool)false;
			}

			$options_visual['header_banner_text_shadow_color']	= ($_POST['header_banner_text_shadow_color'] && $_POST['header_banner_text_shadow_color'] !== '#') ? $_POST['header_banner_text_shadow_color'] : $def_visual['header_banner_text_shadow_color'];

			//Header anchor color
			$options_visual['header_menu_link_color']	= ($_POST['header_menu_link_color'] && $_POST['header_menu_link_color'] !== '#') ? $_POST['header_menu_link_color'] : $def_visual['header_menu_link_color'];
			$options_visual['header_menu_bgcolor']	= $_POST['header_menu_bgcolor'] ? $_POST['header_menu_bgcolor'] : $def_visual['header_menu_bgcolor'];
			$options_visual['header_menu_link_hover_color']	= $_POST['header_menu_link_hover_color'] ? $_POST['header_menu_link_hover_color'] : $def_visual['header_menu_link_hover_color'];

			// Accent color 
			$options_visual['accent_color']	= $_POST['accent_color'];

			//anchor color of menu
			$options_visual['menu_link_color']	= $_POST['menu_link_color'];
			//anchor color of menu on hovering
			$options_visual['menu_link_hover_color']	= $_POST['menu_link_hover_color'];

			$options_visual['global_menu_pos']	= $_POST['global_menu_pos'];

			//content type of header banner area
			$options_visual['dp_header_content_type'] = $_POST['dp_header_content_type'];

			//Slideshow Type
			$options_visual['dp_slideshow_target']	= $_POST['dp_slideshow_target'];

			//Number of slideshow
			$options_visual['dp_number_of_slideshow']	= $_POST['dp_number_of_slideshow'];

			//Order of slideshow
			$options_visual['dp_slideshow_order']	= $_POST['dp_slideshow_order'];

			//Effect of slideshow
			$options_visual['dp_slideshow_effect']	= $_POST['dp_slideshow_effect'];

			// Transition time
			$options_visual['dp_slideshow_transition_time'] = mb_convert_kana($_POST['dp_slideshow_transition_time'],"n");
			if (!is_numeric($options_visual['dp_slideshow_transition_time'])) $options_visual['dp_slideshow_transition_time'] = $def_visual['dp_slideshow_transition_time'];

			// Time for each slide
			$options_visual['dp_slideshow_speed'] = mb_convert_kana($_POST['dp_slideshow_speed'],"n");
			if (!is_numeric($options_visual['dp_slideshow_speed'])) $options_visual['dp_slideshow_speed'] = $def_visual['dp_slideshow_speed'];

			if (isset($_POST['dp_slideshow_nav_button'])) {
				$options_visual['dp_slideshow_nav_button']	= (bool)true;
			}  else {
				$options_visual['dp_slideshow_nav_button']	= (bool)false;
			}

			if (isset($_POST['dp_slideshow_control_button'])) {
				$options_visual['dp_slideshow_control_button']	= (bool)true;
			}  else {
				$options_visual['dp_slideshow_control_button']	= (bool)false;
			}

			if (isset($_POST['dp_slideshow_post_show_meta_always'])) {
				$options_visual['dp_slideshow_post_show_meta_always']	= (bool)true;
			}  else {
				$options_visual['dp_slideshow_post_show_meta_always']	= (bool)false;
			}

			$options_visual['dp_header_img_mobile']	= $_POST['dp_header_img_mobile'];
			//content type of header banner area
			$options_visual['dp_header_content_type_mobile'] = $_POST['dp_header_content_type_mobile'];

			//Slideshow Type
			$options_visual['dp_slideshow_target_mobile']	= $_POST['dp_slideshow_target_mobile'];

			//Number of slideshow
			$options_visual['dp_number_of_slideshow_mobile']	= $_POST['dp_number_of_slideshow_mobile'];

			//Order of slideshow
			$options_visual['dp_slideshow_order_mobile']	= $_POST['dp_slideshow_order_mobile'];

			//Effect of slideshow
			$options_visual['dp_slideshow_effect_mobile']	= $_POST['dp_slideshow_effect_mobile'];

			// Transition time
			$options_visual['dp_slideshow_transition_time_mobile'] = mb_convert_kana($_POST['dp_slideshow_transition_time_mobile'],"n");
			if (!is_numeric($options_visual['dp_slideshow_transition_time_mobile'])) $options_visual['dp_slideshow_transition_time_mobile'] = $def_visual['dp_slideshow_transition_time_mobile'];

			// Time for each slide
			$options_visual['dp_slideshow_speed_mobile'] = mb_convert_kana($_POST['dp_slideshow_speed_mobile'],"n");
			if (!is_numeric($options_visual['dp_slideshow_speed_mobile'])) {
				$options_visual['dp_slideshow_speed_mobile'] = $def_visual['dp_slideshow_speed_mobile'];
			}


			$options_visual['fullscreen_video_url'] = $_POST['fullscreen_video_url'];
			$options_visual['fullscreen_video_start'] = mb_convert_kana($_POST['fullscreen_video_start'],"n");
			if (!is_numeric($options_visual['fullscreen_video_start'])) $options_visual['fullscreen_video_start'] = $def_visual['fullscreen_video_start'];

			//Header image
			$options_visual['dp_header_img']	= $_POST['dp_header_img'];

			//method of header image display
			$options_visual['dp_header_repeat']	= $_POST['dp_header_repeat'];
		
			//background color
			$options_visual['site_bg_color']	= ($_POST['site_bg_color'] && $_POST['site_bg_color'] !== '#') ? $_POST['site_bg_color'] : $def_visual['site_bg_color'];

			//backgroud color of container
			$options_visual['container_bg_color']	= ($_POST['container_bg_color'] && $_POST['container_bg_color'] !== '#') ? $_POST['container_bg_color'] : $def_visual['container_bg_color'];			

			//H1 title type
			$options_visual['h1title_as_what']	= $_POST['h1title_as_what'];

			//H1 title image
			$options_visual['dp_title_img']	= isset($_POST['dp_title_img']) ? $_POST['dp_title_img'] : '';
			$options_visual['dp_title_img_paged']	= isset($_POST['dp_title_img_paged']) ? $_POST['dp_title_img_paged'] : '';
			$options_visual['dp_title_img_mobile']	= isset($_POST['dp_title_img_mobile']) ? $_POST['dp_title_img_mobile'] : '';

			//font color
			$options_visual['base_font_color']	= ($_POST['base_font_color'] && $_POST['base_font_color'] !== '#') ? $_POST['base_font_color'] : $def_visual['base_font_color'];

			//font size
			$options_visual['base_font_size'] = mb_convert_kana($_POST['base_font_size'],"n");
			if (!is_numeric($options_visual['base_font_size'])) $options_visual['base_font_size'] = $def_visual['base_font_size'];

			//font size unit
			$options_visual['base_font_size_unit']	= $_POST['base_font_size_unit'];

			//font size mobile
			$options_visual['base_font_size_mb'] = mb_convert_kana($_POST['base_font_size_mb'],"n");
			if (!is_numeric($options_visual['base_font_size_mb'])) $options_visual['base_font_size_mb'] = $def_visual['base_font_size_mb'];

			//font size unit mobile
			$options_visual['base_font_size_mb_unit']	= $_POST['base_font_size_mb_unit'];

			//anchor style
			$options_visual['base_link_underline']	= $_POST['base_link_underline'];
			$options_visual['base_link_bold']	= isset($_POST['base_link_bold']) ? $_POST['base_link_bold'] : '';

			//anchor text color
			$options_visual['base_link_color']	= ($_POST['base_link_color'] && $_POST['base_link_color'] !== '#') ? $_POST['base_link_color'] : $def_visual['base_link_color'];

			//anchor hover text color
			$options_visual['base_link_hover_color']	= ($_POST['base_link_hover_color'] && $_POST['base_link_hover_color'] !== '#') ? $_POST['base_link_hover_color'] : $def_visual['base_link_hover_color'];

			//footer column number
			$options_visual['footer_col_number']	= $_POST['footer_col_number'];

			//footer text color
			$options_visual['footer_text_color']	= ($_POST['footer_text_color'] && $_POST['footer_text_color'] !== '#') ? $_POST['footer_text_color'] : $def_visual['footer_text_color'];

			//footer link color
			$options_visual['footer_link_color']	= ($_POST['footer_link_color'] && $_POST['footer_link_color'] !== '#') ? $_POST['footer_link_color'] : $def_visual['footer_link_color'];

			//footer link hover color
			$options_visual['footer_link_hover_color']	= ($_POST['footer_link_hover_color'] && $_POST['footer_link_hover_color'] !== '#') ? $_POST['footer_link_hover_color'] : $def_visual['footer_link_hover_color'];

			// background color of footer
			$options_visual['footer_bgcolor']	= ($_POST['footer_bgcolor'] && $_POST['footer_bgcolor'] !== '#') ? $_POST['footer_bgcolor'] : $def_visual['footer_bgcolor'];

			//Background image
			$options_visual['dp_background_img']	= $_POST['dp_background_img'];

			//method of background image display
			$options_visual['dp_background_repeat']	= $_POST['dp_background_repeat'];

			// Noto Sans CJK JP
			$options_visual['base_font_family']	= $_POST['base_font_family'];
			$options_visual['base_font_weight']	= $_POST['base_font_weight'];

			if (isset($_POST['full_wide_container_widget_area_top'])) {
				$options_visual['full_wide_container_widget_area_top']	= (bool)true;
			}  else {
				$options_visual['full_wide_container_widget_area_top']	= (bool)false;
			}
			if (isset($_POST['full_wide_container_widget_area_bottom'])) {
				$options_visual['full_wide_container_widget_area_bottom']	= (bool)true;
			}  else {
				$options_visual['full_wide_container_widget_area_bottom']	= (bool)false;
			}

			//Original CSS
			$options_visual['original_css']	= stripslashes($_POST['original_css']);

			// Category color
			$options_visual['cat_colors'] = $_POST['cat_colors'];
			$options_visual['cat_ids'] = $_POST['cat_ids'];

			//Update
			update_option('dp_options_visual', $options_visual);
			// Update CSS
			if (!dp_css_create()) return;

			// Message
			$notice_msg = __('Successfully updated.','DigiPress');
			set_transient( 'dp-admin-option-notices', array($notice_msg), 10 );
			add_action( 'admin_notices', array('digipress_options', 'dp_show_admin_notice_message') );
		} else {
			//Default
			digipress_options::getOptions_visual();
		}
	}

	/* ==================================================
	* Save the theme settings for control
	* ==================================================
	* @param	nonoe
	* @return	array	$options
	*/
	//Get Options
	public static function getOptions() {
		//Global scope
		global $def_control;
		$options = get_option('dp_options');
		if (!is_array($options)) {
			//Set default
			$options = $def_control;
			//Update
			update_option('dp_options', $options);
		}
		return $options;
	}
	//Update control settings
	static function update() {
		if(isset($_POST['dp_save'])) {
			$options = digipress_options::getOptions();

			// Use compressed jQuery
			if (isset($_POST['use_google_jquery'])) {
				$options['use_google_jquery'] 	= (bool)true;
			} else {
				$options['use_google_jquery'] 	= (bool)false;
			}

			if (isset($_POST['disable_mobile_fast'])) {
				$options['disable_mobile_fast'] 	= (bool)true;
			} else {
				$options['disable_mobile_fast'] 	= (bool)false;
			}

			// Activate or deactivate AMP
			if (isset($_POST['use_amp']) && !empty($_POST['use_amp'])) {
				$options['use_amp'] = true;
				dp_amp_rewrite_activation();
			} else {
				$options['use_amp'] = false;
				dp_amp_rewrite_deactivation();
			}
			if (isset($_POST['always_redirect_to_amp']) && !empty($_POST['always_redirect_to_amp'])) {
				$options['always_redirect_to_amp'] = true;
				dp_amp_rewrite_activation();
			} else {
				$options['always_redirect_to_amp'] = false;
				dp_amp_rewrite_deactivation();
			}
			if (isset($_POST['disable_amp_archive']) && !empty($_POST['disable_amp_archive'])) {
				$options['disable_amp_archive'] 	= (bool)true;
				dp_amp_rewrite_activation();
			} else {
				$options['disable_amp_archive'] 	= (bool)false;
				dp_amp_rewrite_deactivation();
			}

			if (isset($_POST['disable_sns_share_count'])) {
				$options['disable_sns_share_count'] 	= true;
			} else {
				$options['disable_sns_share_count'] 	= false;
			}

			// AMP
			$options['ga_tracking_id'] = htmlspecialchars(stripslashes($_POST['ga_tracking_id']));
			$options['original_css_amp'] = stripslashes($_POST['original_css_amp']);
			$options['enable_amp_cpt'] = stripslashes($_POST['enable_amp_cpt']);
			$options['custom_under_body_content_amp'] = stripslashes($_POST['custom_under_body_content_amp']);
			$options['custom_head_content_amp'] = stripslashes($_POST['custom_head_content_amp']);
			$options['sidemenu_pos_amp'] = $_POST['sidemenu_pos_amp'];
			$options['comments_main_title_amp'] = htmlspecialchars(stripslashes($_POST['comments_main_title_amp']));
			$options['nav_text_nonamp'] = htmlspecialchars(stripslashes($_POST['nav_text_nonamp']));
			if (isset($_POST['no_track_admin_amp'])) {
				$options['no_track_admin_amp'] = true;
				dp_amp_rewrite_activation();
			} else {
				$options['no_track_admin_amp'] = false;
				dp_amp_rewrite_deactivation();
			}

			if (isset($_POST['fixed_header_bar'])) {
				$options['fixed_header_bar'] 	= (bool)true;
			} else {
				$options['fixed_header_bar'] 	= (bool)false;
			}
			if (isset($_POST['fixed_header_bar_mb'])) {
				$options['fixed_header_bar_mb'] 	= (bool)true;
			} else {
				$options['fixed_header_bar_mb'] 	= (bool)false;
			}

			if (isset($_POST['disable_wow_js'])) {
				$options['disable_wow_js'] 	= (bool)true;
			} else {
				$options['disable_wow_js'] 	= (bool)false;
			}
			if (isset($_POST['disable_wow_js_mb'])) {
				$options['disable_wow_js_mb'] 	= (bool)true;
			} else {
				$options['disable_wow_js_mb'] 	= (bool)false;
			}

			if (isset($_POST['disable_cat_slider'])) {
				$options['disable_cat_slider'] 	= (bool)true;
			} else {
				$options['disable_cat_slider'] 	= (bool)false;
			}

			$options['decoration_type'] = $_POST['decoration_type'];

			$options['gcs_id'] = $_POST['gcs_id'];
			$options['google_api_key'] = $_POST['google_api_key'];

			$options['ls_token'] = $_POST['ls_token'];
			$options['ls_mid'] = $_POST['ls_mid'];

			$options['phg_token'] = $_POST['phg_token'];

			$options['adsense_id'] = $_POST['adsense_id'];

			$options['fb_api_lang'] = $_POST['fb_api_lang'];
			$options['fb_app_id'] = $_POST['fb_app_id'];

			$options['blog_start_year'] = $_POST['blog_start_year'];

			$options['news_cpt_slug_id']	= $_POST['news_cpt_slug_id'];
			$options['news_cpt_name']	= $_POST['news_cpt_name'];

			$options['twitter_card_user_id'] = $_POST['twitter_card_user_id'];

			if (isset($_POST['enable_title_site_name'])) {
				$options['enable_title_site_name'] = (bool)true;
			} else {
				$options['enable_title_site_name'] = (bool)false;
			}

			if (isset($_POST['disable_auto_format'])) {
				$options['disable_auto_format'] = (bool)true;
			} else {
				$options['disable_auto_format'] = (bool)false;
			}

			if (isset($_POST['replace_p_to_br'])) {
				$options['replace_p_to_br'] = (bool)true;
			} else {
				$options['replace_p_to_br'] = (bool)false;
			}

			if (isset($_POST['execute_php_in_widget'])) {
				$options['execute_php_in_widget'] = (bool)true;
			} else {
				$options['execute_php_in_widget'] = (bool)false;
			}

			if (isset($_POST['disable_auto_ogp'])) {
				$options['disable_auto_ogp'] = (bool)true;
			} else {
				$options['disable_auto_ogp'] = (bool)false;
			}

			if (isset($_POST['disable_canonical'])) {
				$options['disable_canonical'] = (bool)true;
			} else {
				$options['disable_canonical'] = (bool)false;
			}

			if (isset($_POST['disable_oembed'])) {
				$options['disable_oembed'] = true;
			} else {
				$options['disable_oembed'] = false;
			}

			if (isset($_POST['disable_fix_post_slug'])) {
				$options['disable_fix_post_slug'] = (bool)true;
			} else {
				$options['disable_fix_post_slug'] = (bool)false;
			}

			if (isset($_POST['disable_emoji'])) {
				$options['disable_emoji'] = (bool)true;
			} else {
				$options['disable_emoji'] = (bool)false;
			}

			// Header image title
			$options['header_img_h2'] = htmlspecialchars(stripslashes($_POST['header_img_h2']));
			$options['header_img_h3'] = htmlspecialchars(stripslashes($_POST['header_img_h3']));

			$options['title_site_name_top'] = htmlspecialchars(stripslashes($_POST['title_site_name_top']));
			$options['title_site_name'] = htmlspecialchars(stripslashes($_POST['title_site_name']));
			$options['title_site_name_separate'] = htmlspecialchars($_POST['title_site_name_separate']);

			if (isset($_POST['disable_dns_prefetch'])) {
				$options['disable_dns_prefetch'] = (bool)true;
			} else {
				$options['disable_dns_prefetch'] = (bool)false;
			}
			if (isset($_POST['enable_meta_def_kw'])) {
				$options['enable_meta_def_kw'] = (bool)true;
			} else {
				$options['enable_meta_def_kw'] = (bool)false;
			}
			if (isset($_POST['disable_meta_keywords'])) {
				$options['disable_meta_keywords'] = (bool)true;
			} else {
				$options['disable_meta_keywords'] = (bool)false;
			}
			$options['meta_def_kw'] = stripslashes($_POST['meta_def_kw']);

			$options['meta_ogp_img_url'] = $_POST['meta_ogp_img_url'];

			if (isset($_POST['enable_meta_def_desc'])) {
				$options['enable_meta_def_desc'] = (bool)true;
			} else {
				$options['enable_meta_def_desc'] = (bool)false;
			}
			$options['meta_def_desc'] = stripslashes($_POST['meta_def_desc']);

			$options['custom_head_content'] = stripslashes($_POST['custom_head_content']);

			if (isset($_POST['enable_h1_title'])) {
				$options['enable_h1_title'] = (bool)true;
			} else {
				$options['enable_h1_title'] = (bool)false;
			}
			$options['h1_title'] = htmlspecialchars(stripslashes($_POST['h1_title']));

			if (isset($_POST['enable_h2_title'])) {
				$options['enable_h2_title'] = (bool)true;
			} else {
				$options['enable_h2_title'] = (bool)false;
			}
			$options['h2_title'] = htmlspecialchars(stripslashes($_POST['h2_title']));

			$options['mb_slide_menu_position'] = $_POST['mb_slide_menu_position'];
			$options['mb_slide_menu_zposition'] = $_POST['mb_slide_menu_zposition'];

			if (isset($_POST['show_global_menu_search'])) {
				$options['show_global_menu_search'] = (bool)true;
			} else {
				$options['show_global_menu_search'] = (bool)false;
			}

			if (isset($_POST['show_floating_gcs'])) {
				$options['show_floating_gcs'] = (bool)true;
			} else {
				$options['show_floating_gcs'] = (bool)false;
			}

			$options['global_menu_right_content'] = $_POST['global_menu_right_content'];

			if (isset($_POST['show_global_menu_sns'])) {
				$options['show_global_menu_sns'] = (bool)true;
			} else {
				$options['show_global_menu_sns'] = (bool)false;
			}
			if (isset($_POST['show_global_menu_rss'])) {
				$options['show_global_menu_rss'] = (bool)true;
			} else {
				$options['show_global_menu_rss'] = (bool)false;
			}
			if (isset($_POST['rss_to_feedly'])) {
				$options['rss_to_feedly'] = (bool)true;
			} else {
				$options['rss_to_feedly'] = (bool)false;
			}
			$options['global_menu_twitter_url'] = $_POST['global_menu_twitter_url'];
			$options['global_menu_fb_url'] = $_POST['global_menu_fb_url'];
			$options['global_menu_instagram_url']	= $_POST['global_menu_instagram_url'];
			$options['global_menu_youtube_url']= $_POST['global_menu_youtube_url'];
			$options['global_menu_right_tel']	= $_POST['global_menu_right_tel'];

			// Top under
			$options['show_specific_cat_index'] = $_POST['show_specific_cat_index'];
			if (ctype_digit($_POST['specific_cat_index'])) {
				$options['specific_cat_index'] = $_POST['specific_cat_index'];
			} else {
				$options['specific_cat_index'] = '';
			}
			$options['specific_post_type_index'] = isset($_POST['specific_post_type_index']) ? $_POST['specific_post_type_index'] : '';

			$options['specific_page_id'] = $_POST['specific_page_id'];

			// Digit Check -----------------------------------
			if (ctype_digit($_POST['number_posts_index'])) {
				$options['number_posts_index'] = $_POST['number_posts_index'];
			} else {
				$options['number_posts_index'] = '';
			}
			if (ctype_digit($_POST['number_posts_index_mobile'])) {
				$options['number_posts_index_mobile'] = $_POST['number_posts_index_mobile'];
			} else {
				$options['number_posts_index_mobile'] = '';
			}
			if (isset($_POST['number_posts_index_paged'])) {
				if (ctype_digit($_POST['number_posts_index_paged'])) {
					$options['number_posts_index_paged'] = $_POST['number_posts_index_paged'];
				} else {
					$options['number_posts_index_paged'] = '';
				}
			}
			if (ctype_digit($_POST['number_posts_category'])) {
				$options['number_posts_category'] = $_POST['number_posts_category'];
			} else {
				$options['number_posts_category'] = '';
			}
			if (ctype_digit($_POST['number_posts_tag'])) {
				$options['number_posts_tag'] = $_POST['number_posts_tag'];
			} else {
				$options['number_posts_tag'] = '';
			}
			if (ctype_digit($_POST['number_posts_search'])) {
				$options['number_posts_search'] = $_POST['number_posts_search'];
			} else {
				$options['number_posts_search'] = '';
			}
			if (ctype_digit($_POST['number_posts_date'])) {
				$options['number_posts_date'] = $_POST['number_posts_date'];
			} else {
				$options['number_posts_date'] = '';
			}
			if (ctype_digit($_POST['number_posts_author'])) {
				$options['number_posts_author'] = $_POST['number_posts_author'];
			} else {
				$options['number_posts_author'] = '';
			}

			if (ctype_digit($_POST['number_posts_category_mobile'])) {
				$options['number_posts_category_mobile'] = $_POST['number_posts_category_mobile'];
			} else {
				$options['number_posts_category_mobile'] = '';
			}
			if (ctype_digit($_POST['number_posts_tag_mobile'])) {
				$options['number_posts_tag_mobile'] = $_POST['number_posts_tag_mobile'];
			} else {
				$options['number_posts_tag_mobile'] = '';
			}
			if (ctype_digit($_POST['number_posts_search_mobile'])) {
				$options['number_posts_search_mobile'] = $_POST['number_posts_search_mobile'];
			} else {
				$options['number_posts_search_mobile'] = '';
			}
			if (ctype_digit($_POST['number_posts_date_mobile'])) {
				$options['number_posts_date_mobile'] = $_POST['number_posts_date_mobile'];
			} else {
				$options['number_posts_date_mobile'] = '';
			}
			if (ctype_digit($_POST['number_posts_author_mobile'])) {
				$options['number_posts_author_mobile'] = $_POST['number_posts_author_mobile'];
			} else {
				$options['number_posts_author_mobile'] = '';
			}
			//-----------------------------------------------

			if (isset($_POST['show_top_content'])) {
				$options['show_top_content'] = (bool)true;
			} else {
				$options['show_top_content'] = (bool)false;
			}

			$options['new_post_label'] = htmlspecialchars(stripslashes($_POST['new_post_label']));
			$options['new_post_to_archive_label'] = htmlspecialchars(stripslashes($_POST['new_post_to_archive_label']));
			

			$options['new_post_count'] = $_POST['new_post_count'];
			

			if (isset($_POST['time_for_reading'])) {
				$options['time_for_reading'] = (bool)true;
			} else {
				$options['time_for_reading'] = (bool)false;
			}

			if (isset($_POST['show_top_under_content'])) {
				$options['show_top_under_content'] = (bool)true;
			} else {
				$options['show_top_under_content'] = (bool)false;
			}

			$options['top_post_show_type'] = $_POST['top_post_show_type'];
			$options['top_rev_thumb_odd_even'] = $_POST['top_rev_thumb_odd_even'];

			$options['top_excerpt_type'] = $_POST['top_excerpt_type'];

			$options['top_posts_list_title'] = htmlspecialchars(stripslashes($_POST['top_posts_list_title']));

			$options['top_category_orderby'] = $_POST['top_category_orderby'];

			$options['top_exclude_categories'] = stripslashes($_POST['top_exclude_categories']);

			if (isset($_POST['top_category_show_post_count'])) {
				$options['top_category_show_post_count'] = (bool)true;
			} else {
				$options['top_category_show_post_count'] = (bool)false;
			}

			$options['navigation_text_to_2page_top'] = $_POST['navigation_text_to_2page_top'];
			$options['navigation_text_to_2page_archive'] = $_POST['navigation_text_to_2page_archive'];

			$options['top_masonry_lines'] = $_POST['top_masonry_lines'];
			

			if (isset($_POST['top_archive_list_author'])) {
				$options['top_archive_list_author'] = (bool)true;
			} else {
				$options['top_archive_list_author'] = (bool)false;
			}

			if (isset($_POST['top_archive_list_date'])) {
				$options['top_archive_list_date'] = (bool)true;
			} else {
				$options['top_archive_list_date'] = (bool)false;
			}

			if (isset($_POST['top_archive_list_views'])) {
				$options['top_archive_list_views'] = (bool)true;
			} else {
				$options['top_archive_list_views'] = (bool)false;
			}

			if (isset($_POST['top_archive_list_cat'])) {
				$options['top_archive_list_cat'] = (bool)true;
			} else {
				$options['top_archive_list_cat'] = (bool)false;
			}

			if (isset($_POST['show_archive_title'])) {
				$options['show_archive_title'] = (bool)true;
			} else {
				$options['show_archive_title'] = (bool)false;
			}

			$options['archive_masonry_lines'] = $_POST['archive_masonry_lines'];

			if (isset($_POST['archive_archive_list_author'])) {
				$options['archive_archive_list_author'] = (bool)true;
			} else {
				$options['archive_archive_list_author'] = (bool)false;
			}

			if (isset($_POST['archive_archive_list_date'])) {
				$options['archive_archive_list_date'] = (bool)true;
			} else {
				$options['archive_archive_list_date'] = (bool)false;
			}

			if (isset($_POST['archive_archive_list_views'])) {
				$options['archive_archive_list_views'] = (bool)true;
			} else {
				$options['archive_archive_list_views'] = (bool)false;
			}

			if (isset($_POST['archive_archive_list_cat'])) {
				$options['archive_archive_list_cat'] = (bool)true;
			} else {
				$options['archive_archive_list_cat'] = (bool)false;
			}
			if (isset($_POST['hatebu_number_after_title_top'])) {
				$options['hatebu_number_after_title_top'] = (bool)true;
			} else {
				$options['hatebu_number_after_title_top'] = (bool)false;
			}
			if (isset($_POST['hatebu_number_after_title_archive'])) {
				$options['hatebu_number_after_title_archive'] = (bool)true;
			} else {
				$options['hatebu_number_after_title_archive'] = (bool)false;
			}
			if (isset($_POST['likes_number_after_title_top'])) {
				$options['likes_number_after_title_top'] = (bool)true;
			} else {
				$options['likes_number_after_title_top'] = (bool)false;
			}
			if (isset($_POST['likes_number_after_title_archive'])) {
				$options['likes_number_after_title_archive'] = (bool)true;
			} else {
				$options['likes_number_after_title_archive'] = (bool)false;
			}
			if (isset($_POST['tweets_number_after_title_top'])) {
				$options['tweets_number_after_title_top'] = true;
			} else {
				$options['tweets_number_after_title_top'] = false;
			}
			if (isset($_POST['tweets_number_after_title_archive'])) {
				$options['tweets_number_after_title_archive'] = true;
			} else {
				$options['tweets_number_after_title_archive'] = false;
			}

			$options['archive_post_show_type'] = $_POST['archive_post_show_type'];
			$options['archive_rev_thumb_odd_even'] = $_POST['archive_rev_thumb_odd_even'];

			$options['archive_excerpt_type'] = $_POST['archive_excerpt_type'];

			$options['author_info_title'] = htmlspecialchars(stripslashes($_POST['author_info_title']));
			$options['author_recent_articles_title'] = htmlspecialchars(stripslashes($_POST['author_recent_articles_title']));
			$options['comments_main_title'] = htmlspecialchars(stripslashes($_POST['comments_main_title']));
			$options['comment_form_title'] = htmlspecialchars(stripslashes($_POST['comment_form_title']));
			$options['fb_comments_title'] = htmlspecialchars(stripslashes($_POST['fb_comments_title']));

			$options['top_readmore_str'] = htmlspecialchars(stripslashes($_POST['top_readmore_str']));
			$options['archive_readmore_str'] = htmlspecialchars(stripslashes($_POST['archive_readmore_str']));

			if (isset($_POST['show_pubdate_on_meta'])) {
				$options['show_pubdate_on_meta'] = (bool)true;
			} else {
				$options['show_pubdate_on_meta'] = (bool)false;
			}

			if (isset($_POST['show_pubdate_on_meta_page'])) {
				$options['show_pubdate_on_meta_page'] = (bool)true;
			} else {
				$options['show_pubdate_on_meta_page'] = (bool)false;
			}

			if (isset($_POST['show_date_under_post_title'])) {
				$options['show_date_under_post_title'] = (bool)true;
			} else {
				$options['show_date_under_post_title'] = (bool)false;
			}

			if (isset($_POST['show_date_on_post_meta'])) {
				$options['show_date_on_post_meta'] = (bool)true;
			} else {
				$options['show_date_on_post_meta'] = (bool)false;
			}

			if (isset($_POST['show_author_on_meta'])) {
				$options['show_author_on_meta'] = (bool)true;
			} else {
				$options['show_author_on_meta'] = (bool)false;
			}

			if (isset($_POST['show_author_on_meta_page'])) {
				$options['show_author_on_meta_page'] = (bool)true;
			} else {
				$options['show_author_on_meta_page'] = (bool)false;
			}

			if (isset($_POST['show_author_under_post_title'])) {
				$options['show_author_under_post_title'] = (bool)true;
			} else {
				$options['show_author_under_post_title'] = (bool)false;
			}

			if (isset($_POST['show_author_on_post_meta'])) {
				$options['show_author_on_post_meta'] = (bool)true;
			} else {
				$options['show_author_on_post_meta'] = (bool)false;
			}

			if (isset($_POST['show_views_on_meta'])) {
				$options['show_views_on_meta'] = (bool)true;
			} else {
				$options['show_views_on_meta'] = (bool)false;
			}

			if (isset($_POST['show_views_under_post_title'])) {
				$options['show_views_under_post_title'] = (bool)true;
			} else {
				$options['show_views_under_post_title'] = (bool)false;
			}

			if (isset($_POST['show_views_on_post_meta'])) {
				$options['show_views_on_post_meta'] = (bool)true;
			} else {
				$options['show_views_on_post_meta'] = (bool)false;
			}

			if (isset($_POST['show_cat_on_meta'])) {
				$options['show_cat_on_meta'] = (bool)true;
			} else {
				$options['show_cat_on_meta'] = (bool)false;
			}

			if (isset($_POST['show_cat_under_post_title'])) {
				$options['show_cat_under_post_title'] = (bool)true;
			} else {
				$options['show_cat_under_post_title'] = (bool)false;
			}

			if (isset($_POST['show_cat_on_post_meta'])) {
				$options['show_cat_on_post_meta'] = (bool)true;
			} else {
				$options['show_cat_on_post_meta'] = (bool)false;
			}

			if (isset($_POST['show_tags'])) {
				$options['show_tags'] = (bool)true;
			} else {
				$options['show_tags'] = (bool)false;
			}

			if (isset($_POST['sns_button_under_title'])) {
				$options['sns_button_under_title'] = (bool)true;
			} else {
				$options['sns_button_under_title'] = (bool)false;
			}

			if (isset($_POST['sns_button_on_meta'])) {
				$options['sns_button_on_meta'] = (bool)true;
			} else {
				$options['sns_button_on_meta'] = (bool)false;
			}

			if (isset($_POST['show_twitter_button'])) {
				$options['show_twitter_button'] = (bool)true;
			} else {
				$options['show_twitter_button'] = (bool)false;
			}

			if (isset($_POST['show_facebook_button'])) {
				$options['show_facebook_button'] = (bool)true;
			} else {
				$options['show_facebook_button'] = (bool)false;
			}

			if (isset($_POST['show_facebook_button_w_share'])) {
				$options['show_facebook_button_w_share'] = (bool)true;
			} else {
				$options['show_facebook_button_w_share'] = (bool)false;
			}

			if (isset($_POST['show_pocket_button'])) {
				$options['show_pocket_button'] = (bool)true;
			} else {
				$options['show_pocket_button'] = (bool)false;
			}

			if (isset($_POST['show_pinterest_button'])) {
				$options['show_pinterest_button'] = (bool)true;
			} else {
				$options['show_pinterest_button'] = (bool)false;
			}

			if (isset($_POST['show_mixi_button'])) {
				$options['show_mixi_button'] = (bool)true;
			} else {
				$options['show_mixi_button'] = (bool)false;
			}

			$options['mixi_accept_key'] = $_POST['mixi_accept_key'];

			if (isset($_POST['show_hatena_button'])) {
				$options['show_hatena_button'] = (bool)true;
			} else {
				$options['show_hatena_button'] = (bool)false;
			}

			if (isset($_POST['show_tumblr_button'])) {
				$options['show_tumblr_button'] = (bool)true;
			} else {
				$options['show_tumblr_button'] = (bool)false;
			}

			if (isset($_POST['show_line_button'])) {
				$options['show_line_button'] = (bool)true;
			} else {
				$options['show_line_button'] = (bool)false;
			}

			if (isset($_POST['show_evernote_button'])) {
				$options['show_evernote_button'] = (bool)true;
			} else {
				$options['show_evernote_button'] = (bool)false;
			}

			

			if (isset($_POST['show_feedly_button'])) {
				$options['show_feedly_button'] = (bool)true;
			} else {
				$options['show_feedly_button'] = (bool)false;
			}

			if (isset($_POST['hide_author_info'])) {
				$options['hide_author_info'] = (bool)true;
			} else {
				$options['hide_author_info'] = (bool)false;
			}
			$options['author_info_title'] = htmlspecialchars(stripslashes($_POST['author_info_title']));
			$options['author_recent_articles_title'] = !empty($_POST['author_recent_articles_title']) ? htmlspecialchars(stripslashes($_POST['author_recent_articles_title'])) : 'Recent Posted';

			$options['exclude_pages'] = isset($_POST['exclude_pages']) ? stripslashes($_POST['exclude_pages']) : '';

			$options['tracking_code'] = stripslashes($_POST['tracking_code']);

			if (isset($_POST['no_track_admin'])) {
				$options['no_track_admin'] = (bool)true;
			} else {
				$options['no_track_admin'] = (bool)false;
			}

			if (isset($_POST['facebookcomment'])) {
				$options['facebookcomment'] = (bool)true;
			} else {
				$options['facebookcomment'] = (bool)false;
			}

			if (isset($_POST['facebookcomment_page'])) {
				$options['facebookcomment_page'] = (bool)true;
			} else {
				$options['facebookcomment_page'] = (bool)false;
			}

			if (isset($_POST['show_eyecatch_first'])) {
				$options['show_eyecatch_first'] = (bool)true;
			} else {
				$options['show_eyecatch_first'] = (bool)false;
			}

			$options['related_posts_target'] = $_POST['related_posts_target'];

			$options['number_fb_comment'] = mb_convert_kana($_POST['number_fb_comment'],"n");
			if (!is_numeric($options['number_fb_comment'])) $options['number_fb_comment'] = '10';
			


			if (isset($_POST['show_related_posts'])) {
				$options['show_related_posts'] = (bool)true;
			} else {
				$options['show_related_posts'] = (bool)false;
			}

			$options['related_posts_title'] = $_POST['related_posts_title'];

			$options['related_posts_style'] = $_POST['related_posts_style'];

			$options['number_related_posts'] = $_POST['number_related_posts'];



			if (isset($_POST['related_posts_thumbnail'])) {
				$options['related_posts_thumbnail'] = (bool)true;
			} else {
				$options['related_posts_thumbnail'] = (bool)false;
			}

			if (isset($_POST['related_posts_category'])) {
				$options['related_posts_category'] = (bool)true;
			} else {
				$options['related_posts_category'] = (bool)false;
			}

			if (isset($_POST['date_reckon_mode'])) {
				$options['date_reckon_mode'] = (bool)true;
			} else {
				$options['date_reckon_mode'] = (bool)false;
			}

			if (isset($_POST['show_last_update'])) {
				$options['show_last_update'] = (bool)true;
			} else {
				$options['show_last_update'] = (bool)false;
			}

			if (isset($_POST['pagenation'])) {
				$options['pagenation'] = (bool)true;
			} else {
				$options['pagenation'] = (bool)false;
			}


			if (isset($_POST['next_prev_in_same_cat'])) {
				$options['next_prev_in_same_cat'] = (bool)true;
			} else {
				$options['next_prev_in_same_cat'] = (bool)false;
			}

			if (isset($_POST['autopager'])) {
				$options['autopager'] = (bool)true;
			} else {
				$options['autopager'] = (bool)false;
			}

			if (isset($_POST['autopager_mb'])) {
				$options['autopager_mb'] = (bool)true;
			} else {
				$options['autopager_mb'] = (bool)false;
			}

			if (isset($_POST['pagenation_always_show'])) {
				$options['pagenation_always_show'] = (bool)true;
			} else {
				$options['pagenation_always_show'] = (bool)false;
			}

			if (isset($_POST['show_author_avatar'])) {
				$options['show_author_avatar'] = (bool)true;
			} else {
				$options['show_author_avatar'] = (bool)false;
			}
			$options['sns_button_type'] = $_POST['sns_button_type'];

			if (isset($_POST['index_top_except_cat'])) {
				$options['index_top_except_cat'] = (bool)true;
			} else {
				$options['index_top_except_cat'] = (bool)false;
			}
			$options['index_top_except_cat_id'] = mb_convert_kana($_POST['index_top_except_cat_id'],"n");

			if (isset($_POST['index_bottom_except_cat'])) {
				$options['index_bottom_except_cat'] = (bool)true;
			} else {
				$options['index_bottom_except_cat'] = (bool)false;
			}
			$options['index_bottom_except_cat_id'] = mb_convert_kana($_POST['index_bottom_except_cat_id'],"n");

			$options['show_type_cat_normal'] = mb_convert_kana($_POST['show_type_cat_normal'],"n");
			$options['show_type_cat_blog'] = mb_convert_kana($_POST['show_type_cat_blog'],"n");
			$options['show_type_cat_portfolio1'] = mb_convert_kana($_POST['show_type_cat_portfolio1'],"n");
			$options['show_type_cat_magazine1'] = mb_convert_kana($_POST['show_type_cat_magazine1'],"n");
			$options['show_type_cat_magazine2'] = mb_convert_kana($_POST['show_type_cat_magazine2'],"n");

			$options['one_col_category'] = mb_convert_kana($_POST['one_col_category'],"n");

			$options['top_normal_excerpt_length']	= $_POST['top_normal_excerpt_length'];
			$options['top_magazine_excerpt_length']	= $_POST['top_magazine_excerpt_length'];
			$options['archive_normal_excerpt_length']	= $_POST['archive_normal_excerpt_length'];
			$options['archive_magazine_excerpt_length']	= $_POST['archive_magazine_excerpt_length'];

			$options['top_infeed_ads_code'] = stripslashes($_POST['top_infeed_ads_code']);
			$options['top_infeed_ads_code_mb'] = stripslashes($_POST['top_infeed_ads_code_mb']);
			$options['top_infeed_ads_order'] = mb_convert_kana($_POST['top_infeed_ads_order']);
			$options['top_infeed_ads_order'] = preg_replace("/( |　)/", "", $options['top_infeed_ads_order']);
			$options['top_infeed_ads_order_mb'] = mb_convert_kana($_POST['top_infeed_ads_order_mb']);
			$options['top_infeed_ads_order_mb'] = preg_replace("/( |　)/", "", $options['top_infeed_ads_order_mb']);

			$options['archive_infeed_ads_code'] = stripslashes($_POST['archive_infeed_ads_code']);
			$options['archive_infeed_ads_code_mb'] = stripslashes($_POST['archive_infeed_ads_code_mb']);
			$options['archive_infeed_ads_order'] = mb_convert_kana($_POST['archive_infeed_ads_order']);
			$options['archive_infeed_ads_order'] = preg_replace("/( |　)/", "", $options['archive_infeed_ads_order']);
			$options['archive_infeed_ads_order_mb'] = mb_convert_kana($_POST['archive_infeed_ads_order_mb']);
			$options['archive_infeed_ads_order_mb'] = preg_replace("/( |　)/", "", $options['archive_infeed_ads_order_mb']);

			update_option('dp_options', $options);
			if (!dp_css_create()) return;

			// Message
			$notice_msg = __('Successfully updated.','DigiPress');
			set_transient( 'dp-admin-option-notices', array($notice_msg), 10 );
			add_action( 'admin_notices', array('digipress_options', 'dp_show_admin_notice_message') );

		} else {
			//default
			digipress_options::getOptions();
		}
	}

	/* ==================================================
	* Backup / restore
	* =================================================*/
	// Backup all settings
	static function dp_export_all_settings() {
		if (isset($_POST['dp_export']) && check_admin_referer('dp-export')) { 
			global $def_control, $def_visual;
			$date = date("YmdHis");
			$json_name = DP_THEME_KEY."-".$date;
			// Get all options
			$op1 = get_option('dp_options');
			$op2 = get_option('dp_options_visual');
			$op1 = !empty($op1) ? $op1 : $def_control;
			$op2 = !empty($op2) ? $op2 : $def_visual;
			$all_options = array('dp_options' => $op1,
							 'dp_options_visual' => $op2
							 );
			foreach ($all_options as $key => $value) {
				$value = maybe_unserialize($value);
				$need_options[$key] = $value;
			}
			// Encode data into json data
			$json_file = json_encode($need_options);
			// Clear buffer
			ob_clean();
			header("Content-Type: text/json; charset=" . get_option('blog_charset'));
			header("Content-Disposition: attachment; filename=$json_name.json");
			echo $json_file;
			exit();
		}
	}
	// Resotre
	static function dp_import_all_settings() {
		if (isset($_FILES['dp_import']) && check_admin_referer('dp-import')) {
			if ($_FILES['dp_import']['error'] > 0) {
				wp_die(__("Import Error : ", "DigiPress").$_FILES['dp_import']['error']);
			} else {
				$file_name = $_FILES['dp_import']['name']; // Get the name of file
				$tmp_exp = explode(".", $file_name);
				$file_ext = strtolower(end($tmp_exp)); // Get extension of file
				$file_size = $_FILES['dp_import']['size']; // Get size of file
				/* Ensure uploaded file is JSON file type and the size not over 1000000 bytes
				* You can modify the size you want
				*/
				if ( $file_ext === "json" ) {
					if ( WP_Filesystem() ) {
						global $wp_filesystem;
						$encode_options = $wp_filesystem->get_contents($_FILES['dp_import']['tmp_name']);
						$all_options = json_decode($encode_options, true);
						if (is_array($all_options)) {
							foreach ($all_options as $key => $value) {
								update_option($key, $value);
							}
							// Export CSS
							dp_css_create();
							// Message
							$notice_msg = __('All options are restored successfully.','DigiPress');
							set_transient( 'dp-admin-option-notices', array($notice_msg), 10 );
							add_action( 'admin_notices', array('digipress_options', 'dp_show_admin_notice_message') );
						} else {
							set_transient( 'dp-admin-option-errors',__('Backup file is incorrect format.', 'DigiPress'), 10 );
							// Show error
							add_action( 'admin_notices', array('digipress_options', 'dp_show_admin_error_message') );
						}
					}
				} else {
					// Message
					$err_msg = __('Invalid file or file size too big.','DigiPress');
					$e = new WP_Error();
					$e->add( 'error', $err_msg );
					set_transient( 'dp-admin-option-errors',$e->get_error_messages(), 10 );
					// Show error
					add_action( 'admin_notices', array('digipress_options', 'dp_show_admin_error_message') );
				}
			}
		}
	}

	/* ==================================================
	* Upload files
	* =================================================*/
	static function dp_run_upload_file() {
		if ( !isset($_POST['target_dir']) ) return;

		$target_dir = $_POST['target_dir'];
		if( !is_writable( $target_dir ) ){
			// Set error to transient
			$e = new WP_Error();
			$e->add( 
				'error', 
				$target_dir . __( 'is not writable. Please change the permission to 777.', 'DigiPress' ) 
				);
			// life time = 10 sec
			set_transient( 'dp-admin-option-errors',
				$e->get_error_messages(), 10 );
			// Show error
			add_action( 'admin_notices', array('digipress_options', 'dp_show_admin_error_message') );
			return;
		}

		$target_img = '';
		$upload_file = '';

		$max_file_size = isset($_POST['max_file_size']) ? $_POST['max_file_size'] : 0;

		//Preg Match Pattern
		$strPattern	= '/(\.gif|\.jpg|\.jpeg|\.png|\.svg)$/';

		// Check target
		if( isset($_POST['dp_upload_file_title_img']) ) {
			$target_img = 'dp_title_img';
		} else if( isset($_POST['dp_upload_file_hd']) ) {
			$target_img = 'dp_header_img';
		} else if( isset($_POST['dp_upload_file_hd_mobile']) ) {
			$target_img = 'dp_header_img_mobile';
		} else if( isset($_POST['dp_upload_file_bg']) ) {
			$target_img = 'dp_background_img';
		}

		// Upload
		if ( is_uploaded_file($_FILES[$target_img]["tmp_name"]) ) {
			//If not support format
			if ( ! preg_match($strPattern, $_FILES[$target_img]["name"]) ) {
				$err_msg = $_FILES[$target_img]["name"] .  __(' is unsupported file format. jpg(jpeg), png and gif are supported.', 'DigiPress');
				$e = new WP_Error();
				$e->add( 'error', $err_msg );
				// life time = 10 sec
				set_transient( 'dp-admin-option-errors',
					$e->get_error_messages(), 10 );
				// Show error
				add_action( 'admin_notices', array('digipress_options', 'dp_show_admin_error_message') );

			} elseif ( preg_match($strPattern, $_FILES[$target_img]["size"]) > $max_file_size ) {
				$err_msg = __('Max uploadable size is ', 'DigiPress') . $max_file_size / 1000 . __('KB.', 'DigiPress');
				$e = new WP_Error();
				$e->add( 'error', $err_msg );
				// life time = 10 sec
				set_transient( 'dp-admin-option-errors',
					$e->get_error_messages(), 10 );
				// Show error
				add_action( 'admin_notices', array('digipress_options', 'dp_show_admin_error_message') );
			
			} else {
				$upload_file = $target_dir."/".$_FILES[$target_img]["name"];
				//Upload
				if ( move_uploaded_file($_FILES[$target_img]["tmp_name"], $upload_file) ) {
					//change mode
					chmod($upload_file, 0644);
					// Message
					$notice_msg = $_FILES[$target_img]["name"] . __(' was uploaded.', 'DigiPress');
					// life time = 10 sec
					set_transient( 'dp-admin-option-notices', array($notice_msg), 10 );
					// Show error
					add_action( 'admin_notices', array('digipress_options', 'dp_show_admin_notice_message') );

				} else {
					$err_msg = __('The file couldn\'t be uploaded.' , 'DigiPress');
					$e = new WP_Error();
					$e->add( 'error', $err_msg );
					// life time = 10 sec
					set_transient( 'dp-admin-option-errors',
						$e->get_error_messages(), 10 );
					// Show error
					add_action( 'admin_notices', array('digipress_options', 'dp_show_admin_error_message') );
				}
			}
		} else {
			$err_msg = __('No file selected. Or exceeded max uploadable file size. Max uploadable size is ', 'DigiPress') . $max_file_size / 1000 . __('KB.', 'DigiPress');
			$e = new WP_Error();
			$e->add( 'error', $err_msg );
			// life time = 10 sec
			set_transient( 'dp-admin-option-errors',
				$e->get_error_messages(), 10 );
			// Show error
			add_action( 'admin_notices', array('digipress_options', 'dp_show_admin_error_message') );
		}
	}
	
	/* ==================================================
	* Delete upload files
	* =================================================*/
	static function dp_delete_upload_file() {
		$target_img = '';
		//delete title image
		if( isset($_POST['dp_delete_file_title_img']) ) {
			$target_img = 'dp_title_img';
		} else if( isset($_POST['dp_delete_file_hd']) ) {
			$target_img = 'dp_header_img';
		} else if( isset($_POST['dp_delete_file_hd_mobile']) ) {
			$target_img = 'dp_header_img_mobile';
		} else if( isset($_POST['dp_delete_file_bg']) ) {
			$target_img = 'dp_background_img';
		}

		//Delete
		if ( !empty($target_img) ) {
			if ( ($_POST[$target_img] === "") || (is_null($_POST[$target_img])) ) {
				$err_msg = __('Target file does not found.','DigiPress');
				$e = new WP_Error();
				$e->add( 'error', $err_msg );
				set_transient( 'dp-admin-option-errors',
					$e->get_error_messages(), 10 );
				add_action( 'admin_notices', array('digipress_options', 'dp_show_admin_error_message') );

			} else {
				$filename = $_POST[$target_img];
				if ( file_exists($filename) ) {
					if ( ! unlink($filename) ) {
						$err_msg = __('Failed to delete a file.','DigiPress');
						$e = new WP_Error();
						$e->add( 'error', $err_msg );
						set_transient( 'dp-admin-option-errors',
							$e->get_error_messages(), 10 );
						add_action( 'admin_notices', array('digipress_options', 'dp_show_admin_error_message') );

					} else {
						$notice_msg = __('Successfully deleted.','DigiPress');
						set_transient( 'dp-admin-option-notices', array($notice_msg), 10 );
						add_action( 'admin_notices', array('digipress_options', 'dp_show_admin_notice_message') );
					}
				} else {
					$err_msg = __('Target file does not found.','DigiPress');
					$e = new WP_Error();
					$e->add( 'error', $err_msg );
					set_transient( 'dp-admin-option-errors',
						$e->get_error_messages(), 10 );
					add_action( 'admin_notices', array('digipress_options', 'dp_show_admin_error_message') );
				}
			}
		}
	}

	/* ==================================================
	* Edit Images
	* =================================================*/
	static function edit_images() {
		if(isset($_POST['dp_edit_images'])) {
			$notice_msg = __('Successfully reseted parameters.','DigiPress');
			set_transient( 'dp-admin-option-notices', array($notice_msg), 10 );
			add_action( 'admin_notices', array('digipress_options', 'dp_show_admin_notice_message') );
		}
	}
	
	/* ==================================================
	* Reset all settings
	* =================================================*/
	static function reset_theme_options() {
		//Reset visual settings
		if(isset($_POST['dp_reset_visual'])) {
			global $def_visual;

			//Reset
			update_option('dp_options_visual', $def_visual);

			//Rewrite Style.css
			if (!dp_css_create()) return;
			$notice_msg = __('Successfully reseted parameters.','DigiPress');
			set_transient( 'dp-admin-option-notices', array($notice_msg), 10 );
			add_action( 'admin_notices', array('digipress_options', 'dp_show_admin_notice_message') );
		}
		//Reset control settings
		if(isset($_POST['dp_reset_control'])) {
			global $def_control;

			//Reset
			update_option('dp_options', $def_control);
			//Rewrite Style.css
			if (!dp_css_create()) return;
			$notice_msg = __('Successfully reseted parameters.','DigiPress');
			set_transient( 'dp-admin-option-notices', array($notice_msg), 10 );
			add_action( 'admin_notices', array('digipress_options', 'dp_show_admin_notice_message') );
		}
	}

	/****************************************************************
	* Show visual option interface
	****************************************************************/
	/** ===================================================
	* Include And Display Theme Option Panel.
	*/
	static function display_visual_options() {
		$options = digipress_options::getOptions_visual();
		include_once(DP_THEME_DIR . "/inc/admin/visual.php");
	}
	/** ===================================================
	* Include And Display Theme Option Panel.
	*/
	static function display_theme_custom() {
		$options = digipress_options::getOptions();
		include_once(DP_THEME_DIR . "/inc/admin/control.php");
	}
	/** ===================================================
	* Include And Display Theme Option Panel.
	*/
	static function display_delete_images() {
		$options = digipress_options::getOptions_visual();
		include_once(DP_THEME_DIR . "/inc/admin/delete_file.php");
	}
	/** ===================================================
	* Include And Display Theme Option Panel.
	*/
	static function display_edit_images() {
		$options = digipress_options::getOptions_visual();
		include_once(DP_THEME_DIR . "/inc/admin/edit_img.php");
	}
	/** ===================================================
	* Add-ons panel
	*/
	static function display_add_ons() {
		include_once(DP_THEME_DIR . "/inc/admin/add_ons.php");
	}

	
	/****************************************************************
	*  Insert CSS and javascript to header of DigiPress option panel.
	****************************************************************/
	static function enqueue_css_js() {
		if (!is_admin()) return;
		//CSS
		wp_enqueue_style('dp-admin-css', DP_THEME_URI . '/inc/css/dp-admin.css', array(), DP_OPTION_SPT_VERSION);
		wp_enqueue_style('jquery-ui-css', 'https://ajax.googleapis.com/ajax/libs/jqueryui/1/themes/ui-lightness/jquery-ui.min.css');
		wp_enqueue_style('codemirror-css', DP_THEME_URI . '/inc/css/codemirror.css');
		// Color picker
		wp_enqueue_style('wp-color-picker');
		wp_enqueue_script('wp-color-picker');
		// Code highliter
		wp_enqueue_script('codemirror', DP_THEME_URI . '/inc/js/codemirror-compressed.js',array(),false,true);
		// Main js
		wp_enqueue_script('dp_setting_page', DP_THEME_URI . '/inc/js/dp_setting_page.min.js', array('jquery', 'codemirror', 'wp-color-picker'), DP_OPTION_SPT_VERSION, true);
	}
	static function enqueue_css_js_add_ons() {
		if (!is_admin()) return;
		//CSS
		wp_enqueue_style('dp-admin-css', DP_THEME_URI . '/inc/css/dp-admin.css', array(), DP_OPTION_SPT_VERSION);
		// Register Color picker
		wp_enqueue_style('wp-color-picker');
		wp_enqueue_script('wp-color-picker');
		// Javascript
		wp_enqueue_script('dp_setting_page', DP_THEME_URI . '/inc/js/dp_setting_page.min.js', array('jquery', 'wp-color-picker'), DP_OPTION_SPT_VERSION, true);
	}
	static function enqueue_css_js_img_edit() {
		if (!is_admin()) return;
		//CSS
		wp_enqueue_style('dp-admin-css', DP_THEME_URI . '/inc/css/dp-admin.css', array(), DP_OPTION_SPT_VERSION);
		// Image editing
		wp_enqueue_style('imgareaselect');
		wp_enqueue_script('imgareaselect', null, array('jquery'));
		// Javascript
		wp_enqueue_script('dp_img_edit', DP_THEME_URI . '/inc/js/dp_img_edit_page.min.js', array('jquery', 'imgareaselect'), DP_OPTION_SPT_VERSION, true);
	}


	/****************************************************************
	*  Add menu page into Admin interface.
	****************************************************************/
	/* ==================================================
	 * @param	none
	 * @return	none
	 */
	public static function add_menu() {
		//Main
		$hook_sf = add_menu_page(__('Customize DigiPress Theme', 'DigiPress'), __('DigiPress', 'DigiPress'), 'manage_options', digipress_options::OPTION_NAME, array('digipress_options', 'display_visual_options'), 'dashicons-admin-settings');

		//Sub(Visual)
		$hook_sf_visual = add_submenu_page(digipress_options::OPTION_NAME, __('Visual Settings For DigiPress', 'DigiPress'), __('Visual setting', 'DigiPress'), 'manage_options', digipress_options::OPTION_NAME, array('digipress_options', 'display_visual_options'));

		//Sub(Options)
		$hook_sf_option = add_submenu_page(digipress_options::OPTION_NAME, __('DigiPress Theme Operation Setting', 'DigiPress'), __('Operation Setting', 'DigiPress'), 'manage_options', digipress_options::OPTION_CONTROL, array('digipress_options', 'display_theme_custom'));

		//Sub(Delete file)
		$hook_sf_delete = add_submenu_page(digipress_options::OPTION_NAME, __('Delete Uploaded Files That Theme Use', 'DigiPress'), __('Delete Uploaded Files', 'DigiPress'), 'manage_options', digipress_options::OPTION_DELETE, array('digipress_options', 'display_delete_images'));

		//Sub(Image Edit)
		$hook_sf_edit_img = add_submenu_page(digipress_options::OPTION_NAME, __('Image Editing', 'DigiPress'), __('Image Editing', 'DigiPress'), 'manage_options', digipress_options::OPTION_IMG_EDIT, array('digipress_options', 'display_edit_images'));

		// Add-ons
		$hook_sf_add_ons = add_submenu_page(digipress_options::OPTION_NAME, __('Add-Ons', 'DigiPress'), __('Add-Ons', 'DigiPress'), 'manage_options', digipress_options::OPTION_ADD_ONS, array('digipress_options', 'display_add_ons'));	

		// Add CSS and Javascript into header only
		add_action('admin_print_scripts-'.$hook_sf, array('digipress_options', 'enqueue_css_js'));
		add_action('admin_print_scripts-'.$hook_sf_option, array('digipress_options','enqueue_css_js'));
		add_action('admin_print_scripts-'.$hook_sf_visual, array('digipress_options','enqueue_css_js'));
		add_action('admin_print_scripts-'.$hook_sf_delete, array('digipress_options','enqueue_css_js'));
		add_action('admin_print_scripts-'.$hook_sf_edit_img, array('digipress_options','enqueue_css_js_img_edit'));
		add_action('admin_print_scripts-'.$hook_sf_add_ons, array('digipress_options','enqueue_css_js_add_ons'));
	}
	
	
	/****************************************************************
	* Notice messages
	****************************************************************/
	public function dp_update_msg() {
		echo "<div class=\"updated\"><p>".__('Successfully updated.','DigiPress')."</p></div>";
	}
	public function dp_delete_msg() {
		echo "<div class=\"updated\"><p>".__('Successfully deleted.','DigiPress')."</p></div>";
	}
	public function dp_del_fail_msg() {
		echo "<div class=\"error\"><p>".__('Failed to delete a file.','DigiPress')."</p></div>";
	}
	public function dp_no_file_msg() {
		echo "<div class=\"error\"><p>".__('Target file does not found.','DigiPress')."</p></div>";
	}
	public function dp_reset_options() {
		echo "<div class=\"updated\"><p>".__('Successfully reseted parameters.','DigiPress')."</p></div>";
	}
	public function not_rewrite_msg($filePath) {
		echo "<div class=\"error\"><p>" . $filePath .' : '.__('The file is not writable. Please change the permission to 666 or 606.','DigiPress')."</p></div>";
	}
	public function file_in_use_msg($filePath) {
		echo "<div class=\"error\"><p>" . $filePath .' : '.__('The file may be in use by other program. Please identify the conflict process.','DigiPress')."</p></div>";
	}
	public function not_open_file_msg($filePath) {
		echo "<div class=\"error\"><p>" . $filePath . ' : '.__('The file can not be opened. Please identify the conflict process.','DigiPress')."</p></div>";
	}
	public function not_write_dir_msg($filePath) {
		echo "<div class=\"error\"><p>" . $filePath . ' : '.__('The files in this folder is not rewritable. Please change the permission to 777.','DigiPress')."</p></div>";
	}

	// ****************************************************************
	// Show admin message from transient data
	// ****************************************************************
	static function dp_show_admin_error_message() {
		if ( $messages = get_transient( 'dp-admin-option-errors' ) ) : ?>
<div class="error">
	<ul>
	<?php foreach( (array)$messages as $message ): ?>
		<li><?php echo $message; ?></li>
	<?php endforeach; ?>
	</ul>
</div>
<?php
		endif;
	}
	static function dp_show_admin_notice_message() {
		if ( $messages = get_transient( 'dp-admin-option-notices' ) ) : ?>
<div class="updated">
	<ul>
	<?php foreach( (array)$messages as $message ): ?>
		<li><?php echo $message; ?></li>
	<?php endforeach; ?>
	</ul>
</div>
<?php
		endif;
	}
}
//=========== End of "digipress_options" CLASS ===========
?>