<?php
// ************************************************
// Get author profile
// 
// @return string or array
// ************************************************
function dp_get_author_info($args = array(
				'return_array' 	=> false,
				'user_id' 		=> null,
				'is_microdata' 	=> true,
				'avatar_size' 	=> 300) ) {
	global $post, $options, $IS_MOBILE_DP;

	$mb_suffix	= '';
	if ((bool)$IS_MOBILE_DP) {
		$mb_suffix = '_mb';
	}

	extract($args);

	// User ID
	$user_id 		= empty($user_id) ? get_the_author_meta('ID') : $user_id;
	if (empty($user_id)) return;

	$prof_code 			= '';
	$author_roles 		= '';
	$author_sns_code 	= '';

	$mi_name 	= '';
	$mi_photo 	= '';
	$mi_aff		= '';
	$mi_role 	= '';
	$mi_url 	= '';
	$mi_scope 	= '';

	// Microdata
	if ($is_microdata) {
		$mi_name 	= ' itemprop="name"';
		$mi_photo 	= ' itemprop="photo"';
		$mi_aff		= ' itemprop="affiliation"';
		$mi_role 	= ' itemprop="role"';
		$mi_url 	= ' itemprop="url"';
		$mi_scope 	= ' itemscope itemtype="http://data-vocabulary.org/Person"';
	}

	// Profile data
	$author_name 	= '<span'.$mi_name.' class="author_name">'.get_the_author_meta('display_name', $user_id).'</span>';
	$profile_img = '<a href="'.get_author_posts_url($user_id).'" rel="author" title="'.__('Show articles of this user.', 'DigiPress').'" class="author_img">'.get_avatar($user_id, $avatar_size, '', 'avatar', array('extra_attr' => $mi_photo)).'</a>';

	$author_org 	= get_the_author_meta('org', $user_id);
	$author_org 	= empty($author_org) ? '' : '<span'.$mi_aff.' class="author_org">'.$author_org.'</span>';
	
	$author_role 	= get_the_author_meta('title', $user_id);
	$author_role 	= empty($author_role) ? '' : '<span'.$mi_role.' class="author_role">'.$author_role.'</span>';
	
	$author_roles 	= '<div class="author_roles">'.$author_org.$author_role.$author_name.'</div>';

	$author_site_name = get_the_author_meta('site_name', $user_id);
	$author_url 	= get_the_author_meta('user_url', $user_id);
	$author_site_name = empty($author_site_name) ? $author_url : $author_site_name;
	$author_url 	= empty($author_url) ? '' : '<span class="author_url icon-bookmark"><a href="'.$author_url.'"'.$mi_url.' target="_blank">'.$author_site_name.'</a></span>';

	$author_desc 	= get_the_author_meta('description', $user_id);
	$author_desc 	= empty($author_desc) ? '' : '<div class="author_desc">'.str_replace(array("\r\n","\r","\n","\t"), '<br />', $author_desc).'</div>';

	$author_bg_img 	= get_the_author_meta('bg_img', $user_id);
	$author_bg_img 	= empty($author_bg_img) ? '' : ' style="background:url('.$author_bg_img.') center center;background-size:cover;"';
	
	$facebook_url 	= get_the_author_meta('facebook', $user_id);
	$facebook_url 	= empty($facebook_url) ? '' : '<a href="'.$facebook_url.'" title="Go to Facebook" target="_blank"><span class="r-wrap"><i class="icon-facebook"></i></span></a>';
	$twitter_url 	= get_the_author_meta('twitter', $user_id);
	$twitter_url 	= empty($twitter_url) ? '' : '<a href="'.$twitter_url.'" title="Go to Twitter" target="_blank"><span class="r-wrap"><i class="icon-twitter"></i></span></a>';
	$gplus_url 		= get_the_author_meta('gplus', $user_id);
	$gplus_url 		= empty($gplus_url) ? '' : '<a href="'.$gplus_url.'" title="Go to Google+" target="_blank"><span class="r-wrap"><i class="icon-gplus"></i></span></a>';
	$youtube_url 	= get_the_author_meta('youtube', $user_id);
	$youtube_url 	= empty($youtube_url) ? '' : '<a href="'.$youtube_url.'" title="Go to YouTube" target="_blank"><span class="r-wrap"><i class="icon-youtube"></i></span></a>';
	$flickr_url 	= get_the_author_meta('flickr', $user_id);
	$flickr_url 	= empty($flickr_url) ? '' : '<a href="'.$flickr_url.'" title="Goto Flickr" target="_blank"><span class="r-wrap"><i class="icon-flickr"></i></span></a>';
	$instagram_url 	= get_the_author_meta('instagram', $user_id);
	$instagram_url 	= empty($instagram_url) ? '' : '<a href="'.$instagram_url.'" title="Go to Instagram" target="_blank"><span class="r-wrap"><i class="icon-instagram"></i></span></a>';
	$pinterest_url 	= get_the_author_meta('pinterest', $user_id);
	$pinterest_url 	= empty($pinterest_url) ? '' : '<a href="'.$pinterest_url.'" title="Go to Pinterest" target="_blank"><span class="r-wrap"><i class="icon-pinterest"></i></span></a>';

	if (!empty($facebook_url) || !empty($twitter_url) || !empty($gplus_url) || !empty($youtube_url) || !empty($flickr_url) || !empty($instagram_url) || !empty($pinterest_url)) {
		$author_sns_code = '<div class="author_sns"><span class="sns_follow">Follow :</span>'.$facebook_url.$twitter_url.$gplus_url.$youtube_url.$flickr_url.$instagram_url.$pinterest_url.'</div>';
	}


	// Return value
	if ((bool)$return_array) {
		$prof_code = array(
			'author_name' 		=> $author_name,
			'author_org' 		=> $author_org,
			'author_role'		=> $author_role,
			'author_roles'		=> $author_roles,
			'author_posts_url' 	=> get_author_posts_url($user_id),
			'author_url'		=> $author_url,
			'author_desc'		=> $author_desc,
			'author_bg_img'		=> $author_bg_img,
			'author_sns_code'	=> $author_sns_code,
			'profile_img'		=> $profile_img
			);
	} else {
		$prof_code = '<section'.$mi_scope.' class="archive-title-sec author"'.$author_bg_img.'>'.$profile_img.'<div class="author_meta">'.$author_roles.$author_desc.$author_url.$author_sns_code.'</div></section>';
	}
	return $prof_code;
}


// ************************************************
// Get author's posts
//
// ************************************************
function dp_get_author_posts() {
	global $post, $options, $options_visual, $COLUMN_NUM, $IS_MOBILE_DP;
	if (post_password_required()) return;

	$post_num 	= 3;
	$width 		= 400;
	$height 	= 320;
	$cats 		= '';
	$cats_code 	= '';
	$more_code 	= '';
	$articles_code = '';

	$mb_suffix	= '';
	if ((bool)$IS_MOBILE_DP) {
		$mb_suffix = '_mb';
	}
	// wow.js
	$wow_title_css 	= '';
	$wow_posts_css	= '';
	if (!(bool)$options['disable_wow_js'.$mb_suffix]){
		$wow_title_css 	= ' wow fadeInLeft';
		$wow_posts_css	= ' wow fadeInUp';
	}
	$arg_thumb = array('width' => $width, 'height' => $height, "size"=>"large", "if_img_tag"=> true);

	// Title
	$recent_title = $options['author_recent_articles_title'];
	if (!empty($recent_title)) {
		$recent_title = '<h3 class="inside-title'.$wow_title_css.'"><span>'.$recent_title.'</span></h3>';
	}
	// Author ID
	$user_id 		= get_the_author_meta('ID');

	// Column
	$col_css	= ' two-col';
	if ($COLUMN_NUM === 1 || get_post_meta(get_the_ID(), 'disable_sidebar', true)) {
		$col_css	= ' one-col';
		$post_num 	= 4;
	}
	// Mobile
	if ($IS_MOBILE_DP) {
		$post_num = 4;
		$col_css  = '';
	}

	// Params
	$args = array(
				'author'		=> get_the_author_meta('ID'),
				'numberposts'	=> $post_num,
				'exclude'		=> $post->ID
				);

	// Get posts
	$articles =  get_posts($args);

	// Display
	if ($articles) {
		// Prefix
		$articles_code = '<div class="dp_related_posts clearfix horizontal'.$col_css.'">'. $recent_title.'<ul>';
		// Loop
		foreach ( $articles as $post ) : setup_postdata( $post );
			// Title
			$title = the_title('', '', false);
			if (mb_strlen($title, 'utf-8') > 52) $title = mb_substr($title, 0, 51, 'utf-8').'...';

			// Post options
			$dp_hide_date 	= get_post_meta(get_the_ID(), 'dp_hide_date', true);

			// ************* 
			// HTML
			// *************
			$articles_code .= '<li class="clearfix'.$wow_posts_css.'">';
			// Thumbnail
			$articles_code .= '<div class="widget-post-thumb"><a href="'.get_the_permalink().'" title="'.the_title_attribute(array('before'=>'','after'=>'','echo'=>false)).'">'.show_post_thumbnail($arg_thumb).'</a></div>';
			$articles_code .= '<div class="r-div has_thumb">';
			// Date
			if ( !(bool)get_post_meta(get_the_ID(), 'dp_hide_date', true) ) {
				$articles_code .= '<div class="meta-date"><time datetime="'.get_the_date('c').'" pubdate="pubdate">'.get_the_date().'</time></div>';
			}
			// Category
			$cats = get_the_category();
			if ( $cats ) {
				$cats = $cats[0];
				$cat_color_class = "cat-color".$cats->cat_ID;
				$cats_code = '<a href="'.get_category_link($cats->cat_ID).'" rel="tag" class="'.$cat_color_class.'">' .$cats->cat_name.'</a>';
			}
			$articles_code .= '<div class="meta-cat">'.$cats_code.'</div>';
			// Title
			$articles_code .= '<h4><a href="'.get_the_permalink().'" title="'.the_title_attribute(array('before'=>'','after'=>'','echo'=>false)).'" class="item-link">'.$title.'</a></h4>';
			$articles_code .= '</div>'; // end of .r-div
			// Close list
			$articles_code .= '</li>';

		endforeach; 
		wp_reset_postdata();

		// More link
		$more_code = '<div class="more-entry-link"><a href="'.get_author_posts_url($user_id).'" rel="author" title="'.__('Show articles of this user.', 'DigiPress').'"><span>'.get_the_author_meta('display_name').__('\'s articles','DigiPress').'</span></a></div>';

		// Suffix
		$articles_code .= '</ul>'.$more_code.'</div>';
	}

	return $articles_code;
}