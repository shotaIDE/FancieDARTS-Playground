<?php
// ----------------------------------------------------------------
// Custom init in admin panel 
// ----------------------------------------------------------------
function custom_admin_init() {
	if (!current_user_can('level_10')) {
		// Hide new WordPress version info
		add_filter('pre_site_transient_update_core', '__return_zero');

		// Disable version check connection with API.
		remove_action('wp_version_check', 'wp_version_check');
		remove_action('admin_init', '_maybe_update_core');

		// Remove welcome panel
		remove_action( 'welcome_panel', 'wp_welcome_panel' );
	}

	// Change editor user's role
	$role =& get_role('editor');
	$role->add_cap('edit_themes');
}
// add_action( 'admin_init', 'custom_admin_init');



// ----------------------------------------------------------------
// Add styles in admin panel
// ----------------------------------------------------------------
function dp_admin_print_styles(){
	if (current_user_can('level_10')) return;
	echo '<style type="text/css">
	.versions p,
	#wp-version-message, 
	td.b-posts, td.posts,
	td.b_pages, td.pages, 
	td.b-cats, td.cats,
	td.b-tags, td.tags,
	#contextual-help-link-wrap{display:none;}</style>';
}
// add_action('admin_print_styles', 'dp_admin_print_styles', 21);



// ----------------------------------------------------------------
// Import original CSS and javascript in admin panel
// ----------------------------------------------------------------
function dp_admin_enqueue($hook_suffix) {

	switch ($hook_suffix) {
		case 'post-new.php':
		case 'post.php':
			wp_enqueue_media();
			wp_enqueue_style('wp-color-picker');
			wp_enqueue_script('wp-color-picker');
			wp_enqueue_style('dp-admin', get_template_directory_uri() . '/inc/css/dp-admin.css');
			wp_enqueue_script('dp_post_page', get_template_directory_uri() . '/inc/js/dp_post_page.min.js', array('jquery', 'wp-color-picker'), DP_OPTION_SPT_VERSION, true);
			break;

		case 'widgets.php':
			wp_enqueue_style('dp_widgets_page', get_template_directory_uri() . '/inc/css/dp_widgets_page.css');
			// Color picker
		    wp_enqueue_style('wp-color-picker');
		    wp_enqueue_script('wp-color-picker');
		    // jQuery
		    wp_enqueue_script('jquery',array(),false);
		    // color picker
    		wp_enqueue_script('dp_widgets_page', get_template_directory_uri()."/inc/js/dp_widgets_page.min.js", array('jquery', 'wp-color-picker'));
			break;

		case 'edit.php':
			wp_enqueue_style('dp_admin_edit', get_template_directory_uri() . '/inc/css/dp_admin_edit.css');
			wp_enqueue_script('dp_admin_edit', get_template_directory_uri() . '/inc/js/dp_admin_edit.min.js', array('jquery'));
			break;
	}
}
add_action( 'admin_enqueue_scripts', 'dp_admin_enqueue' );


// ----------------------------------------------------------------
// Change footer text
// ----------------------------------------------------------------
function custom_admin_footer_text() {
	return;
}
// add_filter('admin_footer_text', 'custom_admin_footer_text');


// ----------------------------------------------------------------
// Admin bar items
// ----------------------------------------------------------------
function remove_admin_bar_menu() {
	if (current_user_can('level_10')) return;
	global $wp_admin_bar;
	$wp_admin_bar->remove_menu('wp-logo'); 		// W ロゴ
	// $wp_admin_bar->remove_menu('site-name');	// サイト名
	$wp_admin_bar->remove_menu('view-site'); 	// サイト名 -> サイトを表示
	$wp_admin_bar->remove_menu('comments'); 	// コメント
	$wp_admin_bar->remove_menu('new-content'); 	// 新規
	$wp_admin_bar->remove_menu('new-post'); 	// 新規 -> 投稿
	$wp_admin_bar->remove_menu('new-media'); 	// 新規 -> メディア
	$wp_admin_bar->remove_menu('new-link'); 	// 新規 -> リンク
	$wp_admin_bar->remove_menu('new-page'); 	// 新規 -> 固定ページ
	$wp_admin_bar->remove_menu('new-user'); 	// 新規 -> ユーザー
	$wp_admin_bar->remove_menu('updates'); 		// 更新
	$wp_admin_bar->remove_menu('my-account'); 	// マイアカウント
	$wp_admin_bar->remove_menu('user-info'); 	// マイアカウント -> プロフィール
	$wp_admin_bar->remove_menu('edit-profile'); // マイアカウント -> プロフィール編集
	$wp_admin_bar->remove_menu('logout'); 		// マイアカウント -> ログアウト
 }
// add_action( 'admin_bar_menu', 'remove_admin_bar_menu', 99 );


// ----------------------------------------------------------------
// Add logout menu
// ----------------------------------------------------------------
function add_new_item_in_admin_bar() {
	if (current_user_can('level_10')) return;
	global $wp_admin_bar;
	$wp_admin_bar->add_menu(array(
		'id' 	=> 'admin_bar_logout',
		'title' => __('Log out', 'DigiPress'),
		'href' 	=> wp_logout_url()
		)
	);
}
// add_action('wp_before_admin_bar_render', 'add_new_item_in_admin_bar');
 

// ----------------------------------------------------------------
// Remove dashboard widgets
// ----------------------------------------------------------------
function example_remove_dashboard_widgets() {
	if (current_user_can('level_10')) return;
 	global $wp_meta_boxes;
	//unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_right_now']); // 現在の状況
	unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_comments']); // 最近のコメント
	unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_incoming_links']); // 被リンク
	unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_plugins']); // プラグイン
	unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press']); // クイック投稿
	//unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_recent_drafts']); // 最近の下書き
	unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_primary']); // WordPressブログ
	unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary']); // WordPressフォーラム
}
// add_action('wp_dashboard_setup', 'example_remove_dashboard_widgets');



// ----------------------------------------------------------------
// Change menu title
// ----------------------------------------------------------------
function change_admin_left_menu_title() {
	global $menu, $submenu;

	// $menu[5][0] = 'Blogs'; 
	$menu[10][0] = __('Upload', 'DigiPress'); // Media
}
// add_action( 'admin_menu', 'change_admin_left_menu_title' );


// ----------------------------------------------------------------
// Change menu order
// ----------------------------------------------------------------
function custom_menu_order($menu_ord) {
	if (!$menu_ord) return true;
	
	return array(
		'index.php', 				// Dashboard
		'separator1', 				// First separator
		'edit.php', 				// Posts
		'upload.php', 				// Media
		'link-manager.php', 		// Links
		'edit.php?post_type=page', 	// Pages
		'edit-comments.php', 		// Comments
		'separator2', 				// Second separator
		'themes.php', 				// Appearance
		'plugins.php', 				// Plugins
		'users.php', 				// Users
		'tools.php', 				// Tools
		'options-general.php', 		// Settings
		'separator-last', 			// Last separator
	);
}
// add_filter('custom_menu_order', 'custom_menu_order'); // Activate custom_menu_order
// add_filter('menu_order', 'custom_menu_order');



// ----------------------------------------------------------------
// Remove sidebar menu
// ----------------------------------------------------------------
function remove_admin_menus () {
	if (current_user_can('level_10')) return;
	// remove_menu_page('wpcf7'); //Contact Form 7
	global $menu;
	//unset($menu[2]); // ダッシュボード
	// unset($menu[4]); // メニューの線1
	// unset($menu[5]); // 投稿
	// unset($menu[10]); // メディア
	unset($menu[15]); // リンク
	// unset($menu[20]); // 固定ページ
	// unset($menu[25]); // コメント
	unset($menu[59]); // メニューの線2
	unset($menu[60]); // テーマ
	unset($menu[65]); // プラグイン
	//unset($menu[70]); // プロフィール
	unset($menu[75]); // ツール
	unset($menu[80]); // 設定
	unset($menu[90]); // メニューの線3
}
// add_action('admin_menu', 'remove_admin_menus');


// ----------------------------------------------------------------
// Visible menus
// ----------------------------------------------------------------
function dp_add_admin_menus () {
	add_theme_page(__('Menus', 'DigiPress'), __('Menus', 'DigiPress'), 'editor', 'nav-menus.php');
	add_theme_page(__('Widgets', 'DigiPress'), __('Widgets', 'DigiPress'), 'editor', 'widgets.php');
}
// add_action('admin_menu', 'dp_add_admin_menus');


// ----------------------------------------------------------------
// Remove update number in admin bar
// ----------------------------------------------------------------
function remove_before_admin_bar_render() {
	if (current_user_can('level_10')) return;
	global $wp_admin_bar;
	$wp_admin_bar->remove_menu( 'updates' );
}
// add_action( 'wp_before_admin_bar_render', 'remove_before_admin_bar_render' );



// ----------------------------------------------------------------
// Remove post option items
// ----------------------------------------------------------------
// 投稿
function remove_default_post_screen_metaboxes() {
	if (current_user_can('level_10')) return;
	remove_meta_box( 'postexcerpt','post','normal' );       // 抜粋
	//remove_meta_box( 'trackbacksdiv','post','normal' );     // トラックバック送信
	remove_meta_box( 'postcustom','post','normal' );        // カスタムフィールド
	//remove_meta_box( 'commentstatusdiv','post','normal' );  // ディスカッション
	//remove_meta_box( 'commentsdiv','post','normal' );       // コメント
	//remove_meta_box( 'slugdiv','post','normal' );           // スラッグ
	remove_meta_box( 'authordiv','post','normal' );         // 作成者
	remove_meta_box( 'revisionsdiv','post','normal' );      // リビジョン
	//remove_meta_box( 'formatdiv','post','normal' );         // フォーマット
	//remove_meta_box( 'categorydiv','post','normal' );       // カテゴリー
	//remove_meta_box( 'tagsdiv-post_tag','post','normal' );  // タグ
}
// add_action('admin_menu','remove_default_post_screen_metaboxes');

// 固定ページ
function remove_default_page_screen_metaboxes() {
	if (current_user_can('level_10')) return;
	remove_meta_box( 'postcustom','page','normal' );        // カスタムフィールド
	remove_meta_box( 'commentstatusdiv','page','normal' );  // ディスカッション
	remove_meta_box( 'commentsdiv','page','normal' );       // コメント
	//remove_meta_box( 'slugdiv','page','normal' );           // スラッグ
	remove_meta_box( 'authordiv','page','normal' );         // 作成者
	remove_meta_box( 'revisionsdiv','page','normal' );      // リビジョン
}
// add_action('admin_menu','remove_default_page_screen_metaboxes');


// ----------------------------------------------------------------
// Hide or add profile items
// ----------------------------------------------------------------
function custom_profile_fields( $fields ) {
	// Remove fields
	unset($fields['aim']);     // AIM
	unset($fields['yim']);     // Yahoo IM
	unset($fields['jabber']);  // Jabber / Google Talk

	// Add new fields
	$fields['site_name']= __('Your site name', 'DigiPress');
	$fields['org']		= __('Organization', 'DigiPress');
	$fields['title']	= __('Role', 'DigiPress');
	$fields['twitter']	= __('Twitter URL', 'DigiPress');
	$fields['facebook'] = __('Facebook URL', 'DigiPress');
	$fields['gplus'] 	= __('Google+ URL', 'DigiPress');
	$fields['youtube'] 	= __('YouTube URL', 'DigiPress');
	$fields['flickr'] 	= __('Flickr URL', 'DigiPress');
	$fields['instagram'] 	= __('Instagram URL', 'DigiPress');
	$fields['pinterest'] 	= __('Pinterest URL', 'DigiPress');
	$fields['bg_img']	= __('Prof background image URL', 'DigiPress');

	return $fields;
}
add_filter('user_contactmethods','custom_profile_fields',10, 1);



// ----------------------------------------------------------------
// Remove screen options
// ----------------------------------------------------------------
function remove_screen_options(){
	if (!current_user_can('level_10')) return false;
}
// add_filter( 'screen_options_show_screen', 'remove_screen_options' );


// ----------------------------------------------------------------
// Add custom post type link in left menu
// ----------------------------------------------------------------
function show_custom_post_dashboard() {
	$dashboard_custom_post_types= Array(
		'news',
		'blog',
		'customers',
		'recommenders'
	);
	
	foreach($dashboard_custom_post_types as $custom_post_type) {
		global $wp_post_types;
		$num_post_type = wp_count_posts($custom_post_type);
		$num = number_format_i18n($num_post_type->publish);
		$text = _n( $wp_post_types[$custom_post_type]->labels->singular_name, $wp_post_types[$custom_post_type]->labels->name, $num_post_type->publish );
		$capability = $wp_post_types[$custom_post_type]->cap->edit_posts;
		
		if (current_user_can($capability)) {
			$num = "<a href='edit.php?post_type=" . $custom_post_type . "'>$num</a>";
			$text = "<a href='edit.php?post_type=" . $custom_post_type . "'>$text</a>";
		}
		
		echo '<tr>';
		echo '<td class="first b b_' . $custom_post_type . '">' . $num . '</td>';
		echo '<td class="t ' . $custom_post_type . '">' . $text . '</td>';
		echo '</tr>';
	}
}
// add_action( 'right_now_content_table_end', 'show_custom_post_dashboard' );


// ----------------------------------------------------------------
// Customize editor panel
// ----------------------------------------------------------------
/**
 * Customize editor panel
 */
// Enable original CSS in editor panel
function dp_theme_add_editor_styles() {
    add_editor_style('inc/css/editor_style.css');
}
add_action('admin_init', 'dp_theme_add_editor_styles');
/**
 * Add inline CSS in Visual editor
 */
function dp_tiny_mce_editor_inline_css($settings){
	// Target selectors
	$selector = array(
		'.mce-content-body.editor-area',
		'.mce-content-body.core-blocks-rich-text__tinymce'
	);
	$inline_css = dp_generate_editor_inline_css( $selector );

	$settings['content_style'] = $inline_css;
	return $settings;
}
add_filter('tiny_mce_before_init', 'dp_tiny_mce_editor_inline_css');

/**
 * Enable style sheet for Gutenberg
 */
function dp_theme_enqueue_for_gutenberg() {
	// external CSS
	$editor_style_url = get_theme_file_uri('/inc/css/editor_style.css');
	wp_enqueue_style('dp-editor', $editor_style_url);

	// Target selectors
	$main_selector = array(
		'div.edit-post-visual-editor'
	);
	$font_color_selector = array(
		'div.editor-post-title__block .editor-post-title__input',
		'div.editor-block-list__block',
		'div.edit-post-visual-editor',
		'div.edit-post-visual-editor p',
		'div.edit-post-visual-editor input[type=text].editor-default-block-appender__content'
	);

	// Inline CSS
	$inline_css = dp_generate_editor_inline_css( $main_selector );
	$inline_css .= dp_override_gutenberg_font_color( $font_color_selector );

	// Register inline CSS
	wp_register_style('dp-editor-insert', false);
	wp_enqueue_style('dp-editor-insert');
	wp_add_inline_style('dp-editor-insert', $inline_css);
}
add_action('enqueue_block_editor_assets', 'dp_theme_enqueue_for_gutenberg');

/**
 * Override gutenberg default font color
 */
function dp_override_gutenberg_font_color($arr_selector){
	if ( !(isset($arr_selector) && is_array($arr_selector)) ) return;
	global $options_visual;

	$selector = implode(',', $arr_selector);
	$inline_css = $selector."{color:".$options_visual['base_font_color'].";}";

	return $inline_css;
}

/**
 * Generate inline CSS for editor
 */
function dp_generate_editor_inline_css($arr_selector){
	if ( !(isset($arr_selector) && is_array($arr_selector)) ) return;
	global $options_visual;

	$inline_css = '';

	foreach ($arr_selector as $selector){
		$inline_css =
$selector."{
	background-color:".$options_visual['container_bg_color'].";
	color:".$options_visual['base_font_color'].";}".
$selector." a,".
$selector." blockquote::before,".
$selector." blockquote::after{
	color:".$options_visual['base_link_color'].";}".
$selector." a:hover{
	color:".$options_visual['base_link_hover_color'].";
}".
$selector." .btn{
	color:".$options_visual['accent_color']."!important;
	border-color:".$options_visual['accent_color'].";
}".
$selector." .btn::after,".
$selector." .label,".
$selector." ul:not(.recent_entries) li::before,".
$selector." ol li::before{
	background-color:".$options_visual['accent_color'].";
}";
	}

	$inline_css = str_replace(array("\r\n","\r","\n","\t"), '', $inline_css);

	return $inline_css;
}

// Add custom button on Tiny MCE
if ( !function_exists( 'dp_tiny_mce_before_init' ) ):
	function dp_tiny_mce_before_init( $init_array ){
		$init_array['body_class'] = 'editor-area';
		$init_array['block_formats'] = '段落=p;見出し1=h1;見出し2=h2;見出し3=h3;見出し4=h4;見出し5=h5;見出し6=h6;住所=address;整形済みテキスト=pre';

		// Original style code 
		$style_formats = array(
			array(
				'title' => '太字',
				'inline' => 'span',
				'classes' => 'b'
			),
			array(
				'title' => '斜体',
				'inline' => 'span',
				'classes' => 'i'
			),
			array(
				'title' => 'フォント大',
				'inline' => 'span',
				'classes' => 'big'
			),
			array(
				'title' => 'フォント小',
				'inline' => 'span',
				'classes' => 'small'
			),
			array(
				'title' => '赤字',
				'inline' => 'span',
				'classes' => 'red'
			),
			array(
				'title' => '青字',
				'inline' => 'span',
				'classes' => 'blue'
			),
			array(
				'title' => '緑字',
				'inline' => 'span',
				'classes' => 'green'
			),
			array(
				'title' => '黄字',
				'inline' => 'span',
				'classes' => 'yellow'
			),
			array(
				'title' => '橙字',
				'inline' => 'span',
				'classes' => 'orange'
			),
			array(
				'title' => 'ピンク字',
				'inline' => 'span',
				'classes' => 'pink'
			),
			array(
				'title' => 'マーカー(赤)',
				'inline' => 'span',
				'classes' => 'mk-red'
			),
			array(
				'title' => 'マーカー(青)',
				'inline' => 'span',
				'classes' => 'mk-blue'
			),
			array(
				'title' => 'マーカー(緑)',
				'inline' => 'span',
				'classes' => 'mk-green'
			),
			array(
				'title' => 'マーカー(黄)',
				'inline' => 'span',
				'classes' => 'mk-yellow'
			),
			array(
				'title' => 'マーカー(橙)',
				'inline' => 'span',
				'classes' => 'mk-orange'
			),
			array(
				'title' => 'マーカー(ピンク)',
				'inline' => 'span',
				'classes' => 'mk-pink'
			),
			array(
				'title' => '下線',
				'inline' => 'span',
				'classes' => 'bd'
			),
			array(
				'title' => '下線(赤)',
				'inline' => 'span',
				'classes' => 'bd-red'
			),
			array(
				'title' => '下線(青)',
				'inline' => 'span',
				'classes' => 'bd-blue'
			),
			array(
				'title' => '下線(緑)',
				'inline' => 'span',
				'classes' => 'bd-green'
			),
			array(
				'title' => '下線(黄)',
				'inline' => 'span',
				'classes' => 'bd-yellow'
			),
			array(
				'title' => '下線(橙)',
				'inline' => 'span',
				'classes' => 'bd-orange'
			),
			array(
				'title' => '下線(ピンク)',
				'inline' => 'span',
				'classes' => 'bd-pink'
			),
			array(
				'title' => 'ラベル',
				'inline' => 'span',
				'classes' => 'label'
			),
			array(
				'title' => 'ボタン',
				'block' => 'a',
				'classes' => 'btn'
			),
			array(
				'title' => 'ボックス',
				'block' => 'div',
				'classes' => 'box'
			),
			array(
				'title' => 'ボックス(赤)',
				'block' => 'div',
				'classes' => 'box-red'
			),
			array(
				'title' => 'ボックス(青)',
				'block' => 'div',
				'classes' => 'box-blue'
			),
			array(
				'title' => 'ボックス(緑)',
				'block' => 'div',
				'classes' => 'box-green'
			),
			array(
				'title' => 'ボックス(黄)',
				'block' => 'div',
				'classes' => 'box-yellow'
			)
		);
		//Parse to json
		$init_array['style_formats'] = json_encode($style_formats);

		return $init_array;
	}
endif;
add_filter( 'tiny_mce_before_init', 'dp_tiny_mce_before_init' );

// Add the quick tags on text editor mode
if ( !function_exists( 'add_dp_custom_quicktags' ) ):
	function add_dp_custom_quicktags() {
		if (wp_script_is('quicktags')){
			$quicktags = "<script>
QTags.addButton('qt-bold','太字','<span class=\"b\">','</span>');
QTags.addButton('qt-italic','斜体','<span class=\"i\">','</span>');
QTags.addButton('qt-big','フォント大','<span class=\"big\">','</span>');
QTags.addButton('qt-small','フォント小','<span class=\"small\">','</span>');
QTags.addButton('qt-red','赤字','<span class=\"red\">','</span>');
QTags.addButton('qt-blue','青字','<span class=\"blue\">','</span>');
QTags.addButton('qt-green','緑字','<span class=\"green\">','</span>');
QTags.addButton('qt-yellow','黄字','<span class=\"yellow\">','</span>');
QTags.addButton('qt-orange','橙字','<span class=\"orange\">','</span>');
QTags.addButton('qt-pink','ピンク字','<span class=\"pink\">','</span>');
QTags.addButton('qt-mk-red','マーカー(赤)','<span class=\"mk-red\">','</span>');
QTags.addButton('qt-mk-blue','マーカー(青)','<span class=\"mk-blue\">','</span>');
QTags.addButton('qt-mk-green','マーカー(緑)','<span class=\"mk-green\">','</span>');
QTags.addButton('qt-mk-yellow','マーカー(黄)','<span class=\"mk-yellow\">','</span>');
QTags.addButton('qt-mk-orange','マーカー(橙)','<span class=\"mk-orange\">','</span>');
QTags.addButton('qt-mk-pink','マーカー(ピンク)','<span class=\"mk-pink\">','</span>');
QTags.addButton('qt-bd','下線','<span class=\"bd\">','</span>');
QTags.addButton('qt-bd-red','下線(赤)','<span class=\"bd-red\">','</span>');
QTags.addButton('qt-bd-blue','下線(青)','<span class=\"bd-blue\">','</span>');
QTags.addButton('qt-bd-green','下線(緑)','<span class=\"bd-green\">','</span>');
QTags.addButton('qt-bd-yellow','下線(黄)','<span class=\"bd-yellow\">','</span>');
QTags.addButton('qt-bd-orange','下線(橙)','<span class=\"bd-orange\">','</span>');
QTags.addButton('qt-bd-pink','下線(ピンク)','<span class=\"bd-pink\">','</span>');
QTags.addButton('qt-label','ラベル','<span class=\"label\">','</span>');
QTags.addButton('qt-button','ボタン','<a href=\"\" class=\"btn\">','</a>');
QTags.addButton('qt-box','ボックス','<div class=\"box\">','</div>');
QTags.addButton('qt-box-red','ボックス(赤)','<div class=\"box-red\">','</div>');
QTags.addButton('qt-box-blue','ボックス(青)','<div class=\"box-blue\">','</div>');
QTags.addButton('qt-box-green','ボックス(緑)','<div class=\"box-green\">','</div>');
QTags.addButton('qt-box-yellow','ボックス(黄)','<div class=\"box-yellow\">','</div>');
</script>";
			$quicktags = str_replace(array("\r\n","\r","\n","\t"), '', $quicktags);
			echo $quicktags;
		}
	}
endif;
add_action( 'admin_print_footer_scripts', 'add_dp_custom_quicktags' );

// Add Tiny MCE buttons first row
if ( !function_exists( 'dp_mce_buttons' ) ):
	function dp_mce_buttons($buttons){
		// Remove buttons
		$remove = array('italic');
		// Add buttons
		array_push($buttons, "backcolor", "copy", "cut", "paste", "fontsizeselect", "fontselect", "cleanup");
		return array_diff($buttons,$remove);
	}
endif;
add_filter("mce_buttons", "dp_mce_buttons");

// Add original style box on Tiny MCE second row
if ( !function_exists( 'dp_mce_buttons_2' ) ):
	function dp_mce_buttons_2($buttons) {
		// Get the default select box
		$temp = array_shift($buttons);
		// Push the original styles
		array_unshift($buttons, 'styleselect');
		// Restore the select box
		array_unshift($buttons, $temp);

		return $buttons;
	}
endif;
add_filter('mce_buttons_2','dp_mce_buttons_2');

// Enable AddQuicktag in custom post type
function addquicktag_set_custom_post_type($post_types) {
	global $options;
	array_push($post_types, $options['news_cpt_slug_id']);
	return $post_types;
}
add_filter('addquicktag_post_types', 'addquicktag_set_custom_post_type');


// ----------------------------------------------------------------
// Add Column in posted list page
// ----------------------------------------------------------------
function add_posts_columns($columns) {
	$arr_fields = array(
		'thumbnail',
		'is_slideshow', 
		'is_headline',
		'dp_hide_title',
		'dp_hide_date',
		'dp_hide_author',
		'dp_hide_author_prof',
		'dp_hide_cat',
		'dp_hide_tag',
		'dp_hide_views',
		'dp_hide_fb_comment',
		'dp_hide_time_for_reading',
		'disable_sidebar',
		'dp_show_eyecatch_force',
		'disable_amp'
	);
	foreach ($arr_fields as $key => $field) {
		$columns[$field] = __($field, 'DigiPress');
	}
	return $columns;
}
function show_posts_custom_column($column_name, $post_id) {
	global $options;

	$arr_post_type 	= array('post', 'page', $options['news_cpt_slug_id']);
	$pattern 		= '/'.implode('|', $arr_post_type).'/i';
	$match_type 	= preg_match($pattern, get_post_type($post_id));
	if (!$match_type) return;

	// ********************
	// Thumbnail column
	// ********************
	if ($column_name === 'thumbnail') {
		$thumb = get_the_post_thumbnail($post_id, array(90,90), 'thumbnail');
		// Display
		if ( isset($thumb) && $thumb ) {
			echo $thumb;
		} else {
			_e('No Thumbnail', 'DigiPress');
		}
	}

	// ********************
	// Hidden columns
	// ********************
	// Check box items
	$arr_fields = array(
		'is_slideshow', 
		'is_headline',
		'dp_hide_title',
		'dp_hide_date',
		'dp_hide_author',
		'dp_hide_author_prof',
		'dp_hide_cat',
		'dp_hide_tag',
		'dp_hide_views',
		'dp_hide_fb_comment',
		'dp_hide_time_for_reading',
		'disable_sidebar',
		'dp_show_eyecatch_force',
		'disable_amp'
	);

	// Only hidden check box
	foreach ($arr_fields as $key => $field) {
		if ( $column_name === $field ) {
			$val = (bool)get_post_meta( $post_id , $field , true );
			if ( $val ) {
				$checked = 'checked';
			} else {
				$checked = '';
			}
			echo "<input type='checkbox' readonly $checked />";
		}
	}
}
add_filter( 'manage_posts_columns', 'add_posts_columns' );
add_action( 'manage_posts_custom_column', 'show_posts_custom_column', 10, 2 );
add_filter( 'manage_pages_columns', 'add_posts_columns' );
add_action( 'manage_pages_custom_column', 'show_posts_custom_column', 10, 2 );

// ----------------------------------------------------------------
// Add custom field option into quick edit
// ----------------------------------------------------------------
function add_custom_field_into_quick_edit( $column_name, $post_type ) {
	global $options;

	$arr_post_type 	= array('post', 'page', $options['news_cpt_slug_id']);
	$pattern 		= '/'.implode('|', $arr_post_type).'/i';
	$match_type 	= preg_match($pattern, $post_type);
	if (!$match_type) return;

	static $print_nonce = true;
	if ( $print_nonce ) {
		$print_nonce = false;
		wp_nonce_field( 'quick_edit_action', $post_type . '_edit_nonce' ); // For CSRF
	}

	// Check box items
	$arr_fields = array(
		'is_slideshow' 	=> 'Include slideshow', 
		'is_headline'	=> 'Include headline',
		'dp_hide_title'	=> 'Hide post title',
		'dp_hide_date'	=> 'Hide post date',
		'dp_hide_author'=> 'Hide post author',
		'dp_hide_author_prof' => 'Hide post author prof',
		'dp_hide_cat'	=> 'Hide post categories',
		'dp_hide_tag'	=> 'Hide post tags',
		'dp_hide_views'	=> 'Hide post views',
		'dp_hide_fb_comment'	=> 'Disable Facebook comments',
		'dp_hide_time_for_reading'=> 'Hide time for reading',
		'disable_sidebar'=> 'Set to 1 column',
		'dp_show_eyecatch_force'=> 'Show eyecatch under the title',
		'disable_amp' => 'Disable AMP'
	);

	// HTML
	$field_code = '';
	foreach ($arr_fields as $key => $field) {
		if ( $column_name === $key ) {
			$field_code = '<fieldset class="inline-edit-col-center inline-custom-meta"><div class="inline-edit-col column-'.$key.'"><label class="inline-edit-group">';
			$field_code .= '<input type="checkbox" name="'.$key.'" /><span class="checkbox-title">'.__($field, 'DigiPress').'</span>';
			$field_code .= '</label></div></fieldset>';
		}
	}

	echo $field_code;
}
add_action( 'quick_edit_custom_box', 'add_custom_field_into_quick_edit', 10, 2 );


// ----------------------------------------------------------------
// Save the custom field from quick edit
// ----------------------------------------------------------------
function save_custom_field_from_quick_edit( $post_id ) {
	global $options;

	$arr_post_type 	= array('post', 'page', $options['news_cpt_slug_id']);
	$pattern 		= '/'.implode('|', $arr_post_type).'/i';
	$match_type 	= preg_match($pattern, get_post_type($post_id));
	if (!$match_type) return;

	if ( !current_user_can( 'edit_post', $post_id ) ) return;
	$slug = get_post_type( $post_id ); // Target post type
	$_POST += array("{$slug}_edit_nonce" => '');
	if ( !wp_verify_nonce( $_POST["{$slug}_edit_nonce"], 'quick_edit_action' ) ) return;

	$arr_fields = array(
		'is_slideshow', 
		'is_headline',
		'dp_hide_title',
		'dp_hide_date',
		'dp_hide_author',
		'dp_hide_author_prof',
		'dp_hide_cat',
		'dp_hide_tag',
		'dp_hide_views',
		'dp_hide_fb_comment',
		'dp_hide_time_for_reading',
		'disable_sidebar',
		'dp_show_eyecatch_force',
		'disable_amp');

	// If check box
	foreach ($arr_fields as $key => $field) {
		if ( isset( $_REQUEST[$field] ) ) {
			update_post_meta($post_id, $field, true);
		} else {
			update_post_meta($post_id, $field, false);
		}
	}
}
add_action( 'save_post', 'save_custom_field_from_quick_edit' );