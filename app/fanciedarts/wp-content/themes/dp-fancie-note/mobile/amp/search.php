<?php 
// **********************************
// Header
// **********************************
include_once(TEMPLATEPATH . "/".DP_MOBILE_THEME_DIR."/amp/header.php");
// **********************************
// show posts
// **********************************
if (have_posts() && !empty($_GET['s'])) :
	include (TEMPLATEPATH . "/article-loop.php");
else :
	// Not found...
	include_once(TEMPLATEPATH .'/'.DP_MOBILE_THEME_DIR.'/amp/not-found.php');
endif;	// End of have_posts()
// Footer
include_once(TEMPLATEPATH .'/'.DP_MOBILE_THEME_DIR.'/amp/footer.php');