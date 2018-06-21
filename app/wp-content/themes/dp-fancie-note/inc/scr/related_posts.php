<?php
function dp_get_related_posts() {
	global $post, $options, $options_visual, $COLUMN_NUM, $IS_MOBILE_DP, $IS_AMP_DP;

	if (post_password_required()) return;

	$post_type 				= get_post_type();
	$related_posts_style 	= $options['related_posts_style'];
	$related_posts_target 	= $options['related_posts_target'];
	// Main title
	$main_title = !empty($options['related_posts_title']) ? $options['related_posts_title'] : __('YOU MIGHT ALSO LIKE','DigiPress');

	$mb_suffix	= '';
	if ((bool)$IS_MOBILE_DP) {
		$mb_suffix = '_mb';
	}
	// wow.js
	$wow_title_css 			= '';
	$wow_article_css 		= '';
	if (!(bool)$options['disable_wow_js'.$mb_suffix] && !$IS_AMP_DP){
		$wow_title_css		= ' wow fadeInLeft';
		$wow_article_css 	= ' wow fadeInUp';
	}

	// Column
	$col_css	= ' two-col';
	if ($COLUMN_NUM === 1 || get_post_meta(get_the_ID(), 'disable_sidebar', true)) {
		$col_css	= ' one-col';
	} else if ($options_visual['dp_column'] == 3) {
		$col_css	= ' three-col';
	}

	$cat_code = '';


	// *****************************
	// Main display
	// *****************************
	if ($post_type !== 'post' && $post_type !== 'page' && $post_type !== 'attachment' && $post_type !== 'revision') :
		// ***********************************
		// Probably Custom post type
		// ***********************************
		/*
		// Get title
		$customPostTypeObj 		= get_post_type_object(get_post_type());
		$customPostTypeTitle 	= esc_html($customPostTypeObj->labels->name);
		// More link
		$more_url 				= get_post_type_archive_link($post_type);
		if ( !empty($more_url) ) {
			$more_url = '<div class="more-entry-link"><a href="'.$more_url.'"><span>'.__('See more', 'DigiPress').'</span></a></div>';
		}

		// Get posts
		$latest =  get_posts('numberposts='.$options['number_related_posts'].'&post_type='.$post_type.'&exclude='.$post->ID);?>
<aside class="dp_related_posts news<?php echo $col_css; ?>">
<h3 class="inside-title<?php echo $wow_title_css; ?>"><span><?php echo __('Other posts of ', 'DigiPress') . $customPostTypeTitle; ?></span></h3><ul><?php
		// Show posts of custom post type
		foreach( $latest as $post ) : setup_postdata($post); ?>
<li class="<?php echo $wow_article_css; ?>"><?php echo '<span class="meta-date">'.get_the_date().'</span>'; ?>
<a href="<?php the_permalink(); ?>" class="rel-post-title item-link" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></li><?php
		endforeach;
		wp_reset_postdata();?>
</ul><?php echo $more_url; ?>
</aside><?php*/
	elseif ((get_post_type() === 'post') && is_single() && $options['show_related_posts']) :
		// ***********************************
		// Probably single post
		// *********************************** 
		// Thumbnail size
		$width			= 800;
		$height			= 420;
		$if_img_tag = $IS_AMP_DP ? true : false;
		$arg_thumb = array("width"=>$width, "height"=>$height, "size"=>"large", "if_img_tag"=>$if_img_tag);
		$thumb_class	= '';
		$title_length 	= 38;
		$type = ' vertical';
		$title_length = 52;?>
<aside class="dp_related_posts clearfix<?php echo $type.$col_css; ?>">
<h3 class="inside-title<?php echo $wow_title_css; ?>"><span><?php echo $main_title; ?></span></h3><ul><?php
		// Get related posts
		$number_posts = $options['number_related_posts'];
		// Target
		if ($related_posts_target == 2) {
			$cat = get_the_category();
			$cat = $cat[0];
			$args = array(
				'numberposts'	=> $number_posts,
				'category'		=> $cat->cat_ID,
				'exclude'		=> $post->ID
				);

		} else if ($related_posts_target == 3) {
			$cat = get_the_category();
			$cat = $cat[0];
			$args = array(
				'numberposts'	=> $number_posts,
				'category'		=> $cat->cat_ID,
				'exclude'		=> $post->ID,
				'orderby'		=> 'rand'
				);

		} else {
			$tagIDs		= array();
			$tags		= wp_get_post_tags($post->ID);
			$tagcount 	= count($tags);
			for ($i = 0; $i < $tagcount; $i++) {
				$tagIDs[$i] = $tags[$i]->term_id;
			}
			$args = array(
				'tag__in'			=> $tagIDs,
				'post__not_in'		=> array($post->ID),
				'numberposts'		=> $number_posts,
				'exclude'			=> $post->ID,
				'ignore_sticky_posts'	=> 1
				);
		}

		// Thumbnail
		if ($options['related_posts_thumbnail']) {
			$thumb_class = ' has_thumb';
		}

		// Query
		$my_query = get_posts($args);

		// Display
		if ($my_query) :
			foreach ( $my_query as $post ) : setup_postdata( $post );
				// Title
				$title = the_title('', '', false);
				$anchor_code = 'href="'.get_the_permalink().'" title="'.the_title_attribute(array('before'=>'','after'=>'','echo'=>false)).'"';?>
<li class="<?php echo $wow_article_css.$thumb_class; ?>"><?php
				// Thumbnail
				if ($options['related_posts_thumbnail']) {
					$thumb_code = show_post_thumbnail($arg_thumb);
					$thumb_link_class = 'thumb-link';
					if (!$IS_AMP_DP) {
						echo '<div class="widget-post-thumb"><a '.$anchor_code.' class="'.$thumb_link_class.'"><figure class="post-thumb" style="background-image:url(\''.$thumb_code.'\');"></figure></a></div>';
					} else {
						echo '<a '.$anchor_code.' class="'.$thumb_link_class.'"><figure class="loop-post-thumb">'.$thumb_code.'</figure></a>';
					}
				}
				// Category
				if ($options['related_posts_category']) :
					$cats = get_the_category();
					if ($cats) {
						$cats = $cats[0];
						$cat_color_class = " cat-color".$cats->cat_ID;
						$cats_code = '<a href="'.get_category_link($cats->cat_ID).'" rel="tag" class="'.$cat_color_class.'">' .$cats->cat_name.'</a>';
					}
				endif;?>
<div class="excerpt_div<?php echo $thumb_class; ?>"><div class="meta-cat"><?php echo $cats_code; ?></div><h4 class="entry-title"><?php echo '<a '.$anchor_code.' class="'.$item_link_class.'">'.$title; ?></a></h4></div></li><?php
			endforeach;
			wp_reset_postdata();
		else :?>
<li><?php _e('No related posts yet.', 'DigiPress'); ?></li><?php
		endif;?></ul></aside><?php
	endif;	// End of "Main display"
}