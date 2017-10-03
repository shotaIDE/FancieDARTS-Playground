<?php
/*******************************************************
* Create Style Sheet
*******************************************************/
/** ===================================================
* Create main CSS file.
*
* @param	string	$color
* @param	string	$sidebar
* @return	none
*/
function dp_css_create() {
	$options 		= get_option('dp_options');
	$options_visual = get_option('dp_options_visual');
	
	//Custom CSS file
	$file_path	=  DP_UPLOAD_DIR . "/css/visual-custom.css";
	//Get theme settings
	$originalCSS	= $options_visual['original_css'];

	// Create CSS
	$str_css = dp_custom_design_css(
					$options,
					$options_visual
				);

	// Strip blank, tags
	$str_css = str_replace(array("\r\n","\r","\n","\t"), '', $str_css);

	// Rewrite CSS for custom design
	dp_export_file($file_path, $str_css);
	// gzip compress
	dp_export_gzip($file_path, $str_css);
	
	return true;
}


/**  ===================================================
* Create css for custom design hack.
*
* @param	string	$headerImage	Custom header image.
* @param	string	$imgRepeat	Method image repeat.
* @param	string	$blindTitle	Whether site title is blind.
* @param	string	$blindDesc	Whether site description is blind.
* @return	none
*/
function dp_custom_design_css($options, $options_visual) {
	extract($options);
	extract($options_visual);

	$original_font_size_px				= 14;
	$original_font_size_em				= 1.1;

	// For CS
	$body_css = '';
	$site_bg_img_css= '';
	$base_font_size_css= '';
	$noto_sans_css = '';
	$header_slideshow_css = '';
	$global_menu_css = '';
	$bx_slider_css = '';
	$container_css = '';
	$entry_css = '';
	$sidebar_css = '';
	$list_hover_css = '';
	$meta_area_css = '';
	$base_link_color_css = '';
	$base_link_hover_color_css = '';
	$navigation_link_color_css = '';
	$link_filled_color_css = '';
	$header_filter_css = '';
	$entry_link_css = '';
	$border_color_css = '';
	$bordered_obj_css = '';
	$common_bg_color_css = '';
	$quote_css = '';
	$comment_box_css = '';
	$tooltip_css = '';
	$search_form_css = '';
	$footer_widget_css = '';
	$footer_css= '';
	$form_css = '';
	$ranking_css = '';
	$cat_colos_css = '';
	$btn_label_css = '';
	$wow_css = '';


	// *************************************************
	// layout CSS
	// *************************************************
	// Footer Column number
	switch ($footer_col_number) {
		case 1:
			$footer_widget_css = <<<_EOD_
.ft-widget-content .widget-area {
	width:100%;
}
_EOD_;
			break;
		case 2:
			$footer_widget_css = <<<_EOD_
.ft-widget-content .widget-area {
	width:47.8%;
}
.ft-widget-content .widget-area.one{
	margin:0 3.8% 0 0;
}
_EOD_;
			break;
		case 3:
			$footer_widget_css = <<<_EOD_
.ft-widget-content .widget-area {
	width:30.8%;
}
.ft-widget-content .widget-area.two{
	margin:0 3.8%;
}
_EOD_;
			break;
		case 4:
			$footer_widget_css = <<<_EOD_
.ft-widget-content .widget-area {
	width:22.6%;
	margin:0 3.2% 0 0;
}
.ft-widget-content .widget-area.four{
	margin:0;
}
_EOD_;
			break;
		default:
			$footer_widget_css = "";
			break;
	}


	// RGB
	$rgb_header_banner_txt_shadow = hexToRgb($header_banner_text_shadow_color);
	$rgb_header_banner_txt = hexToRgb($header_banner_font_color);
	$rgb_container_bg = hexToRgb($container_bg_color);
	$rgb_base_font = hexToRgb($base_font_color);
	$rgb_base_link = hexToRgb($base_link_color);
	$rgb_hd_menu_bg = hexToRgb($header_menu_bgcolor);
	$rgb_hd_menu_link = hexToRgb($header_menu_link_color);
	$rgb_footer_text_color = hexToRgb($footer_text_color);
	$rgb_accent_color = hexToRgb($accent_color);


	// *************************************************
	// Body CSS
	// *************************************************
	// Body CSS
	$body_css = 
"body{
	background-color:".$site_bg_color.";
}";

	// *************************************************
	// Background image
	// *************************************************
	//Background image
	if ( $dp_background_img == "none" || !$dp_background_img ) {
		$site_bg_img_css ='';
	} else {
		$dp_background_img = is_ssl() ? str_replace('http:', 'https:', $dp_background_img) : $dp_background_img;
		$site_bg_img_css ="background-image:url(".$dp_background_img.");background-repeat:".$dp_background_repeat.";background-position:left top;";
	}
	

	// *************************************************
	// Container CSS
	// *************************************************
	$container_css = 
".dp-container{
	background-color:".$site_bg_color.";
	".$site_bg_img_css."
}
.header_container.scroll,
.inside-title,
#com_trb_whole,
#reply-title,
.loop-article,
.single-article,
.dp_related_posts ul,
.comment-form,
.widget-container .dp_tab_widget_ul,
.widget-container .dp_tab_contents,
.widget-container.mobile .recent_entries,
.widget-content:not(.single) .widget-box:not(.loop-div),
.sidebar .widget-box,
#gotop.pc{
	color:".$base_font_color.";
	box-shadow:0 0 4px rgba(". $rgb_base_font[0] . ", " . $rgb_base_font[1] . "," . $rgb_base_font[2] . ", 0.18);
	background-color:".$container_bg_color.";
}
#gotop.pc:after{
	background-color:rgba(". $rgb_base_font[0] . ", " . $rgb_base_font[1] . "," . $rgb_base_font[2] . ", 0.08);
}
.btbar_btn:after{
	background-color:rgba(". $rgb_container_bg[0] . ", " . $rgb_container_bg[1] . "," . $rgb_container_bg[2] . ", 0.1);
}
.loop-article:hover{
	box-shadow:0 0 25px rgba(". $rgb_base_font[0] . ", " . $rgb_base_font[1] . "," . $rgb_base_font[2] . ", 0.28);
}
.portfolio .loop-article{
	box-shadow:0 0 6px rgba(". $rgb_base_font[0] . ", " . $rgb_base_font[1] . "," . $rgb_base_font[2] . ", 0.34);
}
.portfolio .loop-article:hover{
	box-shadow:0 0 24px rgba(". $rgb_base_font[0] . ", " . $rgb_base_font[1] . "," . $rgb_base_font[2] . ", 0.42);
}
#headline-sec{
	border-color:rgba(". $rgb_base_font[0] . ", " . $rgb_base_font[1] . "," . $rgb_base_font[2] . ", 0.2);
}
#headline-sec,
.loop-section:not(.portfolio) .loop-article-content,
.mm-page{
	color:".$base_font_color.";
	background-color:".$container_bg_color.";
}
.dp-container a,
.dp-container a:hover,
.dp-container a:visited,
.main-wrap a,
.main-wrap a:visited,
.mm-page a,
.mm-page a:visited{
	color:".$base_font_color.";
}
.pace .pace-activity,
.pace .pace-activity:before{
	border-color:".$accent_color." transparent transparent;
}";



	// *************************************************
	// entry CSS
	// *************************************************
	// Font size
	if (!$base_font_size || ($base_font_size == '')) {
		if ( !$base_font_size_unit || $base_font_size_unit == '' ) {
			$base_font_size_css = 
".entry,
.widget-box{
	font-size:".$original_font_size_px."px;
}";
		} else {
			$base_font_size_css = 
".entry,
.widget-box{
	font-size:".$original_font_size_em."em".$options_visual['base_font_size_unit'].";
}";
		}
	} else {
		if ( !$base_font_size_unit || $base_font_size_unit == '' ) {
			$base_font_size_css = 
".entry,
.widget-box{
	font-size:".$base_font_size."px;
}";
		} else {
			$base_font_size_css = 
".entry,
.widget-box{
	font-size:".$base_font_size.$base_font_size_unit.";
}";
		}
	}
	// For mobile
	if (!$base_font_size_mb || ($base_font_size_mb == '')) {
		if ( !$base_font_size_mb_unit || $base_font_size_mb_unit == '' ) {
			$base_font_size_css .= 
".mb-theme .entry,
.mb-theme .widget-box{
	font-size:".$original_font_size_px."px;
}";
		} else {
			$base_font_size_css .= 
".mb-theme .entry,
.mb-theme .widget-box{
	font-size:".$original_font_size_em."em".$options_visual['base_font_size_mb_unit'].";
}";
		}
	} else {
		if ( !$base_font_size_mb_unit || $base_font_size_mb_unit == '' ) {
			$base_font_size_css .= 
".mb-theme .entry,
.mb-theme .widget-box{
	font-size:".$base_font_size_mb."px;
}";
		} else {
			$base_font_size_css .= 
".mb-theme .entry,
.mb-theme .widget-box{
	font-size:".$base_font_size_mb.$base_font_size_mb_unit.";
}";
		}
	}

	//Link Style
	if ($base_link_underline == 1 || $base_link_underline == null) {
		if ($base_link_bold) {
			$entry_link_css	= 
".dp-container .entry a{
	font-weight:bold;text-decoration:none;
}
.dp-container .entry a:hover{
	text-decoration:underline;
}";
		} else {
			$entry_link_css	= 
".dp-container .entry a{
	font-weight:normal;
	text-decoration:none;
}
.dp-container .entry a:hover{
	text-decoration:underline;
}";
		}
	} else {
		if ($base_link_bold) {
			$entry_link_css	= 
".dp-container .entry a{
	font-weight:bold;
	text-decoration:underline;
}
.dp-container .entry a:hover{
	text-decoration:none;
}";
		} else {
			$entry_link_css	= 
".dp-container .entry a{
	font-weight:normal;
	text-decoration:underline;
}
.dp-container .entry a:hover{
	text-decoration:none;
}";
		}
	}

	// *************************************************
	// anchor text link CSS
	// *************************************************
	$base_link_color_css = 
".dp-container .entry a,
.dp-container .entry a:visited,
.dp-container .dp_text_widget a,
.dp-container .dp_text_widget a:visited,
.dp-container .textwidget a,
.dp-container .textwidget a:visited,
#comment_section .commentlist a:hover{
	color:" . $base_link_color . ";
}";

	$link_filled_color_css 			= 
".single-date-top,
.dp-container pre,
.entry input[type=\"submit\"],
.plane-label,
input#submit{
	color:". $container_bg_color.";
	background-color:" . $accent_color . ";
}";


	//Base hovering anchor text color
	$base_link_hover_color_css	= 
".dp-container .entry a:hover,
.dp-container .dp_text_widget a:hover,
.dp-container .textwidget a:hover,
.fake-hover:hover{
	color:".$base_link_hover_color.";
}";


	// ***********************************
	// navigation color CSS
	// ***********************************
	$navigation_link_color_css = 
".tagcloud a,
#comment_section .comment-meta .comment-reply-link,
.entry>p>a.more-link,
.dp-container .entry .dp-pagenavi a,
.dp-container .entry .dp-pagenavi a:visited,
.dp-pagenavi a,
.dp-pagenavi a:visited,
.dp-pagenavi .page-numbers:not(.dots),
.navigation a,
.navigation a:visited{
	color:".$base_font_color.";
}
#commentform input[type=\"submit\"]{
	color:".$accent_color.";
}
#commentform input[type=\"submit\"]:hover{
	color:".$container_bg_color.";
	background-color:".$accent_color.";
	border-color:".$accent_color.";
}
.dp-container .more-entry-link a{
	background-color:".$container_bg_color.";
}
.single_post_meta .meta-cat a:hover,
.dp_related_posts.horizontal .meta-cat a:hover,
.tagcloud a:hover,
.dp-container .more-entry-link a:hover,
#comment_section .comment-meta .comment-reply-link:hover,
.entry>p>a.more-link:hover{
	color:".$container_bg_color.";
	background-color:".$base_font_color.";
	border-color:".$base_font_color.";
}
.dp_feed_widget a:before,
.dp_feed_widget a:after,
.dp_feed_widget .r-wrap:before,
.dp_feed_widget .r-wrap:after,
.navigation a:before,
.navigation a:after,
.navigation .r-wrap:before,
.navigation .r-wrap:after,
.dp-pagenavi a:before,
.dp-pagenavi a:after,
.dp-pagenavi .r-wrap:before,
.dp-pagenavi .r-wrap:after,
.single-nav .navlink:before,
.single-nav .navlink:after,
.single-nav .r-wrap:before,
.single-nav .r-wrap:after,
.author_sns a:before,
.author_sns a:after,
.author_sns .r-wrap:before,
.author_sns .r-wrap:after,
.loop-section .more-link a:before,
.loop-section .more-link a:after,
.loop-section .more-link .r-wrap:before,
.loop-section .more-link .r-wrap:after,
.loop-section.magazine .loop-article:before{
	background-color:".$base_font_color.";
}
.loop-section .more-link a,
.navigation a,
.dp-pagenavi a,
.single-nav .navlink,
.single-nav i{
	box-shadow:0 0 5px rgba(".$rgb_base_font[0].",".$rgb_base_font[1].",".$rgb_base_font[2].",0.24);
	background-color:".$container_bg_color.";
	color:".$base_font_color.";
}";


	// ***********************************
	// Post meta info CSS
	// ***********************************
	$darken_accent_color = darkenColor($accent_color);
	$meta_area_css = 
".loop-section:not(.portfolio) .loop-date.designed{
	background-color:".$accent_color.";
	color:".$container_bg_color.";
}
.loop-section:not(.portfolio) .loop-date.designed:before{
	background-color:rgba(".$rgb_container_bg[0].",".$rgb_container_bg[1].",".$rgb_container_bg[2].",0.2);
}
.single-article .single_post_meta .loop-share-num a,
.loop-section.normal .loop-share-num a,
.loop-section.blog .loop-share-num a,
.loop-section.magazine .loop-share-num a,
.loop-section.mobile .loop-share-num a,
.loop-section .loop-title a,
.loop-section .meta-author a{
	color:".$base_font_color.";
}
.loop-section:not(.portfolio) .loop-share-num i,
.loop-section.mobile.portfolio .loop-share-num i,
.recent_entries .loop-share-num i{
	color:".$base_font_color.";
	border-color:rgba(".$rgb_base_font[0].",".$rgb_base_font[1].",".$rgb_base_font[2].",0.09);
	background-color:rgba(".$rgb_base_font[0].",".$rgb_base_font[1].",".$rgb_base_font[2].",0.05);
}
.loop-excerpt{
	color:rgba(". $rgb_base_font[0] . ", " . $rgb_base_font[1] . "," . $rgb_base_font[2] . ", 0.74);
}
.label_ft{
	border-color:".$container_bg_color.";
}";


	// *************************************************
	// Border CSS
	// *************************************************
	$border_color_css = 
"hr{
	border-color:rgba(". $rgb_base_font[0] . ", " . $rgb_base_font[1] . "," . $rgb_base_font[2] . ", 0.4);
}
address,
#switch_comment_type li.active_tab{
	border-color:".$accent_color.";
}
.entry h1, .entry h2, .entry h3, .entry h4, .entry h5, .entry h6, .dp_text_widget h1, .dp_text_widget h2, .dp_text_widget h3, .dp_text_widget h4, .dp_text_widget h5, .dp_text_widget h6, .textwidget h1, .textwidget h2, .textwidget h3, .textwidget h4, .textwidget h5, .textwidget h6{
	border-color:rgba(". $rgb_base_font[0] . ", " . $rgb_base_font[1] . "," . $rgb_base_font[2] . ", 0.18);
}
.entry h1:after, .entry h2:after, .entry h3:after, .entry h4:after, .entry h5:after, .entry h6:after, .dp_text_widget h1:after, .dp_text_widget h2:after, .dp_text_widget h3:after, .dp_text_widget h4:after, .dp_text_widget h5:after, .dp_text_widget h6:after, .textwidget h1:after, .textwidget h2:after, .textwidget h3:after, .textwidget h4:after, .textwidget h5:after, .textwidget h6:after{
	background-color:rgba(". $rgb_base_font[0] . ", " . $rgb_base_font[1] . "," . $rgb_base_font[2] . ", 0.08);
}
.widget_pages li a,
.widget_nav_menu li a,
.widget_categories li a,
.widget_mycategoryorder li a,
.recent_entries li,
.dp_related_posts.vertical li,
.mb-theme .dp_related_posts li,
.dp-container table th,
.dp-container table td,
.dp-container dl,
.dp-container dt,
.dp-container dd,
.entrylist-date,
#switch_comment_type li.inactive_tab,
div#comment-author,
div#comment-email,
div#comment-url,
div#comment-comment,
#comment_section li.comment,
#comment_section li.trackback,
#comment_section li.pingback{
	border-color:rgba(". $rgb_base_font[0] . ", " . $rgb_base_font[1] . "," . $rgb_base_font[2] . ", 0.16);
}
#comment_section ul.children{
	background-color:rgba(". $rgb_base_font[0] . ", " . $rgb_base_font[1] . "," . $rgb_base_font[2] . ", 0.06);
}
#comment_section ul.children:before{
	border-color:transparent transparent rgba(". $rgb_base_font[0] . ", " . $rgb_base_font[1] . "," . $rgb_base_font[2] . ", 0.06) transparent;
}
.post-slider .slide:before,
.post-slider .slide:after, 
.post-slider .slide .r-wrap:before, 
.post-slider .slide .r-wrap:after,
.loop-post-thumb:before,
.loop-post-thumb:after, 
.loop-post-thumb .r-wrap:before, 
.loop-post-thumb .r-wrap:after,
.hd_sns_links a:before,
.hd_sns_links a:after,
.hd_sns_links .r-wrap:before,
.hd_sns_links .r-wrap:after{
	background-color:".$container_bg_color.";
}
.loop-media-icon{
	color:".$container_bg_color.";
}
.widget_pages li a:after,
.widget_nav_menu li a:after,
.widget_nav_menu li.current-menu-item a:after,
.widget_categories li a:after,
.widget_categories li.current-cat a:after,
.widget_mycategoryorder li a:after,
.recent_entries li:after,
.dp_related_posts.vertical li:after{
	background-color:rgba(". $rgb_base_font[0] . ", " . $rgb_base_font[1] . "," . $rgb_base_font[2] . ", 0.54);
}";

	

	// *************************************************
	// Comon background color CSS
	// *************************************************
	$common_bg_color_css = 
".dp-container dt,
.dp-container table th,
.entry .wp-caption,
#wp-calendar caption,
#wp-calendar th, 
#wp-calendar td{
	background-color:rgba(". $rgb_base_font[0] . ", " . $rgb_base_font[1] . "," . $rgb_base_font[2] . ", 0.04);
}
.mb-theme .single-nav li{
	background-color:rgba(". $rgb_base_font[0] . ", " . $rgb_base_font[1] . "," . $rgb_base_font[2] . ", 0.06);
}
#wp-calendar tbody td#today,
#wp-calendar tbody td a:before{
	background-color:rgba(". $rgb_base_font[0] . ", " . $rgb_base_font[1] . "," . $rgb_base_font[2] . ", 0.14);
}";

	
	// *************************************************
	// Bordered object css
	// *************************************************
	$bordered_obj_css = 
".entry ul li:before, 
.dp_text_widget ul li:before,
.textwidget ul li:before{
	background-color:".$accent_color.";
}
.single-article header:before,
.single-article .single_post_meta,
.single-article .single_post_meta .loop-share-num div[class^=\"bg-\"],
.dp_related_posts.news li,
.wd-title,
.dp_tab_widget_ul,
.entry .wp-caption,
#searchform,
table.gsc-search-box{
	border-color:rgba(". $rgb_base_font[0] . ", " . $rgb_base_font[1] . "," . $rgb_base_font[2] . ", 0.16);
}
.inside-title:before,
#reply-title:before,
.wd-title:before{
	border-top-color:rgba(". $rgb_base_font[0] . ", " . $rgb_base_font[1] . "," . $rgb_base_font[2] . ", 0.16);
}
.inside-title:after,
#reply-title:after,
.wd-title:after{
	border-top-color:".$container_bg_color.";
}
.author_info .author_img img.avatar{
	border-color:rgba(". $rgb_accent_color[0] . ", " . $rgb_accent_color[1] . "," . $rgb_accent_color[2] . ", 0.18);
}
.dp_tab_widget_ul li:after{
	background-color:".$accent_color.";
}
.cat-item .count{
	color:".$container_bg_color.";
	background-color:rgba(". $rgb_base_font[0] . ", " . $rgb_base_font[1] . "," . $rgb_base_font[2] . ", 0.5);
}";



	// List item hover color
	$list_hover_css = 
"span.v_sub_menu_btn{
	background-color:rgba(". $rgb_base_font[0] . ", " . $rgb_base_font[1] . "," . $rgb_base_font[2] . ", 0.04);
}";


	// *************************************************
	// Global menu CSS
	// *************************************************
	 $split_header_menu_bgcolor = str_replace('#', '', $header_menu_bgcolor);
	 $global_menu_css = 
".header_container{
	border-color:".$header_menu_link_color.";
}
.header_container.pc,
.header_container.pc.scroll:hover,
.header_container.mb.fixed,
#global_menu_ul .sub-menu li:hover,
#global_menu_ul .sub-menu li.current-menu-item{
	color:".$header_menu_link_color.";
	background-color:".$header_menu_bgcolor.";
}
.header_container.mb{
	border-color:".$accent_color.";
	color:".$header_menu_link_color.";
	background-color:".$header_menu_bgcolor.";
}
.header_container.pc.scroll{
	background-color:rgba(".$rgb_hd_menu_bg[0].",".$rgb_hd_menu_bg[1].",".$rgb_hd_menu_bg[2].",0.68);
}
#hidden-search-wrapper,
.hidden-searchtext{
	color:".$header_menu_link_color.";
	background-color:rgba(".$rgb_hd_menu_bg[0].",".$rgb_hd_menu_bg[1].",".$rgb_hd_menu_bg[2].",0.92);
}
.hd_sns_links ul li a:before,
.hd_sns_links ul li a:after,
.hd_sns_links ul li .r-wrap:before,
.hd_sns_links ul li .r-wrap:after,
#hd_searchform:before,
#hd_searchform:after,
#hd_searchform span:before,
#hd_searchform span:after,
#hd_hidden_menu_btn:before,
#hd_hidden_menu_btn:after,
#hd_hidden_menu_btn span,
#expand_float_menu.show i:before,
#expand_float_menu.show i:after {
	background-color:".$header_menu_link_color.";
}
.header_container a,
.header_container a:visited,
#hd_tel a,
.mm-page .header_container a,
.mm-page .header_container a:visited{
	color:".$header_menu_link_color.";
}
.header_container a:hover,
.mm-page .header_container a:hover{
	color:".$header_menu_link_hover_color.";
}
#global_menu_ul .sub-menu{
	background-color:rgba(".$rgb_hd_menu_bg[0].",".$rgb_hd_menu_bg[1].",".$rgb_hd_menu_bg[2].",0.78);
	box-shadow:0 1px 4px rgba(".$rgb_hd_menu_link[0].",".$rgb_hd_menu_link[1].",".$rgb_hd_menu_link[2].", 0.7);
}
#global_menu_ul a.menu-link:after{
	background-color:".$accent_color.";
}
#global_menu_nav.mq-mode{
	color:".$header_menu_link_color.";
	background-color:".$header_menu_bgcolor.";
}
.hidden-close-btn:before,
.hidden-close-btn:after,
#global_menu_nav.mq-mode .mq_sub_li{
	color:".$header_menu_bgcolor.";
	background-color:".$header_menu_link_color.";
}
.header-banner-outer,
.mm-menu {
	background-color:".$header_menu_bgcolor.";
}
.mm-menu,
.mm-listview li a{
	color:".$header_menu_link_color.";	
}
.mm-menu .mm-navbar>a{	
	color:rgba(". $rgb_hd_menu_link[0] . ", " . $rgb_hd_menu_link[1] . "," . $rgb_hd_menu_link[2] . ", 0.6);
}
#global_menu_nav.mq-mode,
#global_menu_nav.mq-mode .menu-link,
.mm-menu .mm-navbar,
.mm-menu .mm-listview > li:after,
.mm-menu .mm-listview>li>a.mm-prev:after,
.mm-menu .mm-listview>li>a.mm-next:before{
	border-color:rgba(". $rgb_hd_menu_link[0] . ", " . $rgb_hd_menu_link[1] . "," . $rgb_hd_menu_link[2] . ", 0.22);	
}
.mm-menu .mm-navbar .mm-btn:before, 
.mm-menu .mm-navbar .mm-btn:after,
.mm-menu .mm-listview>li>a.mm-prev:before, 
.mm-menu .mm-listview>li>a.mm-next:after{
	border-color:rgba(". $rgb_hd_menu_link[0] . ", " . $rgb_hd_menu_link[1] . "," . $rgb_hd_menu_link[2] . ", 0.36);
}
.mm-menu .mm-listview li.current-menu-item:after,
.mm-menu .mm-listview li.current_page_item:after{
	border-color:".$header_menu_link_hover_color.";
}
.mm-menu .mm-listview > li.mm-selected > a:not(.mm-subopen),
.mm-menu .mm-listview > li.mm-selected > span{
	background-color:rgba(". $rgb_hd_menu_link[0] . ", " . $rgb_hd_menu_link[1] . "," . $rgb_hd_menu_link[2] . ", 0.8);
}";

	
	// *************************************************
	// Header Slideshow CSS 
	// *************************************************
	$txt_shadow = (bool)$header_banner_text_shadow_enable ? "text-shadow:0 0 26px rgba(".$rgb_header_banner_txt_shadow[0].",".$rgb_header_banner_txt_shadow[1].",".$rgb_header_banner_txt_shadow[2].",0.56);" : "";
	$txt_shadow_slideshow = (bool)$header_banner_text_shadow_enable ? "text-shadow:0 0 10px rgba(".$rgb_header_banner_txt_shadow[0].",".$rgb_header_banner_txt_shadow[1].",".$rgb_header_banner_txt_shadow[2].",0.9);" : "";
	$txt_shadow_mb = (bool)$header_banner_text_shadow_enable ? "text-shadow:0 0 15px rgba(".$rgb_header_banner_txt_shadow[0].",".$rgb_header_banner_txt_shadow[1].",".$rgb_header_banner_txt_shadow[2].",0.72);" : "";

	$header_slideshow_css = 
".hd_slideshow .bx-wrapper .bx-pager .bx-pager-item a{
	background-color:".$header_banner_font_color.";
	".$txt_shadow."
}
.hd_slideshow .bx-controls-direction a{
	color:".$header_banner_font_color.";
}
.hd_slideshow .slide:hover .sl-meta,
.loop-post-thumb:hover .r-wrap>span:after{
	background-color:rgba(". $rgb_accent_color[0] . ", " . $rgb_accent_color[1] . "," . $rgb_accent_color[2] . ", 0.86);
}
.hd_slideshow .slide .sl-cat{
	border-color:".$header_banner_font_color.";
}
.header-banner-inner.post-slider,
.header-banner-inner.post-slider a,
.header-banner-inner.post-slider a:visited{
	".$txt_shadow_slideshow."
}
.bx-controls-direction a{
	background-color:".$container_bg_color.";
	box-shadow:0 0 5px rgba(". $rgb_base_font[0] . ", " . $rgb_base_font[1] . "," . $rgb_base_font[2] . ", 0.44);
}
.bx-controls-direction a i{
	color:".$accent_color.";
}
.header-banner-inner,
.header-banner-inner a, 
.header-banner-inner a:hover,
.header-banner-inner a:visited{
	color:".$header_banner_font_color.";
	".$txt_shadow."
}
.mb-theme .header-banner-inner,
.mb-theme .header-banner-inner a, 
.mb-theme .header-banner-inner a:hover,
.mb-theme .header-banner-inner a:visited{
	color:".$header_banner_font_color.";
	".$txt_shadow_mb."
}
#banner_caption:before,
#banner_caption:after{
	background-color:".$header_banner_font_color.";
}
";

	// *************************************************
	// Bx Slider object CSS 
	// *************************************************
	$bx_slider_css =
".bx-wrapper .bx-pager .bx-pager-item a{
	background-color:".$base_font_color.";
}
.bx-controls-direction a{
	color:".$container_bg_color.";
}";

	
	// *************************************************
	// Search form CSS
	// *************************************************
	$search_form_css = 
"#searchform input#searchtext{
	color:".$base_font_color.";
}
#searchform:before{
	color:".$base_font_color.";
}
#searchform input:focus {
	background-color:".$container_bg_color.";
}
#hd_searchform td.gsc-search-button:before,
#hd_searchform #searchform input#searchtext,
#hd_searchform #searchform:hover input#searchtext::-webkit-input-placeholder,
#hd_searchform #searchform input#searchtext:focus::-webkit-input-placeholder {
	color:".$header_menu_link_color.";
}
#hd_searchform #searchform,
#hd_searchform #searchform:before{
	color:rgba(".$rgb_hd_menu_link[0].",".$rgb_hd_menu_link[1].",".$rgb_hd_menu_link[2].",0.7);
}
#hd_searchform.mb #searchform{
	border-color:rgba(".$rgb_hd_menu_link[0].",".$rgb_hd_menu_link[1].",".$rgb_hd_menu_link[2].",0.22);
}
#hd_searchform:hover #searchform input#searchtext{
	color:".$header_menu_bgcolor.";
	background-color:".$header_menu_link_color.";
}
#hd_searchform:hover #searchform:before{
	color:".$header_menu_bgcolor.";
}
#hd_searchform.mb-theme .searchtext_div,
#hd_searchform.mb-theme #searchform span.searchsubmit{
	color:".$header_menu_link_color.";
	background-color:".$header_menu_bgcolor.";
}
table.gsc-search-box{
	background-color:".$container_bg_color."!important;
}
td.gsc-search-button{
	color:".$base_font_color."!important;
	background-color:".$container_bg_color."!important;
}";


	// *************************************************
	// Blockquote CSS
	// *************************************************
	//Quotes tag
	$quote_css = 
".dp-container blockquote,
.dp-container q{
	background-color:rgba(". $rgb_base_font[0] . ", " . $rgb_base_font[1] . "," . $rgb_base_font[2] . ", 0.04);
	border:1px solid rgba(". $rgb_base_font[0] . ", " . $rgb_base_font[1] . "," . $rgb_base_font[2] . ", 0.08);
}
.dp-container code{
	background-color:rgba(". $rgb_base_font[0] . ", " . $rgb_base_font[1] . "," . $rgb_base_font[2] . ", 0.05);
	border:1px solid rgba(". $rgb_base_font[0] . ", " . $rgb_base_font[1] . "," . $rgb_base_font[2] . ", 0.48);
}
.dp-container blockquote:before,
.dp-container blockquote:after{
	color:".$accent_color.";
}";

	
	// *************************************************
	// Comment area CSS
	// *************************************************
	$comment_box_css = 
"#comment_section li.comment:before,
#comment_section li.trackback:before,
#comment_section li.pingback:before {
	background-color:rgba(". $rgb_base_font[0] . ", " . $rgb_base_font[1] . "," . $rgb_base_font[2] . ", 0.06);
}
#comment_section li.comment:hover:before,
#comment_section li.trackback:hover:before,
#comment_section li.pingback:hover:before {
	background-color:".$base_link_color.";
}";


	// *************************************************
	// Form CSS
	// *************************************************
	$form_css = 
"input[type=\"checkbox\"]:checked,
input[type=\"radio\"]:checked {
	background-color:".$base_link_color.";
}
select{
	border:1px solid rgba(". $rgb_base_font[0] . ", " . $rgb_base_font[1] . "," . $rgb_base_font[2] . ", 0.14);
	color:".$base_font_color.";
}
.footer select{
	border-color:rgba(". $rgb_footer_text_color[0] . ", " . $rgb_footer_text_color[1] . "," . $rgb_footer_text_color[2] . ",0.14);
	color:".$footer_text_color.";
}";

	// *************************************************
	// Sidebar CSS
	// *************************************************
	$sidebar_css = "";
	
	// *************************************************
	// Ranking CSS
	// *************************************************
	$ranking_css = 
".rank_label.thumb{
	color:".$container_bg_color.";
}
.rank_label.thumb:before{
	border-color:".$accent_color.";
}
.rank_label.no-thumb{
	color:rgba(".$rgb_base_font[0].",".$rgb_base_font[1].",".$rgb_base_font[2].",0.1);
}";

	// *************************************************
	// category colors CSS
	// *************************************************
	$cat_colos_css = 
".ct-hd{
	background-color:".$accent_color.";
}
.meta-cat a{
	color:".$base_font_color.";
}";
	foreach ($cat_ids as $key => $cat_id) {
		$rgb = '';
		if (!empty($cat_colors[$key])) {
			$rgb = hexToRgb($cat_colors[$key]);
			$cat_colos_css .= 
".ct-hd.cat-color".$cat_id."{
	background-color:".$cat_colors[$key].";
}
.meta-cat a.cat-color".$cat_id."{
	color:".$cat_colors[$key].";
}
.single_post_meta .meta-cat a.cat-color".$cat_id.":hover,
.dp_related_posts.horizontal .meta-cat a.cat-color".$cat_id.":hover{
	color:".$container_bg_color.";
	border-color:".$cat_colors[$key].";
	background-color:".$cat_colors[$key].";
}";
		}
	}

	// *************************************************
	// wow js CSS
	// *************************************************
	if (!(bool)$options['disable_wow_js'] && !(bool)$options['disable_wow_js_mb']){
		// Both
		$wow_css = ".wow{visibility:hidden}";
	} else if (!(bool)$options['disable_wow_js'] && (bool)$options['disable_wow_js_mb']) {
		// Only PC
		$wow_css = "body:not(.mb-theme) .wow{visibility:hidden}";
	} else if ((bool)$options['disable_wow_js'] && !(bool)$options['disable_wow_js_mb']) {
		// Only Mobile
		$wow_css = ".mb-theme .wow{visibility:hidden}";
	}	

	// *************************************************
	// Tooltip CSS
	// *************************************************
	$tooltip_css = 
".tooltip-arrow{
	border-color:transparent transparent " . $base_font_color . " transparent;
}
.tooltip-msg{
	color:". $container_bg_color .";
	background-color:" . $base_font_color . ";
}";


	// *************************************************
	// Default Button label color
	// *************************************************
	if ($options['decoration_type'] !== 'bootstrap'){
		$btn_label_css = 
".btn{
	border-color:".$accent_color.";
	color:".$accent_color."!important;
}
.label,
.btn:after{
	background-color:".$accent_color."
}
.label:after{
	background-color:".$container_bg_color.";
}
#footer .label:after{
	background-color:".$footer_bgcolor.";
}";
	}

	// *************************************************
	// Footer area CSS
	// *************************************************
	$footer_css = 
"#footer{
	background-color:".$footer_bgcolor.";
	color:".$footer_text_color.";
	box-shadow:0 0 30px rgba(". $rgb_base_font[0] . ", " . $rgb_base_font[1] . "," . $rgb_base_font[2] . ", 0.2);
}
#footer a,
#footer a:visited{
	color:".$footer_link_color.";
}
#footer a:hover{
	color:".$footer_link_hover_color.";
}
#footer .inside-title,
#footer .wd-title,
#footer .dp_tab_widget_ul{
	border-color:rgba(".$rgb_footer_text_color[0].",".$rgb_footer_text_color[1].",".$rgb_footer_text_color[2].",0.6);
}
#footer .inside-title:before,
#footer .wd-title:before{
	border-top-color:rgba(".$rgb_footer_text_color[0].",".$rgb_footer_text_color[1].",".$rgb_footer_text_color[2].",0.6);
}
#footer .inside-title:after,
#footer .wd-title:after{
	border-top-color:".$footer_bgcolor.";
}
#footer .dp_tab_widget_ul li:after{
	background-color:rgba(". $rgb_footer_text_color[0] . ", " . $rgb_footer_text_color[1] . "," . $rgb_footer_text_color[2] . ", 0.4);
}
#footer .dp_tab_widget_ul li:hover:after,
#footer .dp_tab_widget_ul li.active_tab:after{
	background-color:".$accent_color.";
}
#footer .more-entry-link a{
	background-color:".$footer_bgcolor.";
}
#footer .tagcloud a:hover,
#footer .more-entry-link a:hover{
	color:".$footer_bgcolor.";
	background-color:".$footer_text_color.";
	border-color:".$footer_text_color.";
}
#footer .recent_entries .loop-share-num i{
	color:".$footer_text_color.";
	border-color:rgba(". $rgb_footer_text_color[0] . ", " . $rgb_footer_text_color[1] . "," . $rgb_footer_text_color[2] . ", 0.09);
	background-color:rgba(". $rgb_footer_text_color[0] . ", " . $rgb_footer_text_color[1] . "," . $rgb_footer_text_color[2] . ", 0.05);
}
#footer #wp-calendar caption, 
#footer #wp-calendar th,
#footer #wp-calendar td{
	background-color:rgba(". $rgb_footer_text_color[0] . ", " . $rgb_footer_text_color[1] . "," . $rgb_footer_text_color[2] . ", 0.04);
}
#footer #wp-calendar tbody td#today,
#footer #wp-calendar tbody td a:before{
	background-color:rgba(". $rgb_footer_text_color[0] . ", " . $rgb_footer_text_color[1] . "," . $rgb_footer_text_color[2] . ", 0.14);
}
#footer .cat-item .count{
	color:".$footer_bgcolor.";
	background-color:rgba(". $rgb_footer_text_color[0] . ", " . $rgb_footer_text_color[1] . "," . $rgb_footer_text_color[2] . ", 0.5);
}
#footer #searchform{
	border-color:rgba(".$rgb_footer_text_color[0].",".$rgb_footer_text_color[1].",".$rgb_footer_text_color[2].",0.14);
}
#footer #searchform input#searchtext {
	color:".$footer_text_color.";
}
#footer #searchform:before{
	color:".$footer_text_color.";
}
#footer .dp_feed_widget a:before, 
#footer .dp_feed_widget a:after, 
#footer .dp_feed_widget .r-wrap:before, 
#footer .dp_feed_widget .r-wrap:after{
	background-color:".$footer_text_color.";
}
#footer .widget_pages li a, 
#footer .widget_nav_menu li a, 
#footer .widget_categories li a, 
#footer .widget_mycategoryorder li a, 
#footer .recent_entries li,
#footer .copyright{
	border-color:rgba(".$rgb_footer_text_color[0].",".$rgb_footer_text_color[1].",".$rgb_footer_text_color[2].",0.2);
}
#footer .widget_pages li a:after, 
#footer .widget_nav_menu li a:after, 
#footer .widget_categories li a:after, 
#footer .widget_mycategoryorder li a:after, 
#footer .recent_entries li:after{
	background-color:rgba(".$rgb_footer_text_color[0].",".$rgb_footer_text_color[1].",".$rgb_footer_text_color[2].",0.58);
}
#bottom_bar,
#bottom_bar a,
#bottom_bar a:visited{
	background-color:".$accent_color.";
	color:".$container_bg_color.";
}
#bottom_bar .menu_icon:before,
#bottom_bar .menu_icon:after,
#bottom_bar .menu_icon span{
	background-color:".$container_bg_color.";
}";

	// *************************************************
	// Noto Sans CJK JP font
	// *************************************************
	if ($base_font_family !== 'default' ) {
		$noto_sans_css = 
"@font-face{
	font-family:'NotoSansCJKjp';
	font-style:normal;
	font-weight:100;
	src:url('".DP_THEME_URI."/css/fonts/NotoSans/NotoSansCJKjp-Thin.eot');
	src:url('".DP_THEME_URI."/css/fonts/NotoSans/NotoSansCJKjp-Thin.eot?#iefix') format('embedded-opentype'),
	url('".DP_THEME_URI."/css/fonts/NotoSans/NotoSansCJKjp-Thin.woff') format('woff'),
	url('".DP_THEME_URI."/css/fonts/NotoSans/NotoSansCJKjp-Thin.ttf')  format('truetype');
}
@font-face{
	font-family:'NotoSansCJKjp';
	font-style:normal;
	font-weight:200;
	src:url('".DP_THEME_URI."/css/fonts/NotoSans/NotoSansCJKjp-Light.eot');
	src:url('".DP_THEME_URI."/css/fonts/NotoSans/NotoSansCJKjp-Light.eot?#iefix') format('embedded-opentype'),
	url('".DP_THEME_URI."/css/fonts/NotoSans/NotoSansCJKjp-Light.woff') format('woff'),
	url('".DP_THEME_URI."/css/fonts/NotoSans/NotoSansCJKjp-Light.ttf')  format('truetype');
}
@font-face{
	font-family:'NotoSansCJKjp';
	font-style:normal;
	font-weight:300;
	src:url('".DP_THEME_URI."/css/fonts/NotoSans/NotoSansCJKjp-DemiLight.eot');
	src:url('".DP_THEME_URI."/css/fonts/NotoSans/NotoSansCJKjp-DemiLight.eot?#iefix') format('embedded-opentype'),
	url('".DP_THEME_URI."/css/fonts/NotoSans/NotoSansCJKjp-DemiLight.woff') format('woff'),
	url('".DP_THEME_URI."/css/fonts/NotoSans/NotoSansCJKjp-DemiLight.ttf')  format('truetype');
}
@font-face{
	font-family:'NotoSansCJKjp';
	font-style:normal;
	font-weight:400;
	src:url('".DP_THEME_URI."/css/fonts/NotoSans/NotoSansCJKjp-Regular.eot');
	src:url('".DP_THEME_URI."/css/fonts/NotoSans/NotoSansCJKjp-Regular.eot?#iefix') format('embedded-opentype'),
	url('".DP_THEME_URI."/css/fonts/NotoSans/NotoSansCJKjp-Regular.woff') format('woff'),
	url('".DP_THEME_URI."/css/fonts/NotoSans/NotoSansCJKjp-Regular.ttf')  format('truetype');
}
@font-face{
	font-family:'NotoSansCJKjp';
	font-style:normal;
	font-weight:500;
	src:url('".DP_THEME_URI."/css/fonts/NotoSans/NotoSansCJKjp-Medium.eot');
	src:url('".DP_THEME_URI."/css/fonts/NotoSans/NotoSansCJKjp-Medium.eot?#iefix') format('embedded-opentype'),
	url('".DP_THEME_URI."/css/fonts/NotoSans/NotoSansCJKjp-Medium.woff') format('woff'),
	url('".DP_THEME_URI."/css/fonts/NotoSans/NotoSansCJKjp-Medium.ttf')  format('truetype');
}
@font-face{
	font-family:'NotoSansCJKjp';
	font-style:normal;
	font-weight:700;
	src:url('".DP_THEME_URI."/css/fonts/NotoSans/NotoSansCJKjp-Bold.eot');
	src:url('".DP_THEME_URI."/css/fonts/NotoSans/NotoSansCJKjp-Bold.eot?#iefix') format('embedded-opentype'),
	url('".DP_THEME_URI."/css/fonts/NotoSans/NotoSansCJKjp-Bold.woff') format('woff'),
	url('".DP_THEME_URI."/css/fonts/NotoSans/NotoSansCJKjp-Bold.ttf')  format('truetype');
}
@font-face{
	font-family:'NotoSansCJKjp';
	font-style:normal;
	font-weight:900;
	src:url('".DP_THEME_URI."/css/fonts/NotoSans/NotoSansCJKjp-Black.eot');
	src:url('".DP_THEME_URI."/css/fonts/NotoSans/NotoSansCJKjp-Black.eot?#iefix') format('embedded-opentype'),
	url('".DP_THEME_URI."/css/fonts/NotoSans/NotoSansCJKjp-Black.woff') format('woff'),
	url('".DP_THEME_URI."/css/fonts/NotoSans/NotoSansCJKjp-Black.ttf')  format('truetype');
}";
		switch ($base_font_family){
			case 'notosans-all':
				$noto_sans_css .= 
"body{
	font-family:NotoSansCJKjp,sans-serif;
	font-weight:".$base_font_weight.";
}";
				break;
			case 'notosans-title':
				$noto_sans_css .= 
".header_content .h_group .hd_title,
#banner_title,
.ct-hd .hd-title,
.entry h1,.entry h2,.entry h3,.entry h4,.entry h5,.entry h6,
.loop-sec-header h1,
.inside-title,
#reply-title,
.wd-title{
	font-family:NotoSansCJKjp,sans-serif;
	font-weight:".$base_font_weight."!important;
}";
				break;
			case 'notosans-only':
				$noto_sans_css .= 
".ff-noto1{
	font-family:NotoSansCJKjp;
	font-weight:100!important;
}
.ff-noto2{
	font-family:NotoSansCJKjp;
	font-weight:200!important;
}
.ff-noto3{
	font-family:NotoSansCJKjp;
	font-weight:300!important;
}
.ff-noto4{
	font-family:NotoSansCJKjp;
	font-weight:400!important;
}
.ff-noto5{
	font-family:NotoSansCJKjp;
	font-weight:500!important;
}
.ff-noto6{
	font-family:NotoSansCJKjp;
	font-weight:700!important;
}
.ff-noto7{
	font-family:NotoSansCJKjp;
	font-weight:900!important;
}";
				break;
		}
	}

	$result = <<<_EOD_
@charset "utf-8";
$body_css
$noto_sans_css
$base_font_size_css
$base_link_color_css
$base_link_hover_color_css
$link_filled_color_css
$header_slideshow_css
$global_menu_css
$container_css
$footer_widget_css
$entry_link_css
$meta_area_css
$cat_colos_css
$bx_slider_css
$form_css
$search_form_css
$ranking_css
$common_bg_color_css
$border_color_css
$bordered_obj_css
$navigation_link_color_css
$list_hover_css
$tooltip_css
$quote_css
$comment_box_css
$sidebar_css
$wow_css
$footer_css
$btn_label_css
$original_css
_EOD_;

	return $result;
}

/****************************
 * Gradient SVG for IE9
 ***************************/
function gradientSVGForIE9($color1, $color2) {
	if ($color1 == "") return;
	if ($color2 == "") return;

	$xml = <<<_EOD_
<?xml version="1.0" ?>
<svg xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none" version="1.0" width="100%" height="100%" xmlns:xlink="http://www.w3.org/1999/xlink">
  <defs>
    <linearGradient id="myLinearGradient1" x1="0%" y1="0%" x2="0%" y2="100%" spreadMethod="pad">
      <stop offset="0%"   stop-color="$color1" stop-opacity="1"/>
      <stop offset="100%" stop-color="$color2" stop-opacity="1"/>
    </linearGradient>
  </defs>
  <rect width="100%" height="100%" style="fill:url(#myLinearGradient1);" />
</svg>
_EOD_;

	return $xml;
}

/*******************************************************
* Write File
*******************************************************/
/** ===================================================
* Write css and svg to the file.
*
* @param	string	$file_path
* @param	string	$string
* @return	true or false
*/
function dp_export_file($file_path, $str) {
	if ( !file_exists($file_path) ) {
		touch( $file_path );
		chmod( $file_path, 0666 );
	}

	if ( WP_Filesystem() && is_writable($file_path) ) {
		if (!defined('FS_CHMOD_FILE')) {
			define('FS_CHMOD_FILE', (0666 & ~ umask()));
		}
		global $wp_filesystem;
		if ( !$wp_filesystem->put_contents($file_path, $str, FS_CHMOD_FILE)) {
			$err_msg = $file_path . ": " . __('The file may be in use by other program. Please identify the conflict process.','DigiPress');
			$e = new WP_Error();
			$e->add( 'error', $err_msg );
			set_transient( 'dp-admin-option-errors', $e->get_error_messages(), 10 );
			add_action( 'admin_notices', array('digipress_options', 'dp_show_admin_error_message') );
			return false;
  		}
	} else {
		//if only readinig
		$err_msg = $file_path . ": " . __('The file is not rewritable. Please change the permission to 666 or 606.','DigiPress');
		$e = new WP_Error();
		$e->add( 'error', $err_msg );
		set_transient( 'dp-admin-option-errors', $e->get_error_messages(), 10 );
		add_action( 'admin_notices', array('digipress_options', 'dp_show_admin_error_message') );
		return false;
	}
	return true;
}
function dp_export_gzip($file_path, $str) {
	if ( !file_exists($file_path) ) {
		touch( $file_path );
		chmod( $file_path, 0666 );
	}

	//Rewrite CSS for custom design
	if (is_writable( $file_path )){
		//Open
		if(!$fp = gzopen($file_path.'.gz',  'w9') ){
			$err_msg = $file_path . ".gz: " . __('The file can not be opened. Please identify the conflict process.','DigiPress');
			$e = new WP_Error();
			$e->add( 'error', $err_msg );
			set_transient( 'dp-admin-option-errors',
				$e->get_error_messages(), 10 );
			add_action( 'admin_notices', array('digipress_options', 'dp_show_admin_error_message') );
    		return false;
  		}
  		//Write 
  		if(!gzwrite( $fp, $str )){
			$err_msg = $file_path . ".gz: " . __('The file may be in use by other program. Please identify the conflict process.','DigiPress');
			$e = new WP_Error();
			$e->add( 'error', $err_msg );
			set_transient( 'dp-admin-option-errors',
				$e->get_error_messages(), 10 );
			add_action( 'admin_notices', array('digipress_options', 'dp_show_admin_error_message') );
			return false;
		}
		//Close file
		gzclose($fp);
	} else {
		//if only readinig
		$err_msg = $file_path . ".gz: " . __('The file is not rewritable. Please change the permission to 666 or 606.','DigiPress');
		$e = new WP_Error();
		$e->add( 'error', $err_msg );
		set_transient( 'dp-admin-option-errors',
			$e->get_error_messages(), 10 );
		add_action( 'admin_notices', array('digipress_options', 'dp_show_admin_error_message') );
		return false;
	}
	return true;
}


/****************************
 * HEX to RGB
 ***************************/
function hexToRgb($color) {
	$color = preg_replace("/^#/", '', $color);
	if (mb_strlen($color) == 3) $color .= $color;
	$rgb = array();
	for($i = 0; $i < 6; $i+=2) {
		$hex = substr($color, $i, 2);
		$rgb[] = hexdec($hex);
	}
	return $rgb;
}
/****************************************************************
* Make darken or lighten color from hex to rgb
****************************************************************/
function darkenColor($color, $range = 30) {
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
function lightenColor($color, $range = 30) {
	if (!is_numeric($range)) $range = 30;
	if ($range > 255 || $range < 0) $range = 30;
	$color = preg_replace("/^#/", '', $color);
	if (mb_strlen($color) == 3) $color .= $color;
	$rgb = array();
	for($i = 0; $i < 6; $i+=2) {
		$hex = substr($color, $i, 2);
		$hex = hexdec($hex);
		$hex = $hex + $range <= 255 ? $hex + $range : $hex;
		$rgb[] = $hex;
	}
	return $rgb;
}