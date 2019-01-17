<?php
/**
 * AMP mode
 */
function dp_amp_init(){
	global $options;
	if (!$options['use_amp']) return;

	// Add query var for custom post type
	add_filter('query_vars', 'dp_amp_add_query_vars');
	// Accessible AMP url parameter
	if (isset($options['disable_amp_archive']) && !empty($options['disable_amp_archive'])){
		add_rewrite_endpoint( DP_AMP_QUERY_VAR, EP_PAGES | EP_PERMALINK );
	} else {
		add_rewrite_endpoint( DP_AMP_QUERY_VAR, EP_PAGES | EP_PERMALINK | EP_AUTHORS  | EP_ALL_ARCHIVES |  EP_ROOT );
	}
	// Rewrite rules and flush
	add_action('parse_query', 'dp_amp_re_check_rewrite_rules', 100);
	// Forced query var
	add_filter('request', 'dp_amp_force_query_var_value');
	// Redirect to AMP URL
	add_action('template_redirect', 'dp_amp_url_redirect');
	// Rendering
	add_action('wp', 'dp_amp_add_endpoint_actions');
}
/**
 * Re check rewrite rules
 * @return [type] [description]
 */
function dp_amp_re_check_rewrite_rules(){
	$is_amp_endpoint_dp = is_amp_endpoint_dp();
	if (!$is_amp_endpoint_dp) dp_amp_rewrite_activation();
}
/**
 * AMP Main manager
 */
function dp_amp_add_endpoint_actions(){
	$is_amp_endpoint_dp = is_amp_endpoint_dp();
	if ($is_amp_endpoint_dp) {
		// Looad AMP template
		add_action('template_redirect', 'dp_amp_template_include');
		// Sanitize the user contents
		add_filter('the_content', 'dp_replace_content_for_amp', 100);
		add_filter('widget_text', 'dp_replace_content_for_amp', 100);
		add_filter('dp_widget_text', 'dp_replace_content_for_amp', 100);
		add_filter('get_comment_text', 'dp_replace_content_for_amp');
		// Enable auto paragraph for comment text
		add_filter('get_comment_text', 'wpautop');
		// Prevent next page
		add_filter('the_post', 'dp_amp_remove_nextpage', 99);
		// Remove style element of gallery shortcode
		add_filter('use_default_gallery_style', '__return_false');
		// Remove onclick attribute from comment reply link
		add_filter('comment_reply_link', 'dp_amp_remove_obj_comment_reply');
	}
}
/**
 * Add query var for news custom post type
 * @param  [type] $vars [description]
 * @return [type]       [description]
 */
function dp_amp_add_query_vars($vars){
	global $options;
	$vars[] = $options['news_cpt_slug_id'];
	return $vars;
}
/**
 * Redirect AMP URL
 * @return nothing
 */
function dp_amp_url_redirect(){
	global $options, $IS_MOBILE_DP;
	$is_amp_endpoint_dp = is_amp_endpoint_dp();
	if ($is_amp_endpoint_dp || !$IS_MOBILE_DP || isset($_GET['nonamp']) || !(bool)$options['always_redirect_to_amp']) return;
	// Unknown custom post type
	$unknown_cpt = true;
	$current_type = get_post_type();
	if ($current_type !== 'post' && $current_type !== 'page' && $current_type !== $options['news_cpt_slug_id']) {
		if (isset($options['enable_amp_cpt']) && !empty($options['enable_amp_cpt'])) {
			$arr_cpt = explode(',', $options['enable_amp_cpt']);
	    	if ($arr_cpt) {
	    		foreach ($arr_cpt as $key => $cpt) {
	    			if ($cpt === $current_type){
	    				$unknown_cpt = false;
	    				break;
	    			}
	    		}
	    	}
	    	if ($unknown_cpt) return;
		} else {
			return;
		}
	}

	// Except reply link
	if (preg_match('/\/?replytocom=[0-9]+/iu', $_SERVER['REQUEST_URI'])) return;

	// Except archive
	if (is_home() || is_archive() || is_search() || is_post_type_archive()){
		if (isset($options['disable_amp_archive']) && !empty($options['disable_amp_archive'])){
			return;
		}
	}

	// Redirect
	if (is_home() || is_front_page()) {
		if( get_option('page_for_posts') && get_queried_object_id() ) {
			$post_id = get_option('page_for_posts');
			wp_redirect(trailingslashit(trailingslashit(esc_url((get_permalink($post_id)))).DP_AMP_QUERY_VAR), 301);
			exit();
		} else {
			wp_redirect(trailingslashit(trailingslashit(esc_url(home_url())).DP_AMP_QUERY_VAR), 301);
			exit();
		}
	} else if (is_archive()) {
		global $wp;
		$current_archive_url = home_url($wp->request);
		wp_redirect(trailingslashit(trailingslashit(esc_url($current_archive_url)).DP_AMP_QUERY_VAR), 301);
		exit();
	} else if (is_single() || is_page()){
		$id = get_the_ID();
		$disable_amp = get_post_meta($id, 'disable_amp', true);
		if (!$disable_amp) {
			wp_redirect(trailingslashit(trailingslashit(esc_url((get_permalink($id)))).DP_AMP_QUERY_VAR), 301);
			exit();
		}
	}
}
/**
 * Replace to AMP template
 * @param  [type] $template [description]
 * @return [type]           [description]
 */
function dp_amp_template_include(){
	global $posts, $post, $options, $FB_APP_ID, $EXIST_FB_LIKE_BOX, $COLUMN_NUM, $ARCHIVE_STYLE, $IS_MOBILE_DP, $IS_AMP_DP;

	$template_file = '404.php';
	if (is_home()){
		$template_file = 'index.php';
	} else if (is_archive() || is_search() || is_post_type_archive($options['news_cpt_slug_id'])) {
		$template_file = 'archive.php';
	} else if (is_search() && !empty($_GET['s'])) {
		$template_file = 'search.php';
	} else if (is_single()){
		$unknown_cpt = true;
		$current_type = get_post_type();
		if ($current_type !== 'post' && $current_type !== $options['news_cpt_slug_id']) {
			if (isset($options['enable_amp_cpt']) && !empty($options['enable_amp_cpt'])) {
				$arr_cpt = explode(',', $options['enable_amp_cpt']);
		    	if ($arr_cpt) {
		    		foreach ($arr_cpt as $key => $cpt) {
		    			if ($cpt === $current_type){
		    				$unknown_cpt = false;
		    				break;
		    			}
		    		}
		    	}
			}
		} else {
			$unknown_cpt = false;
		}
		if (!$unknown_cpt) {
			$template_file = 'single.php';
		}
	} else if (is_page()) {
		$template_file = 'page.php';
	}
	$template_file = DP_THEME_DIR.'/'.DP_MOBILE_THEME_DIR.'/amp/'.$template_file;

	if (file_exists($template_file)) {
		add_action('dp_amp_head', 'dp_amp_custom_head_content');
		add_action('dp_amp_under_body', 'dp_amp_custom_under_body_content');
		add_action('dp_amp_library', 'dp_amp_include_library');
		include($template_file);
		exit;
	}
}
/**
 * Check current endpoint for AMP
 * @return boolean $IS_AMP_DP
 */
function is_amp_endpoint_dp(){
	if (is_admin())return;
	global $options, $IS_AMP_DP;

	if ( 0 === did_action( 'parse_query' ) ) {
		_doing_it_wrong( __FUNCTION__, sprintf( esc_html__( "is_amp_endpoint_dp() was called before the 'parse_query' hook was called. This function will always return 'false' before the 'parse_query' hook is called.", 'DigiPress' ) ), DP_OPTION_SPT_VERSION );
	}
	$IS_AMP_DP = get_query_var( DP_AMP_QUERY_VAR, false );

	if ($IS_AMP_DP && isset($options['use_amp']) && !empty($options['use_amp'])) {
		if (is_singular()) {
			$disable_amp = get_post_meta(get_the_ID(), 'disable_amp', true);
			$IS_AMP_DP = !$disable_amp ? true : false;
		} else if (is_home() || is_archive() || is_search() || is_post_type_archive()){
			if (isset($options['disable_amp_archive']) && !empty($options['disable_amp_archive'])){
				$IS_AMP_DP = false;
			}
		}
	}
	return $IS_AMP_DP;
}
/**
 * Make sure the `amp` query var has an explicit value.
 * @param  [type] $query_vars [description]
 * @return [type]             [description]
 */
function dp_amp_force_query_var_value( $query_vars ) {
	global $options;
	if ( isset( $query_vars[ DP_AMP_QUERY_VAR ] ) && '' === $query_vars[ DP_AMP_QUERY_VAR ] ) $query_vars[ DP_AMP_QUERY_VAR ] = 1;
	return $query_vars;
}
/**
 * include amp library
 * @return [type] [description]
 */
function dp_amp_include_library(){
	global $options, $options_visual, $AMP_LIB_CAROUSEL, $AMP_LIB_FORM;
	$amp_lightbox = false;
	$libraries = '<script async src="https://cdn.ampproject.org/v0.js"></script>';
	// For top page slider carousel
	if (is_home() && !is_paged()){
		if ($options_visual['dp_header_content_type_mobile'] == 2 && !$AMP_LIB_CAROUSEL) {
			$libraries .= '<script async custom-element="amp-carousel" src="https://cdn.ampproject.org/v0/amp-carousel-0.1.js"></script>';
			$AMP_LIB_CAROUSEL = true;
		}
	}
	// amp script for SNS share button and Facebook comment
	if (is_singular()) {
		$hide_sns_icon = get_post_meta(get_the_ID(), 'hide_sns_icon', true);
		$fb_comment = get_post_meta(get_the_ID(), 'dp_hide_fb_comment', true);
		// SNS share
		if (($options['sns_button_under_title'] || $options['sns_button_on_meta']) && !$hide_sns_icon) {
			$libraries .= '<script async custom-element="amp-lightbox" src="https://cdn.ampproject.org/v0/amp-lightbox-0.1.js"></script>'.'<script async custom-element="amp-social-share" src="https://cdn.ampproject.org/v0/amp-social-share-0.1.js"></script>';
			if (!$AMP_LIB_FORM){
				$libraries .= '<script async custom-element="amp-form" src="https://cdn.ampproject.org/v0/amp-form-0.1.js"></script>';
				$AMP_LIB_FORM = true;
			}
			add_action('dp_amp_bottom_bar_li_last', 'dp_amp_add_bottom_bar_btn_sns_share');
			add_action('dp_amp_footer', 'dp_amp_lightbox_single_sns_share');
			$amp_lightbox = true;
		}
		// Facebook comment form
		if ( (is_single() && $options['facebookcomment'] && !$fb_comment) || (is_page() && $options['facebookcomment_page'] && !$fb_comment) ) {
			$libraries .= '<script async custom-element="amp-facebook-comments" src="https://cdn.ampproject.org/v0/amp-facebook-comments-0.1.js"></script>';
		}
	}
	// Side menu
	if (has_nav_menu('global_menu_amp') || $options['show_global_menu_sns']) {
		$libraries .= '<script async custom-element="amp-sidebar" src="https://cdn.ampproject.org/v0/amp-sidebar-0.1.js"></script>';
		add_action('dp_amp_bottom_bar', 'dp_amp_add_bottom_bar_btn_side_menu');
		add_action('dp_amp_footer', 'dp_amp_global_side_menu');
	}
	// Lightbox search window
	if ($options['show_global_menu_search']) {
		if (!$amp_lightbox) {
			$libraries .= '<script async custom-element="amp-lightbox" src="https://cdn.ampproject.org/v0/amp-lightbox-0.1.js"></script>';
			if (!$AMP_LIB_FORM){
				$libraries .= '<script async custom-element="amp-form" src="https://cdn.ampproject.org/v0/amp-form-0.1.js"></script>';
				$AMP_LIB_FORM = true;
			}
		}
		add_action('dp_amp_bottom_bar', 'dp_amp_add_bottom_bar_btn_search');
		add_action('dp_amp_footer', 'dp_amp_lightbox_search_form');
	}
	// Google analytics
	if (isset($options['ga_tracking_id']) && !empty($options['ga_tracking_id'])){
		if ( !(is_user_logged_in() && current_user_can('edit_others_posts') && $options['no_track_admin_amp'])) {
			$libraries .= '<script async custom-element="amp-analytics" src="https://cdn.ampproject.org/v0/amp-analytics-0.1.js"></script>';
			add_action('dp_amp_under_body', 'dp_amp_ga_tracking_code');
		}
	}
	echo $libraries;
}
/**
 * Replcae the content to AMP
 * @param  [type] $content [description]
 * @return [type]          [description]
 */
function dp_replace_content_for_amp( $content ){
	global $AMP_LIB_TWITTER, $AMP_LIB_FACEBOOK, $AMP_LIB_YOUTUBE, $AMP_LIB_VIMEO, $AMP_LIB_SOUNDCLOUD, $AMP_LIB_INSTAGRAM, $AMP_LIB_PINTEREST, $AMP_LIB_IFRAME, $AMP_LIB_VIDEO, $AMP_LIB_AUDIO, $AMP_LIB_ADS, $AMP_LIB_FORM;
	// Params
	$pattern = $replacement = '';
	$matches = null;

	// Twitter(URL)
	$pattern = '/<p>https:\/\/twitter\.com\/.+?\/status\/([0-9]{2,30}).*?<\/p>/i';
	$replacement = '<amp-twitter width=486 height=657 layout="responsive" data-tweetid="$1"></amp-twitter>';
	$replacement = '$1';
	if(preg_match($pattern, $content, $matches)){
		if (!$AMP_LIB_TWITTER){
			add_action('dp_amp_library', 'dp_amp_library_twitter');
			$AMP_LIB_TWITTER = true;
		}
		$content = preg_replace($pattern,$replacement,$content);
	}
	// Twitter(blockquote)
	$pattern = '/<blockquote class="twitter-(tweet|video)".*?>(.|\n)+?<a href="https:\/\/twitter\.com\/.*?\/status\/([0-9]{2,30}).*?">(.|\n)+?<\/blockquote>/iu';
	$replacement = '<amp-twitter width=486 height=657 layout="responsive" data-tweetid="$3"></amp-twitter>';
	if(preg_match($pattern, $content, $matches)){
		if (!$AMP_LIB_TWITTER){
			add_action('dp_amp_library', 'dp_amp_library_twitter');
			$AMP_LIB_TWITTER = true;
		}
		$content = preg_replace($pattern,$replacement,$content);
	}

	// Facebook post(iframe)
	$pattern = '/<iframe[^>]+?src="https:\/\/www\.facebook\.com\/plugins\/post\.php\?href=(.*?)&.+?".+?><\/iframe>/is';
	if(preg_match($pattern,$content,$matches)){
		if (!$AMP_LIB_FACEBOOK){
			add_action('dp_amp_library', 'dp_amp_library_facebook');
			$AMP_LIB_FACEBOOK = true;
		}
		$content = preg_replace_callback(
			$pattern,
			function ($m) {
				return '<amp-facebook width=486 height=657 layout="responsive" data-href="'.urldecode($m[1]).'"></amp-facebook>';
			}, $content);
	}
	// Facebook post(blockquote)
	$pattern = '/<blockquote cite="https:\/\/www.facebook\.com\/(.+?)\/posts\/(.+?)".*?>(.|\n)+?<\/blockquote>/is';
	$replacement = '<amp-facebook width=486 height=657 layout="responsive" data-href="https://www.facebook.com/$1/posts/$2"></amp-facebook>';
	if(preg_match($pattern, $content, $matches)){
		if (!$AMP_LIB_FACEBOOK){
			add_action('dp_amp_library', 'dp_amp_library_facebook');
			$AMP_LIB_FACEBOOK = true;
		}
		$content = preg_replace($pattern,$replacement,$content);
	}
	// Facebook video(iframe)
	$pattern = '/<iframe[^>]+?src="https:\/\/www\.facebook\.com\/plugins\/video\.php\?href=(.*?)&.+?".+?><\/iframe>/is';
	if(preg_match($pattern,$content,$matches)){
		if (!$AMP_LIB_FACEBOOK){
			add_action('dp_amp_library', 'dp_amp_library_facebook');
			$AMP_LIB_FACEBOOK = true;
		}
		$content = preg_replace_callback(
			$pattern,
			function ($m) {
				return '<amp-facebook width=480 height=270 layout="responsive" data-embed-as="video" data-href="'.urldecode($m[1]).'"></amp-facebook>';
			}, $content);
	}
	// Facebook video(blockquote)
	$pattern = '/<blockquote cite="https:\/\/www.facebook\.com\/(.+?)\/videos\/(.+?)".*?>(.|\n)+?<\/blockquote>/is';
	$replacement = '<amp-facebook width=480 height=270 layout="responsive" data-embed-as="video" data-href="https://www.facebook.com/$1/videos/$2"></amp-facebook>';
	if(preg_match($pattern, $content, $matches)){
		if (!$AMP_LIB_FACEBOOK){
			add_action('dp_amp_library', 'dp_amp_library_facebook');
			$AMP_LIB_FACEBOOK = true;
		}
		$content = preg_replace($pattern,$replacement,$content);
	}

	// YouTube
	$pattern = '/<iframe[^>]+?youtube\.com\/embed\/(.+?)(\?[^>]+?)?"[^<]+?>.*?<\/iframe>/iu';
	$replacement = '<amp-youtube layout="responsive" data-videoid="$1" width="480" height="270"></amp-youtube>';
	if(preg_match($pattern, $content, $matches)){
		if (!$AMP_LIB_YOUTUBE){
			add_action('dp_amp_library', 'dp_amp_library_youtube');
			$AMP_LIB_YOUTUBE = true;
		}
		$content = preg_replace($pattern,$replacement,$content);
	}

	// Vimeo
	$pattern = '/<iframe[^>]+?vimeo\.com\/video\/(.+?)(\?[^>]+?)?"[^<]+?<\/iframe>/iu';
	$replacement = '<amp-vimeo layout="responsive" data-videoid="$1" width="480" height="270"></amp-vimeo>';
	if(preg_match($pattern, $content, $matches)){
		if (!$AMP_LIB_VIMEO){
			add_action('dp_amp_library', 'dp_amp_library_vimeo');
			$AMP_LIB_VIMEO = true;
		}
		$content = preg_replace($pattern,$replacement,$content);
	}

	// Sound cloud
	$pattern = '/<iframe\b[^>]*soundcloud.com\/tracks\/(\d*).*"[^>]*>(.*?)>/i';
	$replacement = '<amp-soundcloud layout="fixed-height" data-visual="true" data-trackid="$1" height="240"></amp-soundcloud>';
	if(preg_match($pattern, $content, $matches)){
		if (!$AMP_LIB_SOUNDCLOUD){
			add_action('dp_amp_library', 'dp_amp_library_soundcloud');
			$AMP_LIB_SOUNDCLOUD = true;
		}
		$content = preg_replace($pattern,$replacement,$content);
	}

	// Instagram
	$pattern = '/<blockquote class="instagram-media".+?"https:\/\/www\.instagram\.com\/p\/(.+?)\/".+?<\/blockquote>/is';
	$replacement = '<amp-instagram layout="responsive" data-shortcode="$1" width="400" height="400" ></amp-instagram>';
	if(preg_match($pattern,$content,$matches)){
		if (!$AMP_LIB_INSTAGRAM){
			add_action('dp_amp_library', 'dp_amp_library_instagram');
			$AMP_LIB_INSTAGRAM = true;
		}
		$content = preg_replace($pattern,$replacement,$content);
	}

	// Pinterest
	$pattern = '/<a\s.?data-pin-do="embedPin"\s.+?href="(.+?)">*?<\/a>/is';
	$replacement = '<amp-pinterest width=345 height=462 data-do="embedPin" data-url="$1"></amp-pinterest>';
	if(preg_match($pattern,$content,$matches)){
		if (!$AMP_LIB_PINTEREST){
			add_action('dp_amp_library', 'dp_amp_library_pinterest');
			$AMP_LIB_PINTEREST = true;
		}
		$content = preg_replace($pattern,$replacement,$content);
	}

	//Google map
	$pattern = '/<iframe([^>]+?)src="(https:\/\/www\.google\.com\/maps\/embed\?.+?)"\s(.+?)><\/iframe>/i';
	$replacement = '<iframe sandbox="allow-scripts allow-same-origin"$1src="$2" $3></iframe>';
	if(preg_match($pattern, $content, $matches)){
		$content = preg_replace($pattern,$replacement,$content);
	}

	// amp-iframe
	$pattern = '/<iframe ([^<]+?)<\/iframe>/iu';
	$replacement = '<amp-iframe layout="responsive" sandbox="allow-same-origin allow-scripts allow-popups-to-escape-sandbox allow-popups" $1</amp-iframe>';
	if(preg_match($pattern, $content, $matches)){
		if (!$AMP_LIB_IFRAME){
			add_action('dp_amp_library', 'dp_amp_library_iframe');
			$AMP_LIB_IFRAME = true;
		}
		$content = preg_replace($pattern,$replacement,$content);
	}

	// HTML 5 video(Only throw HTTPS)
	if (is_ssl()) {
		$pattern = '/<video\s.+?>((.|\n)+?)<\/video>/iu';
		$replacement = '<amp-video controls width="640" height="360" layout="responsive">$1</amp-video>';
		if(preg_match($pattern, $content, $matches)){
			if (!$AMP_LIB_VIDEO){
				add_action('dp_amp_library', 'dp_amp_library_video');
				$AMP_LIB_VIDEO = true;
			}
			$content = preg_replace($pattern,$replacement,$content);
		}
	}

	// Audio(Only throw HTTPS)
	$pattern = '/<audio ([^<]+?)<\/audio>/iu';
	$replacement = '<amp-audio $1</amp-audio>';
	if(preg_match($pattern, $content, $matches)){
		if (!$AMP_LIB_AUDIO){
			add_action('dp_amp_library', 'dp_amp_library_audio');
			$AMP_LIB_AUDIO = true;
		}
		$content = preg_replace($pattern,$replacement,$content);
	}

	// Google AdSense
	$pattern = '/<script\s.+?><\/script>.*?<ins\s.+?data-ad-client="(.+?)".+?data-ad-slot="(.+?)"><\/ins>.*?<script>.+?adsbygoogle\s.+?<\/script>/is';
	$replacement = '<amp-ad layout="responsive" width=300 height=250 type="adsense" data-ad-client="$1" data-ad-slot="$2"></amp-ad>';
	if(preg_match($pattern, $content, $matches)){
		if (!$AMP_LIB_ADS){
			add_action('dp_amp_library', 'dp_amp_library_ads');
			$AMP_LIB_ADS = true;
		}
		$content = preg_replace($pattern,$replacement,$content);
	}
	$pattern = '(<script\s.+?>.+?google_ad_client.+?=.+?"(.+?)";.+?google_ad_slot.+?=.+?"(.+?)";.+?<\/script>.+?<script.+?src=".+?googlesyndication.+?">.*?<\/script>)is';
	if(preg_match($pattern, $content, $matches)){
		if (!$AMP_LIB_ADS){
			add_action('dp_amp_library', 'dp_amp_library_ads');
			$AMP_LIB_ADS = true;
		}
		$content = preg_replace($pattern,$replacement,$content);
	}

	// Open blog parts
	$pattern = '/ src="http:\/\/ecx.images-amazon.com/i';
	$replacement = ' class="ky-img-am" layout="responsive" alt="item-image" src="http://ecx.images-amazon.com';
	$content = preg_replace($pattern, $replacement, $content);
	$pattern = '/ src="https:\/\/images-fe.ssl-images-amazon.com/i';
	$replacement = ' class="ky-img-am" layout="responsive" alt="item-image" src="https://images-fe.ssl-images-amazon.com';
	$content = preg_replace($pattern, $replacement, $content);
	$pattern = '/ src="http:\/\/thumbnail.image.rakuten.co.jp/i';
	$replacement = ' class="ky-img-ra" layout="responsive" alt="item-image" src="http://thumbnail.image.rakuten.co.jp';
	$content = preg_replace($pattern, $replacement, $content);
	$pattern = '/ src="http:\/\/item.shopping.c.yimg.jp/i';
	$replacement = ' class="ky-img-ya" layout="responsive" alt="item-image" src="http://item.shopping.c.yimg.jp';
	$content = preg_replace($pattern, $replacement, $content);

	// replace img to amp-img (Replace amp-anim, if gif image is included)
	$pattern = '/<img\s([^<>]*?)(src|width|height)="([^<>"\s]+?)"([^<>]*?)(src|width|height)="([^<>"\s]+?)"([^<>]*?)(src|width|height)="([^<>"\s]+?)"([^<>]*?)\/?>/iu';
	if(preg_match($pattern, $content, $matches)){
		$content = preg_replace_callback(
			$pattern,
			function ($m) {
				$com = 'img';
				$layout = '';
				if (is_array($m)) {
					foreach ($m as $key => $val) {
						// GIF image
						// if (preg_match('/^(http:|https:|\/\/).+?(\.gif)$/iu', $val)){
						// 	$com = 'anim';
						// 	add_action('dp_amp_library', 'dp_amp_library_anim');
						// 	break;
						// }
						// if sizes are not found
						if ($val === 'width' && isset($m[$key+1])){
							if ((int)$m[$key+1] > 250) $layout = ' layout="responsive"';
						}
					}
					return '<amp-'.$com.' '.trim($m[1]).' '.$m[2].'="'.$m[3].'" '.trim($m[4]).' '.$m[5].'="'.$m[6].'" '.trim($m[7]).' '.$m[8].'="'.$m[9].'" '.trim($m[10]).$layout.'></amp-'.$com.'>';
				}
			}, $content);
	}
	$pattern = '/<img\s([^<>]*?)(src|width|height)="([^<>"\s]+?)"([^<>]*?)(src|width|height)="([^<>"\s]+?)"([^<>]*?)\/?>/iu';
	if(preg_match($pattern, $content, $matches)){
		$content = preg_replace_callback(
			$pattern,
			function ($m) {
				$width = '';
				$height = '';
				$custom_size = '';
				$has_width = false;
				$has_height = false;
				if (is_array($m)) {
					foreach ($m as $key => $val) {
						if ($val === 'width') {
							$has_width = true;
							$width = (int)$m[$key+1];
						}
						if ($val === 'height') {
							$has_height = true;
							$height = (int)$m[$key+1];
						}
						if ($val === 'src') {
							$custom_size = dp_get_image_size($m[$key+1]);
						}
					}
					if (isset($custom_size) && is_array($custom_size)){
						if ($has_width) {
							$height = floor(($width * $custom_size[1]) / $custom_size[0]);
							$custom_size = ' height="'.$height.'"';
						} else if ($has_height) {
							$width = floor(($height * $custom_size[0]) / $custom_size[1]);
							$custom_size = ' width="'.$width.'"';
						}
						if ($width > 250) $custom_size .= ' layout="responsive"';
					}
					if (empty($custom_size)) $custom_size = ' width="300" height="300" layout="responsive"';

					return '<amp-img'.$custom_size.' '.trim($m[1]).' '.$m[2].'="'.$m[3].'" '.trim($m[4]).' '.$m[5].'="'.$m[6].'" '.trim($m[7]).'></amp-img>';
				}
			}, $content);
	}
	$pattern = '/<img ([^<>]+?)\/?>/iu';
	if(preg_match($pattern, $content, $matches)){
		$content = preg_replace_callback(
			$pattern,
			function ($m) {
				$size_code = '';
				$has_width = false;
				$has_height = false;
				if (preg_match('/\swidth=["|\']\d.+?["|\']/iu', $m[0])) {
					$has_width = true;
				}
				if (preg_match('/\sheight=["|\']\d.+?["|\']/iu', $m[0])) {
					$has_height = true;
				}
				if (!$has_width || !$has_height){
					if (preg_match('/src=["|\']([\/\/:|http:|https:].+?)["|\']/iu', $m[0], $matches)) {
						$size_code = dp_get_image_size($matches[1]);
						if (!empty($size_code) && is_array($size_code)) {
							$size_code = ' width="'.$size_code[0].'" height="'.$size_code[1].'"';
						} else {
							$size_code = ' width="300" height="300"';
						}
					}
				}
				return '<amp-img layout="responsive" '.$m[1].$size_code.'></amp-img>';
			}, $content);
	}

	// Remove stricted tags and attributes
	$pattern = array(
		'/<p><script async src="\/\/platform\.twitter\.com\/widgets\.js" charset="utf-8"><\/script><\/p>/is',
		'/<script async src="\/\/platform\.twitter\.com\/widgets\.js" charset="utf-8"><\/script>/is',
		'/<p><script async defer src="\/\/platform\.instagram\.com\/.+?\/embeds\.js"><\/script><\/p>/is',
		'/<script async defer src="\/\/platform\.instagram\.com\/.+?\/embeds\.js"><\/script>/is',
		'/<script[^<]*?<\/script>/iu',
		'/<style[^<]*?<\/style>/iu',
		'/<font[^<]*?<\/font>/iu',
		'/<frame[^<]*?<\/frame>/iu',
		'/<frameset[^<]*?<\/frameset>/iu',
		'/<object[^<]*?<\/object>/iu',
		'/<param[^<]*?<\/param>/iu',
		'/<embed[^<]*?<\/embed>/iu',
		'/<base[^<]*?<\/base>/iu',
		'/<applet[^<]*?<\/applet>/iu',
		'/\sstyle=["\'][^"].*?["|\']/iu',
		'/\sborder="[^"]*?"/iu',
		'/\starget="[^"]*?"/iu',
		'/\sonclick="[^"]*?"/iu',
		'/\sonchange="[^"]*?"/iu',
		'/\sscale="[^"]*?"/iu'
	);
	$replacement = '';
	foreach ($pattern as $current_pattern) {
		if(preg_match($current_pattern, $content,$matches)){
			$content = preg_replace($pattern,$replacement,$content);
		}
	}
	// form element is allowed in SSL
	if(preg_match('/<form[^<]*?<\/form>/iu', $content,$matches)){
		if (is_ssl() && !$AMP_LIB_FORM) {
			add_action('dp_amp_library', 'dp_amp_library_form');
			$AMP_LIB_FORM = true;
		} else {
			$content = preg_replace($pattern,$replacement,$content);
		}
	}

	return $content;
}
function dp_amp_library_ads(){
	echo '<script async custom-element="amp-ad" src="https://cdn.ampproject.org/v0/amp-ad-0.1.js"></script>';
}
function dp_amp_library_iframe(){
	echo '<script async custom-element="amp-iframe" src="https://cdn.ampproject.org/v0/amp-iframe-0.1.js"></script>';
}
function dp_amp_library_youtube(){
	echo '<script async custom-element="amp-youtube" src="https://cdn.ampproject.org/v0/amp-youtube-0.1.js"></script>';
}
function dp_amp_library_facebook(){
	echo '<script async custom-element="amp-facebook" src="https://cdn.ampproject.org/v0/amp-facebook-0.1.js"></script>';
}
function dp_amp_library_instagram(){
	echo '<script async custom-element="amp-instagram" src="https://cdn.ampproject.org/v0/amp-instagram-0.1.js"></script>';
}
function dp_amp_library_pinterest(){
	echo '<script async custom-element="amp-pinterest" src="https://cdn.ampproject.org/v0/amp-pinterest-0.1.js"></script>';
}
function dp_amp_library_vimeo(){
	echo '<script async custom-element="amp-vimeo" src="https://cdn.ampproject.org/v0/amp-vimeo-0.1.js"></script>';
}
function dp_amp_library_twitter(){
	echo '<script async custom-element="amp-twitter" src="https://cdn.ampproject.org/v0/amp-twitter-0.1.js"></script>';
}
function dp_amp_library_soundcloud(){
	echo '<script async custom-element="amp-soundcloud" src="https://cdn.ampproject.org/v0/amp-soundcloud-0.1.js"></script>';
}
function dp_amp_library_video(){
	echo '<script async custom-element="amp-video" src="https://cdn.ampproject.org/v0/amp-video-0.1.js"></script>';
}
function dp_amp_library_audio(){
	echo '<script async custom-element="amp-audio" src="https://cdn.ampproject.org/v0/amp-audio-0.1.js"></script>';
}
function dp_amp_library_form(){
	echo '<script async custom-element="amp-form" src="https://cdn.ampproject.org/v0/amp-form-0.1.js"></script>';
}
function dp_amp_library_anim(){
	echo '<script async custom-element="amp-anim" src="https://cdn.ampproject.org/v0/amp-anim-0.1.js"></script>';
}
/**
 * [dp_check_target_rewrite_rules description]
 * @param  [type] $rule [description]
 * @return [type]       [description]
 */
function dp_check_target_rewrite_rules($rule){
	global $wp_rewrite;
	$flag = false;
	$rewrite = $wp_rewrite->wp_rewrite_rules();
	if ( !empty($rewrite) ) {
		foreach ( (array)$rewrite as $match => $query ) {
			if ( strpos($match, $rule) !== false ){
				$flag = true;
				break;
			}
		}
	}
	return $flag;
}
/**
 * Activation rewrite rules for AMP
 */
function dp_amp_rewrite_activation() {
	global $options, $wp_rewrite;
	// $flag = dp_check_target_rewrite_rules('/'.DP_AMP_QUERY_VAR);
	// if(!$flag){
		if (isset($options['disable_amp_archive']) && !empty($options['disable_amp_archive'])){
			add_rewrite_endpoint(DP_AMP_QUERY_VAR, EP_PAGES | EP_PERMALINK);
			add_rewrite_endpoint($options['news_cpt_slug_id'], EP_PERMALINK | EP_PAGES);
		} else {
			add_rewrite_endpoint(DP_AMP_QUERY_VAR, EP_PAGES | EP_PERMALINK | EP_AUTHORS  | EP_ALL_ARCHIVES |  EP_ROOT);
			add_rewrite_endpoint($options['news_cpt_slug_id'], EP_PERMALINK | EP_PAGES | EP_ALL_ARCHIVES);
		}
		add_post_type_support('post', DP_AMP_QUERY_VAR);
		add_post_type_support('page', DP_AMP_QUERY_VAR);
		add_post_type_support($options['news_cpt_slug_id'], DP_AMP_QUERY_VAR);
		if (isset($options['enable_amp_cpt']) && !empty($options['enable_amp_cpt'])) {
			$arr_cpt = explode(',', $options['enable_amp_cpt']);
			if ($arr_cpt) {
				foreach ($arr_cpt as $key => $cpt) {
					$cpt = trim($cpt);
					add_rewrite_endpoint($cpt, EP_PERMALINK | EP_PAGES | EP_ALL_ARCHIVES);
					add_post_type_support($cpt, DP_AMP_QUERY_VAR);
				}
			}
		}
		dp_amp_rewrite_rules();
		$wp_rewrite->flush_rules(false);
	// }
}
/**
 * Deactivation rewrite rules for AMP
 */
function dp_amp_rewrite_deactivation() {
	global $options, $wp_rewrite;
	// $flag = dp_check_target_rewrite_rules('/'.DP_AMP_QUERY_VAR);
	// if ($flag) {
		foreach ( $wp_rewrite->endpoints as $index => $endpoint ) {
			if ( DP_AMP_QUERY_VAR === $endpoint[1] ) {
				unset( $wp_rewrite->endpoints[ $index ] );
				remove_post_type_support( 'post', DP_AMP_QUERY_VAR );
				remove_post_type_support( 'page', DP_AMP_QUERY_VAR );
				remove_post_type_support( $options['news_cpt_slug_id'], DP_AMP_QUERY_VAR );
				if (isset($options['enable_amp_cpt']) && !empty($options['enable_amp_cpt'])) {
			    	$arr_cpt = explode(',', $options['enable_amp_cpt']);
			    	if ($arr_cpt) {
			    		foreach ($arr_cpt as $key => $cpt) {
			    			$cpt = trim($cpt);
			    			remove_post_type_support( $cpt, DP_AMP_QUERY_VAR );
			    		}
			    	}
			    }
				break;
			}
		}
		$wp_rewrite->flush_rules(false);
	// }
}
// Frontpage and Blog page check from reading settings.
function dp_amp_name_blog_page() {
	if(!$page_for_posts = get_option('page_for_posts')) return;
	$page_for_posts  =  get_option( 'page_for_posts' );
	$post = get_post($page_for_posts);
	if ( $post ) {
		$slug = $post->post_name;
		return $slug;
	}
}
function dp_amp_get_the_page_id_blog_page(){
	$page = "";
	$output = "";
	if ( dp_amp_name_blog_page() ) {
		$page = get_page_by_path( dp_amp_name_blog_page() );
		$output = $page->ID;
	}
	return $output;
}
// Add Custom Rewrite Rule to make sure pagination & redirection is working correctly
function dp_amp_rewrite_rules() {
	global $options;
    // For Homepage
    add_rewrite_rule(
      'amp/?$',
      'index.php?amp=1',
      'top'
    );
	// For Homepage with Pagination
    add_rewrite_rule(
        'amp\/page\/([0-9]{1,})\/?$',
        array(
        	'amp' => 1,
        	'paged' => '$matches[1]'
        ),
        'top'
    );
	// For /Blog page with Pagination
    add_rewrite_rule(
        dp_amp_name_blog_page(). '\/amp\/page\/([0-9]{1,})\/?$',
        array(
        	'amp' => 1,
        	'paged' => '$matches[1]',
        	'page_id' => dp_amp_get_the_page_id_blog_page(),
        ),
        'top'
    );
    // For search result with Pagination
    add_rewrite_rule(
        'page\/([0-9]{1,})\/\?amp=1&s=(.+?)$',
        array(
        	'amp' => 1,
        	's' => '$matches[2]',
        	'paged' => '$matches[1]'
        ),
        'top'
    );
    // For Author pages
    add_rewrite_rule(
        'author\/([^/]+)\/amp\/?$',
        array(
        	'amp' => 1,
        	'author_name' => '$matches[1]'
        ),
        'top'
    );
    // For Author pages with pagination
    add_rewrite_rule(
        'author\/([^/]+)\/amp\/page\/?([0-9]{1,})\/?$',
        array(
        	'amp' => 1,
        	'author_name' => '$matches[1]',
        	'paged' => '$matches[2]'
        ),
        'top'
    );
    // For Author pages with pagination(with 'archives' prefix)
    add_rewrite_rule(
        'archives\/author\/([^/]+)\/amp\/page\/?([0-9]{1,})\/?$',
        array(
        	'amp' => 1,
        	'author_name' => '$matches[1]',
        	'paged' => '$matches[2]'
        ),
        'top'
    );
    // For news custom post type
    add_rewrite_rule(
		$options['news_cpt_slug_id'].'\/amp\/?$',
		array(
        	'amp' => 1,
        	'post_type' => $options['news_cpt_slug_id']
        ),
		'top'
    );
	// For news custom post type with Pagination
    add_rewrite_rule(
        $options['news_cpt_slug_id'].'\/amp\/page\/?([0-9]{1,})\/?$',
        array(
        	'amp' => 1,
        	'post_type' => $options['news_cpt_slug_id'],
        	'paged' => '$matches[1]'
        ),
        'top'
    );
    // For news custom post type with Pagination(with 'archives' prefix)
    add_rewrite_rule(
		'archives\/'.$options['news_cpt_slug_id'].'\/amp\/?$',
		array(
        	'amp' => 1,
        	'post_type' => $options['news_cpt_slug_id']
        ),
		'top'
    );
    add_rewrite_rule(
        'archives\/'.$options['news_cpt_slug_id'].'\/amp\/page\/?([0-9]{1,})\/?$',
        array(
        	'amp' => 1,
        	'post_type' => $options['news_cpt_slug_id'],
        	'paged' => '$matches[1]'
        ),
        'top'
    );
    // For user's custom post types
    if (isset($options['enable_amp_cpt']) && !empty($options['enable_amp_cpt'])) {
    	$arr_cpt = explode(',', $options['enable_amp_cpt']);
    	if ($arr_cpt) {
    		foreach ($arr_cpt as $key => $cpt) {
    			$cpt = trim($cpt);
    			add_rewrite_rule(
					$cpt.'\/amp\/?$',
					array(
			        	'amp' => 1,
			        	'post_type' => $cpt
			        ),
					'top'
			    );
			    add_rewrite_rule(
			        $cpt.'\/amp\/page\/?([0-9]{1,})\/?$',
			        array(
			        	'amp' => 1,
			        	'post_type' => $cpt,
			        	'paged' => '$matches[1]'
			        ),
			        'top'
			    );
			    // with 'archives' prefix
			    add_rewrite_rule(
					'archives\/'.$cpt.'\/amp\/?$',
					array(
			        	'amp' => 1,
			        	'post_type' => $cpt
			        ),
					'top'
			    );
			    add_rewrite_rule(
			        'archives\/'.$cpt.'\/amp\/page\/?([0-9]{1,})\/?$',
			        array(
			        	'amp' => 1,
			        	'post_type' => $cpt,
			        	'paged' => '$matches[1]'
			        ),
			        'top'
			    );
    		}
    	}
    }
    // For category pages
    $rewrite_category = get_option('category_base');
    if (empty($rewrite_category)) {
    	$rewrite_category = 'category';
    }
    add_rewrite_rule(
      $rewrite_category.'\/(.+?)\/amp/?$',
      array(
      	'amp' => 1,
      	'category_name' => '$matches[1]'
      ),
      'top'
    );
    // For category pages with Pagination
    add_rewrite_rule(
		$rewrite_category.'\/(.+?)\/amp\/page\/?([0-9]{1,})\/?$',
		array(
			'amp' => 1,
			'category_name' => '$matches[1]',
			'paged' => '$matches[2]',
		),
		'top'
    );
    // For category pages with Pagination(with 'archives' prefix)
    add_rewrite_rule(
		'archives\/'.$rewrite_category.'\/(.+?)\/amp\/page\/?([0-9]{1,})\/?$',
		array(
			'amp' => 1,
			'category_name' => '$matches[1]',
			'paged' => '$matches[2]',
		),
		'top'
    );
    // For tag pages
	$rewrite_tag = get_option('tag_base');
    if (! empty($rewrite_tag)) {
    	$rewrite_tag = get_option('tag_base');
    } else {
    	$rewrite_tag = 'tag';
    }
    add_rewrite_rule(
		$rewrite_tag.'\/(.+?)\/amp/?$',
		array(
			'amp' => 1,
			'tag' => '$matches[1]'
		),
		'top'
    );
    // For tag pages with Pagination
    add_rewrite_rule(
		$rewrite_tag.'\/(.+?)\/amp\/page\/?([0-9]{1,})\/?$',
		array(
			'amp' => 1,
			'tag' => '$matches[1]',
			'paged' => '$matches[2]'
		),
		'top'
    );
    // For tag pages with Pagination(with 'archives' prefix)
    add_rewrite_rule(
		'archives\/'.$rewrite_tag.'\/(.+?)\/amp\/page\/?([0-9]{1,})\/?$',
		array(
			'amp' => 1,
			'tag' => '$matches[1]',
			'paged' => '$matches[2]'
		),
		'top'
    );
	//Rewrite rule for custom Taxonomies
	$args = array(
	  'public'   => true,
	  '_builtin' => false
	);
	$output = 'names'; // or objects
	$operator = 'and'; // 'and' or 'or'
	$taxonomies = get_taxonomies( $args, $output, $operator );
	if ( $taxonomies ) {
	  foreach ( $taxonomies  as $taxonomy ) {
		add_rewrite_rule(
			$taxonomy.'\/(.+?)\/amp/?$',
			array(
				'amp' => 1,
				$taxonomy => '$matches[1]'
			),
			'top'
	    );
	    // For Custom Taxonomies with pages
	    add_rewrite_rule(
			$taxonomy.'\/(.+?)\/amp\/page\/?([0-9]{1,})\/?$',
			array(
				'amp' => 1,
				$taxonomy => '$matches[1]',
				'paged' => '$matches[2]'
			),
			'top'
	    );
	    // For Custom Taxonomies with pages(with 'archives' prefix)
	    add_rewrite_rule(
			'archives\/'.$taxonomy.'\/(.+?)\/amp\/page\/?([0-9]{1,})\/?$',
			array(
				'amp' => 1,
				$taxonomy => '$matches[1]',
				'paged' => '$matches[2]'
			),
			'top'
	    );
	  }
	}
}
/**
 * Remove <!--nextpage--> tag
 */
function dp_amp_remove_nextpage ($post){
	if (false !== strpos($post->post_content, '<!--nextpage-->' )){
		$GLOBALS['pages'] = array($post->post_content);
		$GLOBALS['numpages'] = 0;
		$GLOBALS['multipage'] = false;
	}
}
/**
 * Remove onclick attribute for comment replpy link
 */
function dp_amp_remove_obj_comment_reply($link){
	return preg_replace('/\sonclick=\'.+?\'/i', '', $link);
}
/**
 * Insert original codes in head section
 * @return [type] [description]
 */
function dp_amp_custom_head_content(){
	global $options;
	echo $options['custom_head_content_amp'];
}
/**
 * Insert original codes in under body tag
 * @return [type] [description]
 */
function dp_amp_custom_under_body_content(){
	global $options;
	echo $options['custom_under_body_content_amp'];
}
/**
 * Insert google analytics tracking code
 * @return [type] [description]
 */
function dp_amp_ga_tracking_code(){
	global $options;
	echo '<amp-analytics type="googleanalytics"><script type="application/json">{"vars":{"account":"'.$options['ga_tracking_id'].'"},"triggers":{"trackPageview": {"on": "visible","request": "pageview"}}}</script></amp-analytics>';
}
/**
 * Display global menu
 * @return [type] [description]
 */
function dp_amp_global_side_menu(){
	global $options;
	// Slide menu
	if (has_nav_menu('global_menu_amp') || $options['show_global_menu_sns']) { ?>
<amp-sidebar id="g_menu_wrapper" class="g_menu_wrapper" layout="nodisplay" side="<?php echo $options['sidemenu_pos_amp']; ?>"><button on="tap:g_menu_wrapper.close" class="btbar_btn close icon-cross"></button><?php
		// Non AMP redirect link
		$nonampurl = dp_show_canonical(false);
		if (isset($nonampurl) && !empty($nonampurl[0]) && is_array($nonampurl)){
			$nonamp_text = isset($options['nav_text_nonamp']) && !empty($options['nav_text_nonamp']) ? $options['nav_text_nonamp'] : __('Non-AMP Mode', 'DigiPress');
			echo '<div class="nonamplink"><a href="'.$nonampurl[0].'?nonamp=1">'.$nonamp_text.'</a></div>';
		}
		// **********************************
		// Show Custom Menu
		// **********************************
		if (has_nav_menu('global_menu_amp')){ ?>
<nav id="global_menu_nav" class="global_menu_nav"><?php
			wp_nav_menu(array(
				'theme_location'=> 'global_menu_amp',
				'container'	=> '',
				'menu_id'	=> 'g_menu',
				'menu_class' => 'g_menu',
				'walker'	=> new dp_custom_menu_walker()
			));?>
</nav><?php
		}
		if (isset($options['global_menu_right_content'])){
			if ($options['global_menu_right_content'] === 'sns') {
				// **********************************
				// SNS icons
				// **********************************
				if ($options['show_global_menu_sns']) {
					$sns_code = '';
					$sns_code = $options['global_menu_fb_url'] ? '<li class="fb"><a href="' . $options['global_menu_fb_url'] . '" title="Share on Facebook" target="_blank" class="sns_link"><i class="icon-facebook"></i></a></li>' : '';
					$sns_code .= $options['global_menu_twitter_url'] ? '<li class="tw"><a href="' . $options['global_menu_twitter_url'] . '" title="Follow on Twitter" target="_blank" class="sns_link"><i class="icon-twitter"></i></a></li>' : '';
					$sns_code .= $options['global_menu_instagram_url'] ? '<li class="instagram"><a href="' . $options['global_menu_instagram_url'] . '" title="Instagram" target="_blank" class="sns_link"><i class="icon-instagram"></i></a></li>' : '';
					$sns_code .= $options['global_menu_youtube_url'] ? '<li class="youtube"><a href="' . $options['global_menu_youtube_url'] . '" title="YouTube" target="_blank" class="sns_link"><i class="icon-youtube"></i></a></li>' : '';
					$sns_code .= $options['rss_to_feedly'] ? '<li class="feedly"><a href="https://feedly.com/i/subscription/feed/'.urlencode(get_feed_link()).'" target="_blank" title="Follow on feedly" class="sns_link"><i class="icon-feedly"></i></a></li>' : '<li class="rss"><a href="'. get_feed_link() .'" title="Subscribe Feed" target="_blank" class="sns_link"><i class="icon-rss"></i></a></li>';
					// Show
					if (!empty($sns_code)) {
						echo '<div class="sns_links"><ul>'.$sns_code.'</ul></div>';
					}
				}
			} else if ($options['global_menu_right_content'] === 'tel') {
				// **********************************
				// Tel number
				// **********************************
				if (!empty($options['global_menu_right_tel'])) {
					echo '<div class="tel_num"><a href="tel:'.$options['global_menu_right_tel'].'" class="icon-phone">'.$options['global_menu_right_tel'].'</a></div>';
				}
			}
		}?>
</amp-sidebar><?php
	}
}
/**
 * Show site serch form
 * @return [type] [description]
 */
function dp_amp_lightbox_search_form(){
	global $options;
	if (!$options['show_global_menu_search']) return;
	$action_url = esc_url( get_bloginfo('url') );
	$action_url = preg_replace('#^http?:#', '', $action_url);
	$form = '<form role="search" method="get" id="amp-search" class="amp-lightbox-content amp-search icon-search" target="_top" action="'.$action_url  .'"><div class="amp-search-wrapper"><input type="text" placeholder="AMP" value="1" name="amp" class="hidden"/><input type="text" placeholder="'.__('Type Here','DigiPress').'" value="'.get_search_query().'" name="s" id="s" class="words" /><input type="submit" id="amp-search-submit" class="srchsbmt" value="" /></div></form>';
	$form = '<amp-lightbox id="hd_srch_form" class="hd_srch_form" layout="nodisplay"><button on="tap:hd_srch_form.close" class="btbar_btn close icon-cross"></button>'.$form.'</amp-lightbox>';
	echo $form;
}
/**
 * [dp_amp_single_sns_share description]
 * @return [type] [description]
 */
function dp_amp_lightbox_single_sns_share(){
	global $options;
	$code = '<amp-lightbox id="single_sns_share" class="mp-lightbox-content sns-share" layout="nodisplay"><button on="tap:single_sns_share.close" class="btbar_btn close icon-cross"></button><div class="amp-lightbox-content sns_share_wrapper">';
	$url = get_permalink();
	$rss_url = urlencode(get_bloginfo('rss2_url'));
	$title = urlencode(get_the_title().' | '.get_bloginfo('name'));

	if ($options['show_facebook_button']) {
		$code .= '<amp-social-share type="facebook" data-param-app_id="'.$options['fb_app_id'].'" width="300" height="44">'.__('Share on Facebook','DigiPress').'</amp-social-share>';
	}
	if ($options['show_twitter_button']) {
		$code .= '<amp-social-share type="twitter" width="300" height="44">'.__('Share on Twitter','DigiPress').'</amp-social-share>';
	}
	if ($options['show_hatena_button'] ) {
		$code .= '<a href="http://b.hatena.ne.jp/entry/'.$url.'" class="amp-social-share hatebu" target="_blank"><amp-img src="data:image/svg+xml;base64,PHN2ZyB2ZXJzaW9uPSIxLjEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgd2lkdGg9IjMyIiBoZWlnaHQ9IjMyIiB2aWV3Qm94PSIwIDAgMzIgMzIiPjx0aXRsZT5oYXRlYnU8L3RpdGxlPjxwYXRoIGQ9Ik0yNC44NTMgMjcuMDk2YzAtMC42NDQgMC4yMzYtMS4yMDIgMC43MDctMS42NzNzMS4wMzUtMC43MDcgMS42OS0wLjcwNyAxLjIxOSAwLjIzNiAxLjY5IDAuNzA3YzAuNDcxIDAuNDcxIDAuNzA3IDEuMDM1IDAuNzA3IDEuNjkgMCAwLjY2Ny0wLjIzNiAxLjIzNi0wLjcwNyAxLjcwNy0wLjQ2IDAuNDYtMS4wMjMgMC42OS0xLjY5IDAuNjktMC42NzkgMC0xLjI0OC0wLjIzLTEuNzA4LTAuNjlzLTAuNjktMS4wMzUtMC42OS0xLjcyNXpNMjUuMzAxIDIzLjA5NHYtMjAuNjk4aDMuODk4djIwLjY5OGgtMy44OTh6IiBmaWxsPSJ3aGl0ZSI+PC9wYXRoPjxwYXRoIGQ9Ik0yMS42MzEgNS4yODRjMC43NzggMS4xNTMgMS4xNjcgMi41MzIgMS4xNjcgNC4xMzkgMCAxLjY1NS0wLjM5MiAyLjk4Ni0xLjE3NyAzLjk5Mi0wLjQzOSAwLjU2NC0xLjA4NSAxLjA3OS0xLjkzOSAxLjU0NSAxLjI5NyAwLjUwMyAyLjI3NyAxLjMgMi45MzcgMi4zOTFzMC45OSAyLjQxNiAwLjk5IDMuOTc0YzAgMS42MDYtMC4zNzcgMy4wNDgtMS4xMzIgNC4zMjItMC40OCAwLjg0Ny0xLjA4MCAxLjU1OC0xLjgwMSAyLjEzNC0wLjgxMiAwLjY2Mi0xLjc2OSAxLjExNi0yLjg3MiAxLjM2MXMtMi4zMDEgMC4zNjgtMy41OTIgMC4zNjhoLTExLjQ1NXYtMjcuMTE0aDEyLjI4NmMzLjEgMC4wNDkgNS4yOTcgMS4wMTIgNi41ODkgMi44ODh6TTcuODI2IDcuMTA1djUuOTc4aDYuMTc5YzEuMTA0IDAgMi0wLjIyMyAyLjY4OC0wLjY3MXMxLjAzMy0xLjI0MiAxLjAzMy0yLjM4MmMwLTEuMjYzLTAuNDU1LTIuMDk3LTEuMzY2LTIuNTAyLTAuNzg2LTAuMjgyLTEuNzg3LTAuNDIzLTMuMDA1LTAuNDIzaC01LjUzek03LjgyNiAxNy41NzJ2Ny4yMjloNi4xNzJjMS4xMDIgMCAxLjk2LTAuMTU5IDIuNTc0LTAuNDc5IDEuMTE0LTAuNTg4IDEuNjcxLTEuNzE2IDEuNjcxLTMuMzg1IDAtMS40MS0wLjU0LTIuMzc5LTEuNjE5LTIuOTA2LTAuNjAzLTAuMjk1LTEuNDUtMC40NDgtMi41NDEtMC40NmgtNi4yNTh6IiBmaWxsPSJ3aGl0ZSI+PC9wYXRoPjwvc3ZnPg==" width="24" height="24" class="amp-social-icon"></amp-img>'.__('Share on Hatena Bookmark','DigiPress').'</a>';
	}
	if ($options['show_tumblr_button'] ) {
		$code .= '<amp-social-share type="tumblr" width="300" height="44">'.__('Share on Tumblr','DigiPress').'</amp-social-share>';
	}
	if ($options['show_pinterest_button']) {
		$code .= '<amp-social-share type="pinterest" width="300" height="44">'.__('Share on Pinterest','DigiPress').'</amp-social-share>';
	}
	if ($options['show_pocket_button']) {
		$code .= '<a href="https://getpocket.com/edit?url='.urlencode($url).'" class="amp-social-share pocket" target="_blank"><amp-img src="data:image/svg+xml;base64,PHN2ZyB2ZXJzaW9uPSIxLjEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgd2lkdGg9IjE2IiBoZWlnaHQ9IjE2IiB2aWV3Qm94PSIwIDAgMTYgMTYiPjx0aXRsZT5wb2NrZXQ8L3RpdGxlPjxwYXRoIGQ9Ik04IDE1cS0xLjM3NSAwLTIuNTMxLTAuMjV0LTIuMTg4LTAuODQ0LTEuNzQyLTEuNS0xLjEyNS0yLjI4MS0wLjQxNC0zLjEyNXYtNHEwLTAuODI4IDAuNTg2LTEuNDE0dDEuNDE0LTAuNTg2aDEycTAuODI4IDAgMS40MTQgMC41ODZ0MC41ODYgMS40MTR2NHEwIDEuNzUtMC40MTQgMy4xMjV0LTEuMTI1IDIuMjgxLTEuNzQyIDEuNS0yLjE4OCAwLjg0NC0yLjUzMSAwLjI1ek0xMi42MTcgNS4zNTJxLTAuMzgzLTAuMzY3LTAuOTMtMC4zNjd0LTAuOTIyIDAuMzU5bC0yLjc2NiAyLjY0MS0yLjc4MS0yLjY0MXEtMC4zNzUtMC4zNTktMC45MTQtMC4zNTl0LTAuOTIyIDAuMzY3LTAuMzgzIDAuODgzIDAuMzc1IDAuODc1bDMuNzAzIDMuNTE2cTAuMzc1IDAuMzc1IDAuOTE0IDAuMzc1dDAuOTMtMC4zNzVsMy42ODgtMy41MTZxMC4zOTEtMC4zNTkgMC4zOTEtMC44NzV0LTAuMzgzLTAuODgzeiIgZmlsbD0id2hpdGUiPjwvcGF0aD48L3N2Zz4=" width="24" height="24" class="amp-social-icon"></amp-img>'.__('Share on Pocket','DigiPress').'</a>';
	}
	if ($options['show_feedly_button']) {
		$code .= '<a href="https://feedly.com/i/subscription/feed/'.$rss_url.'" class="amp-social-share feedly" target="_blank"><amp-img src="data:image/svg+xml;base64,PHN2ZyB2ZXJzaW9uPSIxLjEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgd2lkdGg9IjMyIiBoZWlnaHQ9IjMyIiB2aWV3Qm94PSIwIDAgMzIgMzIiPjx0aXRsZT5mZWVkbHk8L3RpdGxlPjxwYXRoIGQ9Ik0xOC4zNzkgMi45NzZjLTEuMzU5LTEuMzU5LTMuNTgzLTEuMzU5LTQuOTQyIDBsLTEyLjQxOCAxMi40MThjLTEuMzU5IDEuMzU5LTEuMzU5IDMuNTgzIDAgNC45NDJsOC44NjEgOC44NjFjMC42MTcgMC41MzcgMS40MjQgMC44NjMgMi4zMDYgMC44NjNoNy40NDRjMC45NyAwIDEuODQ4LTAuMzkzIDIuNDg0LTEuMDI5bDguNjg5LTguNjg5YzEuMzU5LTEuMzU5IDEuMzU5LTMuNTgzIDAtNC45NDJsLTEyLjQyNS0xMi40MjR6TTkuMzYxIDE5LjQ2NGMtMC4wOTEgMC4wOTEtMC4yMTYgMC4xNDctMC4zNTQgMC4xNDdoLTEuMDYyYy0wLjEyNiAwLTAuMjQxLTAuMDQ3LTAuMzI5LTAuMTIzbC0xLjI2NC0xLjI2NGMtMC4xOTQtMC4xOTQtMC4xOTQtMC41MTEgMC0wLjcwNWw5LjIwNC05LjIwNGMwLjE5NC0wLjE5NCAwLjUxMS0wLjE5NCAwLjcwNSAwbDEuNzczIDEuNzczYzAuMTk0IDAuMTk0IDAuMTk0IDAuNTExIDAgMC43MDVsLTguNjcyIDguNjcyek0xOC4wMzMgMjUuNjU3bC0xLjI0IDEuMjRjLTAuMDkxIDAuMDkxLTAuMjE2IDAuMTQ3LTAuMzU0IDAuMTQ3aC0xLjA2MmMtMC4xMjYgMC0wLjI0MS0wLjA0Ny0wLjMyOS0wLjEyNGwtMS4yNjQtMS4yNjRjLTAuMTk0LTAuMTk0LTAuMTk0LTAuNTExIDAtMC43MDVsMS43NzItMS43NzJjMC4xOTQtMC4xOTQgMC41MTEtMC4xOTQgMC43MDUgMGwxLjc3MyAxLjc3M2MwLjE5NCAwLjE5NCAwLjE5NCAwLjUxMSAwIDAuNzA1ek0xOC4wMzMgMTguMjI0bC00Ljk1NiA0Ljk1NmMtMC4wOTEgMC4wOTEtMC4yMTYgMC4xNDctMC4zNTQgMC4xNDdoLTEuMDYyYy0wLjEyNiAwLTAuMjQxLTAuMDQ3LTAuMzI5LTAuMTIzbC0xLjI2NC0xLjI2NGMtMC4xOTQtMC4xOTQtMC4xOTQtMC41MTEgMC0wLjcwNWw1LjQ4OC01LjQ4OGMwLjE5NC0wLjE5NCAwLjUxMS0wLjE5NCAwLjcwNSAwbDEuNzcyIDEuNzczYzAuMTk0IDAuMTk0IDAuMTk0IDAuNTExIDAgMC43MDV6IiBmaWxsPSJ3aGl0ZSI+PC9wYXRoPjwvc3ZnPg==" width="24" height="24" class="amp-social-icon"></amp-img>'.__('Subscribe on Feedly','DigiPress').'</a>';
	}
	if ($options['show_line_button']) {
		$code .= '<a href="http://line.me/R/msg/text/?'.$title.'%3A'.urlencode($url).'" class="amp-social-share line"><amp-img src="data:image/svg+xml;utf8;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iaXNvLTg4NTktMSI/Pgo8IS0tIEdlbmVyYXRvcjogQWRvYmUgSWxsdXN0cmF0b3IgMTguMC4wLCBTVkcgRXhwb3J0IFBsdWctSW4gLiBTVkcgVmVyc2lvbjogNi4wMCBCdWlsZCAwKSAgLS0+CjwhRE9DVFlQRSBzdmcgUFVCTElDICItLy9XM0MvL0RURCBTVkcgMS4xLy9FTiIgImh0dHA6Ly93d3cudzMub3JnL0dyYXBoaWNzL1NWRy8xLjEvRFREL3N2ZzExLmR0ZCI+CjxzdmcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgdmVyc2lvbj0iMS4xIiBpZD0iQ2FwYV8xIiB4PSIwcHgiIHk9IjBweCIgdmlld0JveD0iMCAwIDI5Ni41MjggMjk2LjUyOCIgc3R5bGU9ImVuYWJsZS1iYWNrZ3JvdW5kOm5ldyAwIDAgMjk2LjUyOCAyOTYuNTI4OyIgeG1sOnNwYWNlPSJwcmVzZXJ2ZSIgd2lkdGg9IjI0cHgiIGhlaWdodD0iMjRweCI+CjxnPgoJPHBhdGggZD0iTTI5NS44MzgsMTE1LjM0N2wwLjAwMy0wLjAwMWwtMC4wOTItMC43NmMtMC4wMDEtMC4wMTMtMC4wMDItMC4wMjMtMC4wMDQtMC4wMzZjLTAuMDAxLTAuMDExLTAuMDAyLTAuMDIxLTAuMDA0LTAuMDMyICAgbC0wLjM0NC0yLjg1OGMtMC4wNjktMC41NzQtMC4xNDgtMS4yMjgtMC4yMzgtMS45NzRsLTAuMDcyLTAuNTk0bC0wLjE0NywwLjAxOGMtMy42MTctMjAuNTcxLTEzLjU1My00MC4wOTMtMjguOTQyLTU2Ljc2MiAgIGMtMTUuMzE3LTE2LjU4OS0zNS4yMTctMjkuNjg3LTU3LjU0OC0zNy44NzhjLTE5LjEzMy03LjAxOC0zOS40MzQtMTAuNTc3LTYwLjMzNy0xMC41NzdjLTI4LjIyLDAtNTUuNjI3LDYuNjM3LTc5LjI1NywxOS4xOTMgICBDMjMuMjg5LDQ3LjI5Ny0zLjU4NSw5MS43OTksMC4zODcsMTM2LjQ2MWMyLjA1NiwyMy4xMTEsMTEuMTEsNDUuMTEsMjYuMTg0LDYzLjYyMWMxNC4xODgsMTcuNDIzLDMzLjM4MSwzMS40ODMsNTUuNTAzLDQwLjY2ICAgYzEzLjYwMiw1LjY0MiwyNy4wNTEsOC4zMDEsNDEuMjkxLDExLjExNmwxLjY2NywwLjMzYzMuOTIxLDAuNzc2LDQuOTc1LDEuODQyLDUuMjQ3LDIuMjY0YzAuNTAzLDAuNzg0LDAuMjQsMi4zMjksMC4wMzgsMy4xOCAgIGMtMC4xODYsMC43ODUtMC4zNzgsMS41NjgtMC41NywyLjM1MmMtMS41MjksNi4yMzUtMy4xMSwxMi42ODMtMS44NjgsMTkuNzkyYzEuNDI4LDguMTcyLDYuNTMxLDEyLjg1OSwxNC4wMDEsMTIuODYgICBjMC4wMDEsMCwwLjAwMSwwLDAuMDAyLDBjOC4wMzUsMCwxNy4xOC01LjM5LDIzLjIzMS04Ljk1NmwwLjgwOC0wLjQ3NWMxNC40MzYtOC40NzgsMjguMDM2LTE4LjA0MSwzOC4yNzEtMjUuNDI1ICAgYzIyLjM5Ny0xNi4xNTksNDcuNzgzLTM0LjQ3NSw2Ni44MTUtNTguMTdDMjkwLjE3MiwxNzUuNzQ1LDI5OS4yLDE0NS4wNzgsMjk1LjgzOCwxMTUuMzQ3eiBNOTIuMzQzLDE2MC41NjFINjYuNzYxICAgYy0zLjg2NiwwLTctMy4xMzQtNy03Vjk5Ljg2NWMwLTMuODY2LDMuMTM0LTcsNy03YzMuODY2LDAsNywzLjEzNCw3LDd2NDYuNjk2aDE4LjU4MWMzLjg2NiwwLDcsMy4xMzQsNyw3ICAgQzk5LjM0MywxNTcuNDI3LDk2LjIwOSwxNjAuNTYxLDkyLjM0MywxNjAuNTYxeiBNMTE5LjAzLDE1My4zNzFjMCwzLjg2Ni0zLjEzNCw3LTcsN2MtMy44NjYsMC03LTMuMTM0LTctN1Y5OS42NzUgICBjMC0zLjg2NiwzLjEzNC03LDctN2MzLjg2NiwwLDcsMy4xMzQsNyw3VjE1My4zNzF6IE0xODIuMzA0LDE1My4zNzFjMCwzLjAzMy0xLjk1Myw1LjcyMS00LjgzOCw2LjY1OCAgIGMtMC43MTIsMC4yMzEtMS40NDEsMC4zNDMtMi4xNjEsMC4zNDNjLTIuMTk5LDAtNC4zMjMtMS4wMzktNS42NjYtMi44ODhsLTI1LjIwNy0zNC43MTd2MzAuNjA1YzAsMy44NjYtMy4xMzQsNy03LDcgICBjLTMuODY2LDAtNy0zLjEzNC03LTd2LTUyLjE2YzAtMy4wMzMsMS45NTMtNS43MjEsNC44MzgtNi42NThjMi44ODYtMC45MzYsNi4wNDUsMC4wOSw3LjgyNywyLjU0NWwyNS4yMDcsMzQuNzE3Vjk5LjY3NSAgIGMwLTMuODY2LDMuMTM0LTcsNy03YzMuODY2LDAsNywzLjEzNCw3LDdWMTUzLjM3MXogTTIzMy4zMTEsMTU5LjI2OWgtMzQuNjQ1Yy0zLjg2NiwwLTctMy4xMzQtNy03di0yNi44NDdWOTguNTczICAgYzAtMy44NjYsMy4xMzQtNyw3LTdoMzMuNTdjMy44NjYsMCw3LDMuMTM0LDcsN3MtMy4xMzQsNy03LDdoLTI2LjU3djEyLjg0OWgyMS41NjJjMy44NjYsMCw3LDMuMTM0LDcsN2MwLDMuODY2LTMuMTM0LDctNyw3ICAgaC0yMS41NjJ2MTIuODQ3aDI3LjY0NWMzLjg2NiwwLDcsMy4xMzQsNyw3UzIzNy4xNzcsMTU5LjI2OSwyMzMuMzExLDE1OS4yNjl6IiBmaWxsPSIjRkZGRkZGIi8+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPC9zdmc+Cg==" width="24" height="24" class="amp-social-icon"></amp-img>'.__('Share on LINE','DigiPress').'</a>';
	}
	$code .= '</div></amp-lightbox>';
	echo $code;
}
/**
 * [dp_amp_add_bottom_bar_btn_side_menu description]
 * @return [type] [description]
 */
function dp_amp_add_bottom_bar_btn_side_menu(){
	echo '<div role="button" on="tap:g_menu_wrapper.toggle" tabindex="0" class="btbar_btn abs menu"><i class="icon-spaced-menu"></i><span class="cap">'.__('Menu', 'DigiPress').'</span></div>';
}
/**
 * [dp_amp_add_bottom_bar_btn_search description]
 * @return [type] [description]
 */
function dp_amp_add_bottom_bar_btn_search(){
	echo '<div role="button" on="tap:hd_srch_form" tabindex="0" class="btbar_btn abs search"><i class="icon-search"></i><span class="cap">'.__('Search', 'DigiPress').'</span></div>';
}
/**
 * [dp_amp_add_bottom_bar_btn_sns_share description]
 * @return [type] [description]
 */
function dp_amp_add_bottom_bar_btn_sns_share(){
	echo '<li><div role="button" on="tap:single_sns_share" tabindex="0" class="btbar_btn sns"><i class="icon-share"></i><span class="cap">'.__('Share', 'DigiPress').'</span></div></li>';
}
/**
 * Add buttons into bottom bar
 * @return [type] [description]
 */
function dp_amp_insert_bottom_bar(){
	global $options;
	if ($options['show_global_menu_search']) {
		echo '<div role="button" on="tap:hd_srch_form" tabindex="1" class="btbar_btn abs search"><i class="icon-search"></i><span class="cap">'.__('Search', 'DigiPress').'</span></div>';
	}
}