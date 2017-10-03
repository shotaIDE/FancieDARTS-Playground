<div class="r_block">
<?php
// **********************************
// SNS icon and search form
// **********************************
if ($options['global_menu_right_content'] !== 'none') :
?>
<div id="hd_misc_div"<?php echo ' class="hd_misc_div'.$wow_title_css.'"'.$attr_delay_2; ?>>
<?php
	if ($options['global_menu_right_content'] === 'sns') {
		$sns_code = '';
		// **********************************
		// SNS icon links
		// **********************************
		if ($options['show_global_menu_sns']) {
			$sns_code = $options['global_menu_fb_url'] ? '<li class="fb"><a href="' . $options['global_menu_fb_url'] . '" title="Share on Facebook" target="_blank"><span class="r-wrap"><i class="icon-facebook"></i></span></a></li>' : '';
			$sns_code .= $options['global_menu_twitter_url'] ? '<li class="tw"><a href="' . $options['global_menu_twitter_url'] . '" title="Follow on Twitter" target="_blank"><span class="r-wrap"><i class="icon-twitter"></i></span></a></li>' : '';
			$sns_code .= $options['global_menu_gplus_url'] ? '<li class="gplus"><a href="' . $options['global_menu_gplus_url'] . '" title="Google+" target="_blank"><span class="r-wrap"><i class="icon-gplus"></i></span></a></li>' : '';
			$sns_code .= $options['global_menu_instagram_url'] ? '<li class="instagram"><a href="' . $options['global_menu_instagram_url'] . '" title="Instagram" target="_blank"><span class="r-wrap"><i class="icon-instagram"></i></span></a></li>' : '';
		}
		// **********************************
		// Feed icon
		// **********************************
		if ($options['show_global_menu_rss']) {
			$sns_code .= $options['rss_to_feedly'] ? '<li class="feedly"><a href="http://cloud.feedly.com/#subscription%2Ffeed%2F'.urlencode(get_feed_link()).'" target="blank" title="Follow on feedly"><span class="r-wrap"><i class="icon-feedly"></i></span></a></li>' : '<li class="rss"><a href="'. get_feed_link() .'" title="Subscribe Feed" target="_blank"><span class="r-wrap"><i class="icon-rss"></i></span></a></li>';
		}
		// Show
		if (!empty($sns_code)) {
			echo '<div id="hd_sns_links" class="hd_sns_links"><ul>'.$sns_code.'</ul></div>';
		}
		// **********************************
		// Search form 
		// **********************************
		if ($options['show_global_menu_search']) {
?>
<div id="hd_searchform"<?php if ($IS_MOBILE_DP) echo ' class="mb"'; ?>>
<?php
			if ($options['show_floating_gcs']) {
				// Google Custom Search
?>
<div id="dp_hd_gcs"><gcse:searchbox-only></gcse:searchbox-only></div>
<?php
			} else {
				// Default search form
?>
<span class="r-wrap"><i id="hd_search_btn" class="icon-search"></i></span>
<?php
			}
?>
</div>
<?php 
		}	// End of $options['show_global_menu_search']
	}
?>
<div id="expand_float_menu"><i><span>•</span></i></div>
</div><?php // End of .hd_misc_div 
endif;	// End of $options['global_menu_right_content'] !== 'none'

// ******************
// Default menu
// ******************
function show_wp_global_menu_list() { 
	// Global scope
	global $IS_MOBILE_DP;
?>
<ul id="global_menu_ul"><li class="menu-item"><a href="<?php echo home_url(); ?>" title="HOME" class="icon-home menu-link"><?php _e('HOME','DigiPress'); ?></a></li><li class="menu-item"><a href="<?php echo get_feed_link(); ?>" target="_blank" title="feed" class="icon-rss menu-link">RSS</a></li></ul>
<?php
} //End Function

// ******************
// Show Custom Menu
// ******************
if (function_exists('wp_nav_menu')) {
	$menu_num_class = '';
	// Code
	// **** Note: $wow~ and $attr_delay is setted in "site-header.php" ****
	echo '<nav id="global_menu_nav" class="global_menu_nav '.$wow_desc_css.'"'.$attr_delay_2.'>';
	wp_nav_menu(array(
		'theme_location'=> 'global_menu_ul',
		'container'	=> '',
		'after_only_parent_link' => '',
		'menu_id'	=> 'global_menu_ul',
		'menu_class'=> $IS_MOBILE_DP ? $menu_num_class . ' mb' : $menu_num_class,
		'fallback_cb'=> 'show_wp_global_menu_list',
		'walker'	=> new dp_custom_menu_walker()
	));
	echo '</nav>';
}
?>
</div><?php // End of .r_block