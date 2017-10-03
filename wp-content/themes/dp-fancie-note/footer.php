</div><?php // end of .content-wap ?>
<?php
global $options, $options_visual;
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
if (is_active_sidebar('widget-container-bottom') && !is_404()) :
?>
<div id="widget-container-bottom" class="widget-container bottom clearfix">
<?php dynamic_sidebar( 'widget-container-bottom' ); ?>
</div>
<?php
endif;	// is_active_sidebar('widget-container-bottom')

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
</div><?php // end of .container ?>
<i id="gotop" class="pc icon-up-open"></i>
<?php
// **********************************
// Hidden Search form (Header trigger)
// **********************************
if ($options['show_global_menu_search'] && !$options['show_floating_gcs']) {
?>
<div id="hidden-search-wrapper"><form method="get" id="hidden-searchform" action="<?php echo esc_url(home_url('/')); ?>"><input type="search" id="hidden-searchtext" class="hidden-searchtext" name="s" placeholder="<?php esc_attr_e( 'Type to search...', 'DigiPress' ); ?>" required />
</form><i class="hidden-close-btn"></i></div>
<?php
}
// **********************************
// pace.js options (*Need before main script)
// **********************************?>
<script>paceOptions={restartOnRequestAfter:false};</script>
<?php
// **********************************
// WordPress Footer
// **********************************
wp_footer();
// **********************************
// wow.js
// **********************************
$wow_js = '';
if (!(bool)$options['disable_wow_js']){
	 $wow_js = 'new WOW().init();';
}
// **********************************
// Pace.js page loader option(inline) + wow js + parallax js
// **********************************
echo '<script>'.$wow_js.'</script>';
// **********************************
// js for slideshow
// **********************************
echo make_slider_js('#hd_slideshow');
// **********************************
// Autopager JS
// **********************************
showScriptForAutopager($wp_query->max_num_pages);
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