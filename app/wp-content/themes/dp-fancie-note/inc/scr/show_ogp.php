<?php
// ********************
// OGP & Twitter card
// ********************
function dp_show_ogp() {
	global $options;
	if ((bool)$options['disable_auto_ogp'] || class_exists('All_in_One_SEO_Pack')) return;

	$ogp_code = '';
	$ogp_image_sizes = '';
	$title = '';
	$desc = '';
	$url = '';
	$img_url= '';
	$fb_app_id = '';
	$twitter_card_code = '';
	$type= 'article';
	$site_name = dp_h1_title();

	if (is_single() || is_page()) {
		$title 	 = the_title_attribute(array('before'=> '', 
											 'after' => '', 
											 'echo' => false));
		$url 	 = get_permalink();
		$arg = array("width"=>1200, "height"=>630, "size"=>"full", "if_img_tag"=> false);
		$img_url = DP_Post_Thumbnail::get_post_thumbnail($arg);

	} else {
		$title = dp_site_title('', '', false);
		$url = is_ssl() ? 'https://' : 'http://';
		$url .= $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
		$img_url = $options['meta_ogp_img_url'];

		if (is_home() && is_front_page()) {
			$type= 'website';
		}
	}
	// og:image sizes
	if (!empty($img_url)){
		$ogp_image_sizes = dp_get_image_size($img_url);
		if (is_array($ogp_image_sizes)){
			$ogp_image_sizes = '<meta property="og:image:width" content="'.$ogp_image_sizes[0].'" /><meta property="og:image:height" content="'.$ogp_image_sizes[1].'" />';
		}
	}
	// FB App ID
	if (isset($options['fb_app_id']) && !empty($options['fb_app_id'])) {
		$fb_app_id = '<meta property="fb:app_id" content="'.$options['fb_app_id'].'" />';
	}
	// desc
	$desc = create_meta_desc_tag();

	// OGP tag
	$ogp_code = 
'<meta property="og:title" content="' . $title . '" /><meta property="og:type" content="' . $type . '" /><meta property="og:url" content="' . $url .'" /><meta property="og:image" content="' . $img_url . '" />'.$ogp_image_sizes.'<meta property="og:description" content="' . $desc . '" /><meta property="og:site_name" content="' . $site_name . '" />'.$fb_app_id;
	
	// twitter card tag
	if ( !empty($options['twitter_card_user_id']) ) {
		$twitter_card_code = '<meta name="twitter:card" content="summary_large_image" /><meta name="twitter:site" content="@'.$options['twitter_card_user_id'].'" />';
	}

	echo $ogp_code.$twitter_card_code;
}