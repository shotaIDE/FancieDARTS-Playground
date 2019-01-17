<?php
/**
 * DigiPress functions and definitions
 *
 * @package DigiPress
 */

$dp_theme_upload_dir = wp_upload_dir();
$upload_url = is_ssl() ? str_replace('http:', 'https:', $dp_theme_upload_dir['baseurl']) : $dp_theme_upload_dir['baseurl'];
$theme_url = is_ssl() ? str_replace('http:', 'https:', get_template_directory_uri()) : get_template_directory_uri();

//Version
define ('DP_OPTION_SPT_VERSION', '1.2.2.3');
//Base theme name
define('DP_THEME_NAME', "Fancie NOTE");
//Base theme key
define('DP_THEME_KEY', "fancie-note");
// Theme Slug
define ('DP_THEME_SLUG', 'dp_fancie_note');
//Theme ID
define('DP_THEME_ID', "DigiPress");
//Theme URI
define('DIGIPRESS_URI', "https://digipress.info/");
//Author URI
define('DP_AUTHOR_URI', "https://www.digistate.co.jp/");
//Theme Directory
define('DP_THEME_DIR', dirname(__FILE__));
//Theme Directory
define('DP_THEME_URI', $theme_url);
//Theme Directory for mobile
define('DP_MOBILE_THEME_DIR', 'mobile');
// Query for AMP
define('DP_AMP_QUERY_VAR', 'amp');
//Column Type(1, 2)
define('DP_COLUMN', '2');
// Theme Type
define('DP_BUTTON_STYLE', 'flat6');
//Original upload dir
define('DP_UPLOAD_DIR', $dp_theme_upload_dir['basedir'].'/digipress/'.DP_THEME_KEY);
//Original upload path
define('DP_UPLOAD_URI', $upload_url.'/digipress/'.DP_THEME_KEY);

//Load Theme Domain
load_theme_textdomain('DigiPress', get_template_directory().'/languages/');

/****************************************************************
* Load theme options/global
****************************************************************/
$options 	= get_option('dp_options');
$options_visual 	= get_option('dp_options_visual');

/****************************************************************
* GLOBALS
****************************************************************/
$EXIST_FB_LIKE_BOX 	= false;
$FB_APP_ID 	= '';
$COLUMN_NUM = '';
$SIDEBAR_FLOAT = '';
$SIDEBAR2_FLOAT 	= '';
$IS_MOBILE_DP = false;
$IS_AMP_DP = false;
$ARCHIVE_STYLE = array();
$AMP_LIB_CAROUSEL = $AMP_LIB_TWITTER = $AMP_LIB_FACEBOOK = $AMP_LIB_ADS = $AMP_LIB_YOUTUBE = $AMP_LIB_VIMEO = $AMP_LIB_SOUNDCLOUD = $AMP_LIB_INSTAGRAM = $AMP_LIB_PINTEREST = $AMP_LIB_IFRAME = $AMP_LIB_VIDEO = $AMP_LIB_AUDIO = $AMP_LIB_FORM = false;

/****************************************************************
* Include Main Class
****************************************************************/
require_once(ABSPATH . 'wp-admin/includes/file.php');
include_once(DP_THEME_DIR . "/inc/scr/theme_main_class.php");

/**
 * Load theme updater functions.
 * Action is used so that child themes can easily disable.
 */
function dp_prefix_theme_updater() {
	require( DP_THEME_DIR . '/inc/scr/updater/theme-updater.php' );
}
add_action( 'after_setup_theme', 'dp_prefix_theme_updater' );

/****************************************************************
* Include Function
****************************************************************/
require_once(ABSPATH . "/wp-admin/includes/template.php");
if (is_admin()) {
	include_once(DP_THEME_DIR . "/inc/scr/permission_check.php");
	include_once(DP_THEME_DIR . "/inc/admin/visual_params.php");
	include_once(DP_THEME_DIR . "/inc/admin/control_params.php");
	include_once(DP_THEME_DIR . "/inc/scr/create_css.php");
}
require_once(DP_THEME_DIR . "/inc/scr/get_uploaded_images.php");
include_once(DP_THEME_DIR . "/inc/scr/admin_menu_control.php");
require_once(DP_THEME_DIR . "/inc/scr/create_title_h1.php");
require_once(DP_THEME_DIR . "/inc/scr/create_title_desc.php");
require_once(DP_THEME_DIR . "/inc/scr/create_meta.php");
require_once(DP_THEME_DIR . "/inc/scr/count_sns.php");
require_once(DP_THEME_DIR . "/inc/scr/show_banner_contents.php");
require_once(DP_THEME_DIR . '/inc/scr/post_thumbnail.php');
require_once(DP_THEME_DIR . "/inc/scr/show_sns_icon.php");
require_once(DP_THEME_DIR . "/inc/scr/is_mobile_dp.php");
require_once(DP_THEME_DIR . "/inc/scr/amp_helper.php");
require_once(DP_THEME_DIR . "/inc/scr/breadcrumb.php");
require_once(DP_THEME_DIR . "/inc/scr/listing_post_styles.php");
require_once(DP_THEME_DIR . "/inc/scr/autopager.php");
require_once(DP_THEME_DIR . "/inc/scr/meta_info.php");
require_once(DP_THEME_DIR . "/inc/scr/widgets.php");
include_once(DP_THEME_DIR . "/inc/scr/widget_for_archive.php");
include_once(DP_THEME_DIR . "/inc/scr/custom_field.php");
include_once(DP_THEME_DIR . "/inc/scr/custom_menu.php");
require_once(DP_THEME_DIR . "/inc/scr/get_column_num.php");
require_once(DP_THEME_DIR . "/inc/scr/get_archive_style.php");
include_once(DP_THEME_DIR . "/inc/scr/placeholder.php");
require_once(DP_THEME_DIR . "/inc/scr/shortcodes.php");
require_once(DP_THEME_DIR . "/inc/scr/pagination.php");
include_once(DP_THEME_DIR . "/inc/scr/custom_post_type.php");
require_once(DP_THEME_DIR . "/inc/scr/show_ogp.php");
include_once(DP_THEME_DIR . "/inc/scr/disable_auto_format.php");
require_once(DP_THEME_DIR . "/inc/scr/js_for_sns_objects.php");
require_once(DP_THEME_DIR . "/inc/scr/footer_widgets.php");
include_once(DP_THEME_DIR . "/inc/scr/post_views.php");
require_once(DP_THEME_DIR . "/inc/scr/related_posts.php");
include_once(DP_THEME_DIR . "/inc/scr/widget_categories.php");
include_once(DP_THEME_DIR . "/inc/scr/widget_tag_cloud.php");
require_once(DP_THEME_DIR . "/inc/scr/json_ld.php");

/****************************************************************
* Set globals before the site is about to showing.
****************************************************************/
add_action('after_setup_theme', 'is_mobile_dp');
add_action('init', 'dp_amp_init');
add_action('wp', 'get_column_num');
add_action('wp', 'dp_get_archive_style');

/****************************************************************
* Add Theme Option into wp admin interfaces.
****************************************************************/

//Add option menu into admin panel header and insert CSS and scripts to DigiPress panel.
add_action('admin_menu', array('digipress_options', 'add_menu'));
add_action('admin_menu', array('digipress_options', 'update'));
add_action('admin_menu', array('digipress_options', 'update_visual'));
add_action('admin_menu', array('digipress_options', 'dp_run_upload_file'));
add_action('admin_menu', array('digipress_options', 'dp_delete_upload_file'));
add_action('admin_menu', array('digipress_options', 'edit_images'));
add_action('admin_menu', array('digipress_options', 'reset_theme_options'));
add_action('admin_menu', array('digipress_options', 'dp_export_all_settings'));
add_action('admin_menu', array('digipress_options', 'dp_import_all_settings'));

/****************************************************************
* Remove Wordpress theme customizer
****************************************************************/
function dp_theme_customize_remove($wp_customize){
	class DP_Customize_Control extends WP_Customize_Control {
		public $note = '';
		protected function render() {
			$id    = 'customize-control-' . str_replace( array( '[', ']' ), array( '-', '' ), $this->id );
			$hierarchy = isset($this->hierarchy) && !empty($this->hierarchy) ? ' sub-'.esc_attr($this->hierarchy) : '';
			$class = 'customize-control customize-control-' . $this->type.$hierarchy;?>
<li id="<?php echo esc_attr( $id ); ?>" class="<?php echo esc_attr( $class ); ?>"><?php echo $this->note;?></li><?php
		}
	}

	$wp_customize->remove_section('header_image');
	$wp_customize->remove_section('background_image');
	$wp_customize->remove_section('static_front_page');
	$wp_customize->remove_section('colors');
	$wp_customize->remove_section('custom_css');

	$wp_customize->add_section(
		'dp_goto_option_section', array(
		'title' => __('Customize Theme', 'DigiPress'),
		'priority' => 1
	));
	$wp_customize->add_setting(
		'dp_goto_option_section', array(
		'transport' => 'postMessage'
	));
	$wp_customize->add_control( new DP_Customize_Control(
		$wp_customize,
		'dp_goto_option_section', array(
		'note' => '<a href="admin.php?page=digipress" class="button">'.__('Customize this theme','DigiPress').'</a>',
		'section' => 'dp_goto_option_section',
		'type' => 'text'
		)
	));
}
add_action('customize_register','dp_theme_customize_remove');

/****************************************************************
* Insert custom field to editing post window.
* from custom_field.php
****************************************************************/
// Add custom fields
add_action('admin_menu', 'add_custom_field');
add_action('save_post', 'save_custom_field');
/* Add CSS into admin panel */
function add_css_for_admin() {
   echo '<link rel="stylesheet" type="text/css" href="'.get_template_directory_uri().'/inc/css/dp-admin.css">';
}
add_action('admin_head-post.php' , 'add_css_for_admin');
add_action('admin_head-post-new.php' , 'add_css_for_admin');



/****************************************************************
* Last Modified into HTTP Headers
****************************************************************/
function dp_get_last_modified_date(){
	$date = array(
		get_the_modified_time("Y"),
		get_the_modified_time("m"),
		get_the_modified_time("d")
	);
	$time = array(
		get_the_modified_time("H"),
		get_the_modified_time("i"),
		get_the_modified_time("s"),
	);
	$time_str = implode("-", $date)."T".implode(":", $time);
	return date_i18n( "r", strtotime($time_str) );
}

function dp_http_header_set(){
	if (is_singular()){
		$mod_time = get_the_modified_time('U');
		$last_mod = gmdate("D, d M Y H:i:s T", $mod_time);
		$etag = md5($last_mod.get_permalink());
		header(sprintf("Last-Modified: %s", $last_mod));
		header(sprintf("Etag: %s", $etag));
		// $if_modified_since = filter_input(INPUT_SERVER, 'HTTP_IF_MODIFIED_SINCE');
		// $if_none_match = filter_input(INPUT_SERVER, 'HTTP_IF_NONE_MATCH');
		// if ( $if_modified_since === $last_mod || $if_none_match === $etag ) {
		// 	header( 'HTTP', true, 304 );
		// 	exit;
		// }
	}
}
add_action( "wp", "dp_http_header_set", 1 );

/****************************************************************
* Action hook on wp_head
****************************************************************/
function dp_hook_wp_head(){
	global $options;

	// SNS
	$prefetch = '<link rel="dns-prefetch" href="//connect.facebook.net">
<link rel="dns-prefetch" href="//apis.google.com">
<link rel="dns-prefetch" href="//secure.gravatar.com">
<link rel="dns-prefetch" href="//query.yahooapis.com">
<link rel="dns-prefetch" href="//api.pinterest.com">
<link rel="dns-prefetch" href="//jsoon.digitiminimi.com">
<link rel="dns-prefetch" href="//b.hatena.ne.jp">
<link rel="dns-prefetch" href="//jsoon.digitiminimi.com">
<link rel="dns-prefetch" href="//platform.twitter.com">';
	echo str_replace(array("\r\n","\r","\n","\t","/^\s(?=\s)/"), '', $prefetch);

	if (isset($options['use_google_jquery']) && !empty($options['use_google_jquery'])) {
		echo '<link rel="dns-prefetch" href="//ajax.googleapis.com" />';
	}

	if (is_singular()){
		// Modified date
		$lm_date = dp_get_last_modified_date();
		echo '<meta http-equiv="Last-Modified" content="'.$lm_date.'" />';
	}
}
add_action( "wp_head", "dp_hook_wp_head" );

/****************************************************************
* Admin function
****************************************************************/
/* Disable admin bar */
// add_filter('show_admin_bar', '__return_false');
/* Disable admin notice for editors */
if (!current_user_can('edit_users')) {
	function dp_wphidenag() {
		remove_action( 'admin_notices', 'update_nag');
	}
	add_action('admin_menu','dp_wphidenag');
}

/****************************************************************
* Replace upload content url in SSL
****************************************************************/
function dp_replace_ssl_content($content){
	if (is_ssl()){
		$upload_dir = wp_upload_dir();
		$upload_dir_url = $upload_dir['baseurl'];
		$upload_dir_ssl_url = str_replace('http:', 'https:', $upload_dir_url);
		$content = str_replace($upload_dir_url, $upload_dir_ssl_url, $content);
	}
	return $content;
}
add_filter('the_content', 'dp_replace_ssl_content');

/****************************************************************
* Insert Ads in single post content
****************************************************************/
define('DP_H_TAG_REG', '/<h[1-6].*?>/i');
function dp_get_h_tag_position_in_content( $the_content ){
	if ( preg_match( DP_H_TAG_REG, $the_content, $h_tags )) {
		return $h_tags[0];
	}
}
function dp_insert_widget_before_first_h_tag($the_content) {
	global $IS_MOBILE_DP, $IS_AMP_DP;
	$middle_content = '';
	if (is_singular()){
		if (!$IS_MOBILE_DP) {
			ob_start();
			dynamic_sidebar('widget-post-middle');
			$middle_content = ob_get_contents();
			ob_end_clean();
		} else {
			if ($IS_AMP_DP) {
				ob_start();
				dynamic_sidebar('widget-post-middle-amp');
				$middle_content = ob_get_contents();
				ob_end_clean();
			} else {
				ob_start();
				dynamic_sidebar('widget-post-middle-mb');
				$middle_content = ob_get_contents();
				ob_end_clean();
			}
		}
	}
	$h_tag_position = dp_get_h_tag_position_in_content( $the_content );
	if ( $h_tag_position ) {
		$the_content = preg_replace(DP_H_TAG_REG, $middle_content.$h_tag_position, $the_content, 1);
	}
	return $the_content;
}
add_filter('the_content','dp_insert_widget_before_first_h_tag', 12);

/****************************************************************
* Avoid SSL at home url
****************************************************************/
function dp_ssl_home_url($url, $path = '', $orig_scheme = 'http'){
	if(is_ssl() && strpos($path, 'wp-content') === false){
		$url = str_replace('https:', 'http:', $url);
	}
	return $url;
}
// add_filter('home_url', 'dp_ssl_home_url');

/****************************************************************
* Disable self pinback
****************************************************************/
function dp_no_self_ping( &$links ) {
	$home = home_url();
	foreach ( $links as $l => $link )
	if ( 0 === strpos( $link, $home ) )
		unset($links[$l]);
}
add_action( 'pre_ping', 'dp_no_self_ping' );
/* Enable excerpt for single page */
add_post_type_support( 'page', 'excerpt' );

/****************************************************************
* Disable meta canonical
****************************************************************/
remove_action('wp_head', 'adjacent_posts_rel_link_wp_head');
remove_action('wp_head', 'rel_canonical');

/****************************************************************
* Disable oEmbed
****************************************************************/
if($options['disable_oembed']) {
	add_filter('embed_oembed_discover', '__return_false');
	remove_action('wp_head','rest_output_link_wp_head');
	remove_action('wp_head','wp_oembed_add_discovery_links');
	remove_action('wp_head','wp_oembed_add_host_js');
}
/**
 * Replace oEmbed style
 */
function dp_embed_styles(){
	wp_enqueue_style( 'wp-embed-template-org', DP_THEME_URI . '/inc/css/wp-embed-template.css' );
}
remove_action( 'embed_head', 'print_embed_styles' );
add_filter( 'embed_head', 'dp_embed_styles' );

/****************************************************************
* Upload image width
****************************************************************/
if ( !isset( $content_width ) ) $content_width = 900;

/****************************************************************
* For check the order of curret post
****************************************************************/
function dp_is_first(){
	global $wp_query;
	return ((int)$wp_query->current_post === 0) ? true : false;
}
function dp_is_last(){
	global $wp_query;
	return ((int)$wp_query->current_post + 1 === $wp_query->post_count) ? true : false;;
}
function dp_is_odd(){
	global $wp_query;
	return (((int)$wp_query->current_post + 1) % 2 === 1) ? true : false;;
}
function dp_is_even(){
	global $wp_query;
	return (((int)$wp_query->current_post + 1) % 2 === 0) ? true : false;;
}
function is_multiple3(){
    global $wp_query;
    return ((($wp_query->current_post+1) % 3) === 0);
}
function is_multiple4(){
    global $wp_query;
    return ((($wp_query->current_post+1) % 4) === 0);
}

/****************************************************************
* Use mobile theme when user agent is mobile.
****************************************************************/
function dp_mobile_template_include( $template ) {
	global $options, $IS_MOBILE_DP;
	if ($options['disable_mobile_fast']) return $template;

	// Mobile theme directory name
	if ( $IS_MOBILE_DP ) {
		$template_file = basename($template);
		$template_mb = str_replace( $template_file, DP_MOBILE_THEME_DIR.'/'.$template_file, $template );
		// If exist the mobile template, replace them.
		if ( file_exists( $template_mb ) )
			$template = $template_mb;
	}
	return $template;
}
add_filter( 'template_include', 'dp_mobile_template_include' );

/****************************************************************
* After setup theme
****************************************************************/
function dp_password_form() {
	$custom_phrase =
'<p class="need-pass-title label label-orange icon-lock">'.__('Protected','DigiPress').'</p>'.__('Please type the password to read this page.', 'DigiPress').'
<div id="protectedForm"><form action="' . esc_url(site_url()) . '/wp-login.php?action=postpass" method="post"><input name="post_password" type="password" size="24" /><input type="submit" name="Submit" value="' . esc_attr__("Submit") . '" />
</form></div>';

return $custom_phrase;
}
function dp_after_setup_theme() {
	global $wp_version;
	// ***
	// * Add theme support
	// **
	// Post thumbnail
	add_theme_support('post-thumbnails');
	// Custom menu
	add_theme_support('menus');
	// Feed links
	add_theme_support( 'automatic-feed-links' );
	// Auto title tag (WP4.1 over)
    if ( version_compare( $wp_version, '4.1', '>=' ) ) {
        add_theme_support('title-tag');
    }

	// Password form
	remove_filter( 'the_password_form', 'custom_password_form' );
	add_filter('the_password_form', 'dp_password_form');

	// Theme customizer
	add_theme_support('custom-background');
	add_theme_support('custom-header');
	add_theme_support( 'align-wide' );
	// Post formats
	add_theme_support( 'post-formats', array(
		'aside',
		'gallery',
		'image',
		'link',
		'quote',
		'status',
		'video',
		'audio',
		'chat'
	) );

	// Enable support for HTML5 markup.
	add_theme_support( 'html5', array(
		'comment-list',
		'search-form',
		'comment-form',
		// 'gallery',
		'caption'
	) );
}
add_action( 'after_setup_theme', 'dp_after_setup_theme' );

/****************************************************************
* Replace title tag
****************************************************************/
function dp_replace_wp_title( $title ) {
    $title = dp_site_title(null, null, false);
    return $title;
}
add_filter( 'pre_get_document_title', 'dp_replace_wp_title' );

/****************************************************************
* Replace gallery shortcode
****************************************************************/
// Remove gallery shortcode
// remove_shortcode('gallery', 'gallery_shortcode');
// Add original gallery shortcode
// add_shortcode('gallery', 'dp_gallery_shortcode');

/****************************************************************
* Fix original "the_excerpt" function.
****************************************************************/
remove_filter('the_excerpt', 'wpautop');
function dp_del_from_excerpt($str){
	$str = preg_replace("/(\r|\n|\r\n)/m", " ", $str);
	$str = preg_replace("/　/", "", $str); //del multibyte space
	$str = preg_replace("/\t/", "", $str); //del tab
	$str = preg_replace("/(<br>)+/", " ", $str);
	$str = preg_replace("/^(<br \/>)/", "", $str);
	return '<p>' . $str . '</p>';
}
add_filter('the_excerpt', 'dp_del_from_excerpt');

/****************************************************************
* Replace "more [...]" strings.
****************************************************************/
//WordPress version as integer.
function dp_new_excerpt_more($more) {
	return '...';
}
//Replace "more" strings.
add_filter('excerpt_more', 'dp_new_excerpt_more');

/****************************************************************
* Change excerpt length.
****************************************************************/
function dp_new_excerpt_mblength($length) {
	return 220;
}
add_filter('excerpt_mblength', 'dp_new_excerpt_mblength');

/****************************************************************
* Remove more "#" link string.
****************************************************************/
function dp_custom_content_more_link( $output ) {
	$output = preg_replace('/#more-[\d]+/i', '', $output );
	return $output;
}
add_filter( 'the_content_more_link', 'dp_custom_content_more_link' );

/****************************************************************
* Disable font-sizeing in tag cloud
****************************************************************/
function theme_new_tag_cloud($args) {
	$my_args = array(
		'smallest'	=> 11,
		'largest'	=> 11,
		'unit'		=> 'px',
		'number' 	=> 45,
		'orderby'	=> 'count',
		'order' 	=> 'DESC'
		);
	$args = wp_parse_args( $args, $my_args );
	return $args;
}
add_filter('widget_tag_cloud_args', 'theme_new_tag_cloud');
/**
 * remove style attribute in tag cloud
 */
function dp_remove_tagcloud_style( $return ) {
	$return = preg_replace('/\sstyle=".+?"/i', '', $return );
	return $return;
}
add_filter( 'wp_tag_cloud', 'dp_remove_tagcloud_style' );

/****************************************************************
* Disable comment form at static page
****************************************************************/
function close_page_comment($open, $post_id) {
	$post = get_post($post_id);
	if ($post && $post->post_type == 'page') {
		return false;
	}
	return $open;
}
add_filter('comments_open', 'close_page_comment', 10, 2);

/****************************************************************
* Disable wp emoji
****************************************************************/
function dp_disable_emoji() {
    remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
    remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
    remove_action( 'wp_print_styles', 'print_emoji_styles' );
    remove_action( 'admin_print_styles', 'print_emoji_styles' );
    remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
    remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
    remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
}
if ((bool)$options['disable_emoji'] ) {
	add_action('init', 'dp_disable_emoji');
	add_filter( 'emoji_svg_url', '__return_false' );
}
/****************************
 * HEX to RGB
 ***************************/
function dp_hex_to_rgb($color) {
	$color = preg_replace("/^#/", '', $color);
	if (mb_strlen($color) == 3) $color .= $color;
	$rgb = array();
	for($i = 0; $i < 6; $i+=2) {
		$hex = substr($color, $i, 2);
		$rgb[] = hexdec($hex);
	}
	return $rgb;
}
function dp_darken_color($color, $range = 30) {
	if (!is_numeric($range)) $range = 30;
	if ($range > 255 || $range < 0) $range = 30;
	$color = preg_replace("/^#/", '', $color);
	if (mb_strlen($color) == 3) $color .= $color;
	$rgb = array();
	for($i = 0; $i < 6; $i+=2) {
		$hex = substr($color, $i, 2);
		$hex = hexdec($hex);
		$hex = $hex > $range ? $hex - $range : $hex;
		$rgb[] = $hex;
	}
	return $rgb;
}
/****************************************************************
* Enable PHP in widgets
****************************************************************/
function dp_execute_php_in_widget($html){
	global $options;
	if(strpos($html,"<"."?php")!==false && $options['execute_php_in_widget'] ){
		ob_start();
		eval("?".">".$html);
		$html=ob_get_contents();
		ob_end_clean();
	}
	return $html;
}
add_filter('widget_text','dp_execute_php_in_widget',100);
add_filter('dp_widget_text','dp_execute_php_in_widget',100);



/****************************************************************
* Add title attribute in next post link
****************************************************************/
/*function add_title_to_next_post_link($link) {
	global $post;
	$post = get_post($post_id);
	$next_post = get_next_post();
	$title = $next_post->post_title;
	$link = str_replace("rel=", " title='".$title."' rel", $link);
	return $link;
}
add_filter('next_post_link','add_title_to_next_post_link');*/

/****************************************************************
* Add title attribute in previous post link
****************************************************************/
/*function add_title_to_previous_post_link($link) {
	global $post;
	$post = get_post($post_id);
	$previous_post = get_previous_post();
	$title = $previous_post->post_title;
	$link = str_replace("rel=", " title='".$title."' rel", $link);
	return $link;
}
add_filter('previous_post_link','add_title_to_previous_post_link');*/

/****************************************************************
* Remopve protection text and custome protected form
****************************************************************/
function remove_private($s) {
	return '%s';
}
add_filter('protected_title_format', 'remove_private');


/****************************************************************
* Insert post thumbnail in Feeds.
****************************************************************/
// function post_thumbnail_in_feeds($content) {
// 	global $post;
// 	if(has_post_thumbnail($post->ID)) {
// 		$content = '<div>' . get_the_post_thumbnail($post->ID) . '</div>' . $content;
// 	}
// 	return $content;
// }
// add_filter('the_excerpt_rss', 'post_thumbnail_in_feeds');
// add_filter('the_content_feed', 'post_thumbnail_in_feeds');

/****************************************************************
* Replace post slug when unexpected character.
****************************************************************/
function auto_post_slug( $slug, $post_ID, $post_status, $post_type ) {
	global $options;
	if (!(bool)$options['disable_fix_post_slug']) {
		if ( preg_match( '/(%[0-9a-f]{2})+/', $slug ) ) {
			$slug = utf8_uri_encode( $post_type ) . '-' . $post_ID;
		}
	}
	return $slug;
}
add_filter( 'wp_unique_post_slug', 'auto_post_slug', 10, 4 );

/****************************************************************
* Use search.php if the search word is not set.
****************************************************************/
function enable_empty_query( $search, $query ) {
	global $wpdb, $options;

	if ($query->is_main_query()) {
		// "q" is for Google Custom Search
		if ( (isset( $_REQUEST['s'] ) && empty( $_REQUEST['s'])) || (isset( $_REQUEST['q']) && isset( $options['gcs_id'] ) && !empty($options['gcs_id']) ) ) {
			$term = $_REQUEST['s'];
			$query->is_search = true;
			if ( $term === '' ) {
				$search = ' AND 0';
			} else {
				$search = " AND ( ( $wpdb->posts.post_title LIKE '%{$term}%' ) OR ( $wpdb->posts.post_content LIKE '%{$term}%' ) )";
			}
		}
	}
	return $search;
}
if (!is_admin()) {
	add_action( 'posts_search', 'enable_empty_query', 10, 2);
}
/****************************************************************
* Disable hentry class
****************************************************************/
function dp_remove_hentry( $classes ) {
	$classes = array_diff($classes, array('hentry'));
	return $classes;
}
add_filter('post_class', 'dp_remove_hentry');

/**
 * Converts pre tag contents to HTML entities
 */
function dp_pre_content_filter( $content ) {
	return preg_replace_callback(
		'|<pre.*>(.*)</pre|isU',
		function( $matches ) {
			return str_replace( $matches[1], str_replace( array('[', ']'), array('&#91;', '&#93;'), htmlentities( $matches[1] ) ), $matches[0] );
		}, $content );
}
add_filter( 'the_content', 'dp_pre_content_filter', 1 );

/****************************************************************
 * Modifies WordPress's built-in comments_popup_link() function to return a string instead of echo comment results
 ***************************************************************/
function get_comments_popup_link( $zero = false, $one = false, $more = false, $css_class = '', $none = false ) {
    global $wpcommentspopupfile, $wpcommentsjavascript;

    $id = get_the_ID();

    if ( false === $zero ) $zero = __( 'No Comments','DigiPress' );
    if ( false === $one ) $one = __( 'Comment(1)','DigiPress' );
    if ( false === $more ) $more = __( 'Comments(%)','DigiPress' );
    if ( false === $none ) $none = __( 'Comments Off','DigiPress' );

    $number = get_comments_number( $id );

    $str = '';

    if ( 0 == $number && !comments_open() && !pings_open() ) {
        $str = '<span' . ((!empty($css_class)) ? ' class="' . esc_attr( $css_class ) . '"' : '') . '>' . $none . '</span>';
        return $str;
    }

    if ( post_password_required() ) {
        $str = __('Enter your password to view comments.','DigiPress');
        return $str;
    }

    $str = '<a href="';
    if ( $wpcommentsjavascript ) {
        if ( empty( $wpcommentspopupfile ) )
            $home = home_url();
        else
            $home = home_url();
        $str .= $home . '/' . $wpcommentspopupfile . '?comments_popup=' . $id;
        $str .= '" onclick="wpopen(this.href); return false"';
    } else { // if comments_popup_script() is not in the template, display simple comment link
        if ( 0 == $number )
            $str .= get_permalink() . '#respond';
        else
            $str .= get_comments_link();
        $str .= '"';
    }

    if ( !empty( $css_class ) ) {
        $str .= ' class="'.$css_class.'" ';
    }
    $title = the_title_attribute( array('echo' => 0 ) );

    $str .= apply_filters( 'comments_popup_link_attributes', '' );

    $str .= ' title="' . esc_attr( sprintf( __('Comment on %s','DigiPress'), $title ) ) . '">';
    $str .= get_comments_number_str( $zero, $one, $more );
    $str .= '</a>';

    return $str;
}
/**
 * Modifies WordPress's built-in comments_number() function to return string instead of echo
 */
function get_comments_number_str( $zero = false, $one = false, $more = false, $deprecated = '' ) {
    if ( !empty( $deprecated ) )
        _deprecated_argument( __FUNCTION__, '1.3' );

    $number = get_comments_number();

    if ( $number > 1 )
        $output = str_replace('%', number_format_i18n($number), ( false === $more ) ? __('Comments(%)', 'DigiPress') : $more);
    elseif ( $number == 0 )
        $output = ( false === $zero ) ? __('No Comments', 'DigiPress') : $zero;
    else // must be one
        $output = ( false === $one ) ? __('Comment(1)', 'DigiPress') : $one;

    return apply_filters('comments_number', $output, $number);
}

/****************************************************************
* Number of post at each archive.
****************************************************************/
function dp_number_posts_per_archive( $query ) {
	if (is_admin()) return;
	global $options, $IS_MOBILE_DP;

	$suffix = '';

	if ( $query->is_main_query() ) {
		// Suffix
		$suffix = $IS_MOBILE_DP ? '_mobile' : '';

		// Get posts
		if ($query->is_home() && $options['number_posts_index'.$suffix]) {
			if ($options['show_specific_cat_index'] === 'cat') {
				$query->set( 'posts_per_page', $options['number_posts_index'.$suffix] );

				// Show specific category's posts
				if ($options['index_bottom_except_cat']) {
					// Add nimus each category id
					$cat_ids = preg_replace('/(\d+)/', '-${1}', $options['index_bottom_except_cat_id']);

					$query->set( 'cat', $cat_ids );

				} else {
					$query->set( 'cat', $options['specific_cat_index'] );
				}

			} else if ($options['show_specific_cat_index'] === 'custom') {
				// Show specific custom post type
				$query->set( 'posts_per_page', $options['number_posts_index'.$suffix] );
				$query->set( 'post_type', $options['specific_post_type_index'] );

			} else {
				$query->set( 'posts_per_page', $options['number_posts_index'.$suffix] );
			}
		}
		else if ($query->is_category() && $options['number_posts_category'.$suffix] ) {
			$query->set( 'posts_per_page', $options['number_posts_category'.$suffix] );
		}
		else if ($query->is_search() && $options['number_posts_search'.$suffix] ) {
			$query->set( 'posts_per_page', $options['number_posts_search'.$suffix] );
		}
		else if ($query->is_tag() && $options['number_posts_tag'.$suffix] ) {
			$query->set( 'posts_per_page', $options['number_posts_tag'.$suffix] );
		}
		else if ($query->is_date() && $options['number_posts_date'.$suffix] ) {
			$query->set( 'posts_per_page', $options['number_posts_date'.$suffix] );
		}
		else if ($query->is_author() && $options['number_posts_author'.$suffix] ) {
			$query->set( 'posts_per_page', $options['number_posts_author'.$suffix] );
		}
	}
}
add_action( 'pre_get_posts', 'dp_number_posts_per_archive' );

/****************************************************************
* Add functions into outer html for theme.
****************************************************************/
//Remove meta of CMS Version
remove_action('wp_head', 'wp_generator');



/**
* Detects animated GIF from given file pointer resource or filename.
*
* @param resource|string $file File pointer resource or filename
* @return bool
*/
function dp_is_animated_gif($file){
	$fp = null;
	if (is_string($file)) {
		$fp = fopen($file, "rb");
	} else {
		$fp = $file;
		/* Make sure that we are at the beginning of the file */
		fseek($fp, 0);
	}
	if (fread($fp, 3) !== "GIF") {
		fclose($fp);
		return false;
	}
	$frames = 0;
	while (!feof($fp) && $frames < 2) {
		if (fread($fp, 1) === "\x00") {
			/* Some of the animated GIFs do not contain graphic control extension (starts with 21 f9) */
			if (fread($fp, 1) === "\x21" || fread($fp, 2) === "\x21\xf9") {
				$frames++;
			}
		}
	}
	fclose($fp);
	return $frames > 1;
}
function dp_disable_upload_sizes( $sizes, $metadata ) {
	if (ini_get('allow_url_fopen')){
		$uploads = wp_upload_dir();
		$upload_path = $uploads['baseurl'];
		$relative_path = $metadata['file'];
		$file_url = $upload_path . '/' . $relative_path;

		if( dp_is_animated_gif( $file_url ) ) {
			$sizes = array();
		}
	}
	// Return sizes you want to create from image (None if image is gif.)
	return $sizes;
}
add_filter('intermediate_image_sizes_advanced', 'dp_disable_upload_sizes', 10, 2);

/****************************************************************
* Create Uinique ID
****************************************************************/
function dp_rand($sha1 = false) {
	$str_rand = (bool)$sha1 ? sha1(uniqid(mt_rand())) : uniqid(mt_rand(100,500));
	return $str_rand;
}
/**
 * Get the image width and height
 * @param $iamge_url : image URL
 * @return array(width, height) or false
 */
function dp_get_image_size($image_url = null){
	if (empty($image_url)) return;
	if (preg_match("/^\/\//", $image_url)){
		$image_url = is_ssl() ? 'https:'.$image_url : 'http:'.$image_url;
	}
	$img_data = wp_remote_get($image_url);
	if ( !is_wp_error( $img_data ) && $img_data['response']['code'] === 200 ) {
		if (function_exists('getimagesizefromstring')) {
			$img_data = getimagesizefromstring($img_data['body']);
			return array($img_data[0], $img_data[1]);
		} else {
			$img_data = imagecreatefromstring($img_data['body']);
			return array(imagesx($img_data), imagesy($img_data));
		}
	} else {
		return false;
	}
}
/**************************
 * HEX to RGB
 ***************************/
function dp_hexToRgb($color) {
	$color = preg_replace("/^#/", '', $color);
	if (mb_strlen($color) == 3) $color .= $color;
	$rgb = array();
	for($i = 0; $i < 6; $i+=2) {
		$hex = substr($color, $i, 2);
		$rgb[] = hexdec($hex);
	}
	return $rgb;
}

/******************************************************
 For url cache control
******************************************************/
function echo_filedate($filename) {
    if (file_exists($filename)) {
        return date_i18n('YmdHis', filemtime($filename));
    } else {
    	return date_i18n('Ymd');
    }
}
/****************************************************************
* Load css and js
*  -- disable WP default jquery, load Google API minimized script
****************************************************************/
function dp_load_css_scripts() {
	if (is_admin()) return;
	global $options, $options_visual, $IS_MOBILE_DP, $IS_AMP_DP;
	if ($IS_AMP_DP) return;

	$mb_sufix = '';
	$arr_main_pri = array('jquery','easing','dp-masonry');

	// Default CSS
	$css_name = "style.css";
	if ($options['decoration_type'] === 'bootstrap' || $options['decoration_type'] === 'none') {
		$css_name = "style-bs.css";
	}
	// Bootstrap
	if ($options['decoration_type'] === 'bootstrap'){
		wp_enqueue_style('bootstrap','https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css');
		wp_enqueue_script('bootstrap','https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js',array('jquery'),DP_OPTION_SPT_VERSION,true);
	}
	// wow.js
	if ($IS_MOBILE_DP) {
		$mb_sufix = '_mb';
	}
	if (!(bool)$options['disable_wow_js'.$mb_sufix]){
		wp_enqueue_style('wow',DP_THEME_URI.'/css/animate.css',array('digipress'),DP_OPTION_SPT_VERSION);
		wp_enqueue_script('wow',DP_THEME_URI.'/inc/js/wow.min.js',array(),DP_OPTION_SPT_VERSION,true);
	}

	$css_pc = '/css/'.$css_name;
	$css_mb = '/'.DP_MOBILE_THEME_DIR.'/css/'.$css_name;
	$css_custom = '/css/visual-custom.css';

	if ( $IS_MOBILE_DP ) {
        wp_enqueue_style( 'digipress', DP_THEME_URI.$css_mb, null, echo_filedate(DP_THEME_DIR.$css_mb) );
    } else {
        wp_enqueue_style( 'digipress', DP_THEME_URI.$css_pc, null, echo_filedate(DP_THEME_DIR.$css_pc) );
    }
    // Custom CSS
    if ( file_exists( DP_UPLOAD_DIR.'/css/visual-custom.css') ) {
        wp_enqueue_style( 'dp-visual', DP_UPLOAD_URI.$css_custom, array('digipress'), echo_filedate(DP_UPLOAD_DIR.$css_custom) );
    } else {
        wp_enqueue_style( 'dp-visual', DP_THEME_URI.$css_custom, array('digipress'), echo_filedate(DP_THEME_DIR.$css_custom) );
    }

	// jQuery
	if ($options['use_google_jquery']) {
		// Disable default jQuery
		wp_deregister_script('jquery');
		// Replace to Google API jQuery
		wp_enqueue_script('jquery','https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js',array(),DP_OPTION_SPT_VERSION);
	} else {
		wp_enqueue_script('jquery',array(),DP_OPTION_SPT_VERSION);
	}

	// jQuery easing
	wp_enqueue_script('easing', DP_THEME_URI . '/inc/js/jquery/jquery.easing.min.js', array('jquery'),DP_OPTION_SPT_VERSION,true);
	// Masonry
	wp_enqueue_script('dp-masonry', DP_THEME_URI . '/inc/js/jquery/jquery.masonry.min.js', array('jquery', 'imagesloaded'),DP_OPTION_SPT_VERSION,true);
	// imagesload
	wp_enqueue_script('imagesloaded', DP_THEME_URI . '/inc/js/jquery/jquery.imagesloaded.min.js', array('jquery'),DP_OPTION_SPT_VERSION,true);
	// For throw Cross Domain Error
    wp_enqueue_script('xdomainajax', DP_THEME_URI . '/inc/js/jquery/jquery.xdomainajax.min.js', array('jquery'),DP_OPTION_SPT_VERSION,true);
	// fitVids
	wp_enqueue_script('fitvids', DP_THEME_URI . '/inc/js/jquery/jquery.fitvids.min.js', array('jquery'),DP_OPTION_SPT_VERSION,true);
	// Share count
	if (!(isset($options['disable_sns_share_count']) && !empty($options['disable_sns_share_count']))){
		wp_enqueue_script('sns-share-count', DP_THEME_URI . '/inc/js/jquery/jquery.sharecount.min.js', array('jquery'),DP_OPTION_SPT_VERSION,true);
		$arr_main_pri[] = 'sns-share-count';
	}
	// slider js (Even if it is not necessary)
	if ($options['autopager'.$mb_sufix] && !is_single() && !is_page()) {
		wp_enqueue_script('dp-bxslider', DP_THEME_URI . '/inc/js/jquery/jquery.bxslider.min.js', array('jquery', 'imagesloaded'),DP_OPTION_SPT_VERSION,true);
	}

	// Mobile or PC
	if ( $IS_MOBILE_DP ) {
		wp_enqueue_script('mmenu', DP_THEME_URI . '/inc/js/jquery/jquery.mmenu.min.js', array('jquery'),DP_OPTION_SPT_VERSION,true);
		if ($options['autopager_mb'] && !is_singular()) {
			wp_enqueue_script('autopager', DP_THEME_URI . '/inc/js/jquery/jquery.autopager.min.js', array('jquery','easing'),DP_OPTION_SPT_VERSION,true);
		}
		// Main theme js
		wp_enqueue_script('digipress', DP_THEME_URI . '/inc/js/mb-theme-import.min.js', $arr_main_pri, echo_filedate(DP_THEME_DIR . '/inc/js/mb-theme-import.min.js'),true);

	} else {
		// Progress bar
		if (is_front_page() && !is_paged()){
			wp_enqueue_script('pace', DP_THEME_URI . '/inc/js/pace.min.js', array('jquery'), DP_OPTION_SPT_VERSION, true);
		}
		if ($options['autopager'] && !is_singular()) {
			wp_enqueue_script('autopager', DP_THEME_URI . '/inc/js/jquery/jquery.autopager.min.js', array('jquery', 'imagesloaded'),DP_OPTION_SPT_VERSION,true);
		}
		// Main theme js
		wp_enqueue_script('digipress', DP_THEME_URI . '/inc/js/theme-import.min.js', $arr_main_pri, echo_filedate(DP_THEME_DIR . '/inc/js/theme-import.min.js'),true);
	}

	// for comment form
	if ( is_singular() && comments_open() && get_option('thread_comments') ) {
		wp_enqueue_script('comment-reply');
	}
}
add_action('wp_enqueue_scripts', 'dp_load_css_scripts', 1);
// *************************
// For assyncro scripts
// *************************
function dp_async_scripts($url){
	if ( strpos( $url, '#asyncload') === false ) {
		return $url;
	} else if ( is_admin() ) {
		return str_replace( '#asyncload', '', $url );
	} else {
		return str_replace( '#asyncload', '', $url )."' async='async"; 
	}
}
add_filter('clean_url', 'dp_async_scripts', 11, 1 );

/****************************************************************
* Insert css and Javascript to head
****************************************************************/
// function dp_add_meta_tags() {

// }
// add_action( 'wp_print_scripts', 'dp_add_meta_tags' );

/****************************************************************
* Insert Javascript to the end of html
****************************************************************/
function dp_inline_footer() {
	global $options;
	$trace_code = '';

	// Access Code
	if ( !empty($options['tracking_code']) ) {
		$trace_code = "<!-- Tracking Code -->" . $options['tracking_code'] ."<!-- /Tracking Code -->";
	}

	//Run only user logged in...
	if ( is_user_logged_in() ) {
		if (current_user_can('edit_others_posts') && $options['no_track_admin']) {
			$trace_code = "<!-- You are logged in as Administrator -->";
		}
	}
	echo $trace_code;
}
add_action('wp_footer', 'dp_inline_footer', 100);