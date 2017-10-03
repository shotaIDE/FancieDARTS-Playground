<?php 
// **********************************
// Header
// **********************************
include_once(TEMPLATEPATH .'/'.DP_MOBILE_THEME_DIR.'/header.php');
// **********************************
// show posts
// **********************************
if ($options['show_specific_cat_index'] === 'page') :
	// Display the specific static page
	$page = get_post($options['specific_page_id']);
	$post_content = apply_filters('the_content', $page->post_content);
	echo '<article id="page-'.$options['specific_page_id'].'" class="single-article"><div class="entry entry-content">'.$post_content.'</div></article>';
else :
	if (have_posts()) :
		include (TEMPLATEPATH . "/article-loop.php");
	else :
		// Not found...
		include_once(TEMPLATEPATH .'/not-found.php');
	endif;	// End of have_posts()
endif;	// End of ($options['show_specific_cat_index'] === 'page') 
// **********************************
// Footer
// **********************************
include_once(TEMPLATEPATH .'/'.DP_MOBILE_THEME_DIR.'/footer.php');