<?php 
// **********************************
// Header (main-wrap > container > content)
// **********************************
get_header();
// **********************************
// show posts
// **********************************
if ($options['show_specific_cat_index'] === 'page') :
	// Display the specific static page
	$top_static_page = get_post((int)$options['specific_page_id']);
	$post_content = apply_filters('the_content', $top_static_page->post_content);
	echo '<article id="page-'.$options['specific_page_id'].'" class="single-article"><div class="entry entry-content">'.$post_content.'</div></article>';
else :
	if (have_posts()) :
		include (TEMPLATEPATH . "/article-loop.php");
	else :
		// Not found...
		include_once(TEMPLATEPATH .'/not-found.php');
	endif;	// End of have_posts()
endif;	// End of ($options['show_specific_cat_index'] === 'page') 
// ***********************************
// Content bottom widget
// ***********************************
if (is_active_sidebar('widget-content-bottom')) : ?>
<div class="widget-content bottom clearfix"><?php dynamic_sidebar( 'widget-content-bottom' ); ?>
</div><?php 
endif;?>
</div><?php // end of .content
// **********************************
// Sidebar
// **********************************
if ( !(is_front_page() && !is_paged() && !(bool)$options['show_top_under_content']) ) {
	if ( $COLUMN_NUM === 2 ) get_sidebar();
}
// **********************************
// Footer
// **********************************
get_footer();