<?php
$site_title = dp_h1_title();
// ***********************************
// Content bottom widget
// ***********************************
if (is_active_sidebar('widget-bottom-container-amp')) : ?>
<div class="ctn-wdgt bottom clearfix"><?php dynamic_sidebar( 'widget-bottom-container-amp' ); ?></div><?php
endif; ?>
</main><?php
if ( !is_home() ){
	dp_breadcrumb();
}?>
<footer class="footer"><?php // end of #content
// ***********************************
// Footer widget
// ***********************************
if (is_active_sidebar('footer-amp') && !is_404()) : ?><div id="ft-widget-content" class="clearfix"><?php dynamic_sidebar( 'footer-amp' ); ?></div><?php
endif;
// ***********************************
// Footer menu
// ***********************************
if (has_nav_menu('footer_menu_amp')) {
	wp_nav_menu(array(
		'theme_location'	=> 'footer_menu_amp',
		'menu_id'			=> 'f_menu',
		'menu_class'		=> 'f_menu',
		'depth'				=> 1,
		'walker'			=> new dp_custom_menu_walker()
	));
}
// **********************
// Fixed bottom bar
// **********************
$bt_prev_code = '';
$bt_next_code = '';
if (is_single()) {
	if ($post_type === 'post'){
		if (isset($prev_post) && !empty($prev_post)) {
			$nav_url_prev = get_permalink($prev_post->ID);
			$nav_title_prev = get_the_title($prev_post->ID);
			$bt_prev_code = '<li><a href="'.$nav_url_prev.'" title="'.$nav_title_prev.'" class="btbar_btn"><i class="icon-left-light"></i><span class="cap">'.__('Prev','DigiPress').'</span></a></li>';
		}
		if (isset($next_post) && !empty($next_post)) {
			$nav_url_next = get_permalink($next_post->ID);
			$nav_title_next = get_the_title($next_post->ID);
			$bt_next_code = '<li><a href="'.$nav_url_next.'" title="'.$nav_title_next.'" class="btbar_btn"><i class="icon-right-light"></i><span class="cap">'.__('Next','DigiPress').'</span></a></li>';
		}
	}
}?>
<div class="footer-bottom">&copy; <?php 
if ($options['blog_start_year'] !== '') {
	echo $options['blog_start_year'] . '-' . date('Y');
} else {
	echo date('Y');
} ?> <a href="<?php echo home_url(); ?>/"><small><?php echo $site_title; ?></small></a>
</div></footer></div><?php // end of #main-wrap ?>
<div class="site-bar bottom-bar"><?php do_action('dp_amp_bottom_bar'); ?>
<ul><?php 
echo $bt_prev_code;
do_action('dp_amp_bottom_bar_li_first');?><li><a href="<?php echo home_url(); ?>" class="btbar_btn gohome"><i class="icon-home"></i><span class="cap"><?php echo _e('Home','DigiPress'); ?></span></a></li><li><a href="#main-body" class="btbar_btn"><i class="icon-up-open"></i><span class="cap"><?php echo _e('Go top','DigiPress'); ?></span></a></li><?php 
do_action('dp_amp_bottom_bar_li_last');
echo $bt_next_code; ?></ul></div>
<?php do_action('dp_amp_footer');?>
</body></html>