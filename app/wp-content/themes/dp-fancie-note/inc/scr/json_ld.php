<?php 
function dp_json_ld(){
	if (is_single() || is_page()) :
		while (have_posts()) : the_post();
			global $options, $options_visual;
			$type = 'BlogPosting';
			$article_section = '';
			if (is_page()){
				$type = 'Article';
			} else if (is_single()){
				$cats = get_the_category();
				if (isset($cats[0])){
					$article_section = '"articleSection":"'.$cats[0]->cat_name.'",';
				}
			}
			// Get the post thumbnail
			$image_url = '';
			$image_path = '';
			$image_size = null;
			$image_w = 696;
			$image_h = 0;
			$logo_url = '';
			$logo_path = '';
			$logo_w = 600;
			$logo_h = 60;

			// Thumnbnail
			if (has_post_thumbnail()) {
				$image_id = get_post_thumbnail_id();
				$image_data = wp_get_attachment_image_src($image_id, array(1200,800), true);
				if (is_array($image_data)) {
					$image_url = is_ssl() ? str_replace('http:', 'https:', $image_data[0]) : $image_data[0];
					$image_w = ($image_data[1] < $image_w) ? $image_w : $image_data[1];
					$image_h = $image_data[2];
				}
			} else {
				$image_path = DP_THEME_DIR.'/img/post_thumbnail/noimage.png';
				$image_size = dp_get_image_size($image_path);
				if (isset($image_size) && is_array($image_size)){
					$image_w = ($image_size[0] < $image_w) ? $image_w : $image_size[0];
					$image_h = $image_size[1];
				}
			}

			// Logo image
			if (!empty($options_visual['dp_title_img'])) {
				$logo_url = $options_visual['dp_title_img'];
				$logo_url = is_ssl() ? str_replace('http:', 'https:', $logo_url) : $logo_url;
			} else {
				if (!empty($options['meta_ogp_img_url'])){
					$logo_url = $options['meta_ogp_img_url'];
				} else {
					$logo_url = DP_THEME_URI.'/img/post_thumbnail/noimage.png';
				}
			}
			$image_size = dp_get_image_size($logo_url);
			if (isset($image_size) && is_array($image_size)){
				$logo_w = ($image_size[0] > $logo_w) ? $logo_w : $image_size[0];
				$logo_h = ($image_size[1] > $logo_h) ? $logo_h : $image_size[1];
			}

			// json ld
			$json_ld_code = 
'<script type="application/ld+json">{
	"@context":"http://schema.org",
	"@type":"'.$type.'",
	"mainEntityOfPage":{
		"@type":"WebPage",
		"@id":"'.get_permalink().'"
	},
	"headline":"'.the_title('','',false).'",
	"image":{
		"@type":"ImageObject",
		"url":"'.$image_url.'",
		"width":'.$image_w.',
		"height":'.$image_h.'
	},
	"datePublished":"'.get_the_date('c').'",
	"dateModified":"'.get_the_modified_date('c').'",
	'.$article_section.'
	"author":{
		"@type":"Person",
		"name":"'.get_the_author_meta('display_name').'"
	},
	"publisher":{
		"@type":"Organization",
		"name":"'.get_bloginfo('name').'",
		"logo":{
			"@type":"ImageObject",
				"url":"'.$logo_url.'",
				"width":'.$logo_w.',
				"height":'.$logo_h.'
		}
	},
	"description":"'.mb_substr(strip_tags(get_the_excerpt()),0,60,'utf-8').'..."
}</script>';
			$json_ld_code = str_replace(array("\r\n","\r","\n","\t"), '', $json_ld_code);
			echo $json_ld_code;
		endwhile;
	endif;
}