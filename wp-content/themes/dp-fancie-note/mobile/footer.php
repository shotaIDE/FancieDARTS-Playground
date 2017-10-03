<?php
// **********************************
// Breadcrumb
// **********************************
dp_breadcrumb();
// ************
// Params
// ************
$show_header_class = '';
if (is_front_page() && !is_paged() &&  !isset( $_REQUEST['q']) ) {
	if ($options_visual['dp_header_content_type'] !== 'none'){
		$show_header_class = ' show-header';
	}
} 
// **********************************
// Container botom widget
// **********************************
if (is_active_sidebar('widget-container-bottom-mb') && !is_404()) :
?>
<div class="widget-container bottom mobile clearfix">
<?php dynamic_sidebar( 'widget-container-bottom-mb' ); ?>
</div>
<?php
endif;	// is_active_sidebar('widget-container-bottom-mb')

// **********************************
// Footer
// **********************************
?>
<footer id="footer" class="clearfix<?php echo $show_header_class; ?>">
<div class="ft-container">
<?php 
// **********************************
// show footer widgets and menu (footer_widgets.php)
// **********************************
dp_get_footer();
// **********************************
// Copyright
// **********************************
?>
<div class="copyright"><div class="inner">&copy; <?php 
if ($options['blog_start_year'] !== '') {
	echo $options['blog_start_year'] . ' - ' . date('Y');
} else {
	echo date('Y');
} ?> <a href="<?php echo home_url(); ?>/"><small><?php echo dp_h1_title(); ?></small></a>
</div></div>
</div>
</footer>
</div><?php // end of .container
// **********************
// Fixed bottom bar
// **********************
$bt_prev_code = '';
$bt_next_code = '';
$sl_menu_align = ' left';
if ($options['mb_slide_menu_position'] == 'right') {
	$sl_menu_align = ' right';
}
if (is_single()){
	if ($prev_post) {
		$nav_url_prev = get_permalink($prev_post->ID);
		$nav_title_prev = get_the_title($prev_post->ID);
		$bt_prev_code = '<li><a href="'.$nav_url_prev.'" title="'.$nav_title_prev.'" class="btbar_btn"><i class="icon-left-light"></i></a></li>';
	}
	if ($next_post) {
		$nav_url_next = get_permalink($next_post->ID);
		$nav_title_next = get_the_title($next_post->ID);
		$bt_next_code = '<li><a href="'.$nav_url_next.'" title="'.$nav_title_next.'" class="btbar_btn"><i class="icon-right-light"></i></a></li>';
	}
}
?>
<div id="bottom_bar" class="bottom_bar"><?php 
	echo '<a href="#global_menu_nav" class="menu_icon icon-spaced-menu'.$sl_menu_align.'"><span></span></a>';
?><ul><?php echo $bt_prev_code; ?><li><a href="<?php echo home_url(); ?>" class="btbar_btn gohome"><i class="icon-home"></i></a></li><li><div class="btbar_btn"><i id="gotop" class="mobile icon-up-open"></i></div></li><?php echo $bt_next_code; ?></ul></div><?php // end of .#bottom_bar ?>
</div><?php // end of .main-wrap
// **********************
// Global Menu(mmenu)
// **********************
include (TEMPLATEPATH . "/".DP_MOBILE_THEME_DIR."/global-menu.php");
// **********************************
// WordPress Footer
// **********************************
wp_footer();
// ***********************
// Slide menu JS
// ***********************
$position 	= 'position:"'.$options['mb_slide_menu_position'].'",';
$zposition 	= 'zposition:"'.$options['mb_slide_menu_zposition'].'"';
$mmenu_js = <<<_EOD_
j$(function(){
	j$("#global_menu_nav").mmenu({
		offCanvas:{
			$position
			$zposition
		}
	});
});
_EOD_;
$mmenu_js = str_replace(array("\r\n","\r","\n","\t"), '', $mmenu_js);
// **********************************
// wow.js
// **********************************
$wow_js = '';
if (!(bool)$options['disable_wow_js_mb']){
	 $wow_js = 'new WOW().init();';
}
// **********************************
// mmenu js + wow js + parallax js
// **********************************
echo '<script>'.$mmenu_js.$wow_js.$plx_js.'</script>';
// **********************************
// js for slideshow
// **********************************
echo make_slider_js('#hd_slideshow_mb');
// **********************************
// Autopager JS
// **********************************
showScriptForAutopager($wp_query->max_num_pages);
// **********************************
// Google Custom Search
// **********************************
if (!empty($options['gcs_id'])) :  ?>
<script>(function(){var cx='<?php echo $options['gcs_id']; ?>';var gcse=document.createElement('script');gcse.type='text/javascript';gcse.async=true;gcse.src=(document.location.protocol=='https:'?'https:':'http:')+'//cse.google.com/cse.js?cx='+cx;var s=document.getElementsByTagName('script')[0];s.parentNode.insertBefore(gcse,s);})();</script>
<?php 
endif;
// **********************************
// Javascript for sns
// **********************************
js_for_sns_objects();
// **********************************
// JSON-LD for Structured Data
// **********************************
dp_json_ld();
?>
</body>
</html>