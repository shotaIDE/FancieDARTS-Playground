<?php
/**
 * Custom CSS for AMP template
 * @return [type] [description]
 */
function dp_custom_css_amp(){
	global $options, $options_visual;

	extract($options);
	extract($options_visual);

	// RGB
	$rgb_container_bg_color = dp_hex_to_rgb($container_bg_color);
	$rgb_base_font_color = dp_hex_to_rgb($base_font_color);
	$rgb_accent_color = dp_hex_to_rgb($accent_color);
	$rgb_header_menu_bgcolor = dp_hex_to_rgb($header_menu_bgcolor);
	$rgb_header_menu_link_color = dp_hex_to_rgb($header_menu_link_color);
	$rgb_footer_text_color = dp_hex_to_rgb($footer_text_color);
	$rgb_header_banner_text_shadow_color = dp_hex_to_rgb($header_banner_text_shadow_color);
	$darken_accent_color = dp_darken_color($accent_color);

	$css = '';

	// Font size
	if (!$base_font_size_mb || ($base_font_size_mb == '')) {
		if ( !$base_font_size_mb_unit || $base_font_size_mb_unit == '' ) {
			$css .= 
".entry,
.dp_text_widget,
.textwidget{
	font-size:14px;
}";
		} else {
			$css .= 
".entry,
.dp_text_widget,
.textwidget{
	font-size:".$original_font_size_em."em".$options_visual['base_font_size_mb_unit'].";
}";
		}
	} else {
		if ( !$base_font_size_mb_unit || $base_font_size_mb_unit == '' ) {
			$css .= 
".entry,
.dp_text_widget,
.textwidget{
	font-size:".$base_font_size_mb."px;
}";
		} else {
			$css .= 
".entry,
.dp_text_widget,
.textwidget{
	font-size:".$base_font_size_mb.$base_font_size_mb_unit.";
}";
		}
	}

	// body
	$css .=
".main-wrap{
	color:".$base_font_color.";
	background-color:".$site_bg_color.";
}
.main-wrap a,
.navigation a{
	color:".$base_font_color.";
}
.entry a:not(.btn),
.dp_text_widget a:not(.btn),
.textwidget a:not(.btn){
	color:".$base_link_color.";
}
.main-wrap .ct-hd,
.main-wrap .ct-hd a,
.header-banner,
.header-banner a{
	color:".$header_banner_font_color.";
}
.hd-bn-h3::before,
.hd-bn-h3::after{
	background-color:".$header_banner_font_color.";
}";
	if ($header_banner_text_shadow_enable){
		$css .=
".header-banner{
	text-shadow:0 0 10px rgba(".$rgb_header_banner_text_shadow_color[0].",".$rgb_header_banner_text_shadow_color[1].",".$rgb_header_banner_text_shadow_color[2].",.72);
}";
	}

	if ( (isset($dp_page_header_padding_top_mb) && !empty($dp_page_header_padding_top_mb)) && (isset($dp_page_header_padding_bottom_mb) && !empty($dp_page_header_padding_bottom_mb)) ) {
		$css .=
".ct-hd{
	padding-top:".$dp_page_header_padding_top_mb."vh;
	padding-bottom:".$dp_page_header_padding_bottom_mb."vh;
}";
	}

	$css .=
"address{
	border-color:".$accent_color.";
}
.btn,
.main-wrap a.btn,
.content blockquote::before,
.content blockquote::after{
	color:".$accent_color.";
}
.label,
.content pre,
ul li::before,
.ct-hd,
.label_ft,
.loop-date{
	color:".$container_bg_color.";
	background-color:".$accent_color.";
}
.rank_label.thumb::before{
	color:".$container_bg_color.";
	border-color:".$accent_color." ".$accent_color." transparent ".$accent_color.";
}
.label::after{
	background-color:".$container_bg_color.";
	border-color:transparent transparent transparent rgba(".$darken_accent_color[0].",".$darken_accent_color[1].",".$darken_accent_color[2].",1);
}
.author_info .avatar{
	border-color:rgba(".$rgb_accent_color[0].",".$rgb_accent_color[1].",".$rgb_accent_color[2].",.18);
}
.footer .label::after{
	background-color:".$footer_bgcolor.";
}
.rank_label.thumb{
	color:".$container_bg_color.";
}
.navigation .current{
	border-color:".$base_font_color.";
}
hr,code{
	border-color:rgba(".$rgb_base_font_color[0].",".$rgb_base_font_color[1].",".$rgb_base_font_color[2].",.4);
}
.entry h1,.entry h2,.entry h3,.entry h4,.entry h5,.entry h6,
.dp_text_widget h1,.dp_text_widget h2,.dp_text_widget h3,.dp_text_widget h4,.dp_text_widget h5,.dp_text_widget h6,
.textwidget h1,.textwidget h2,.textwidget h3,.textwidget h4,.textwidget h5,.textwidget h6,
.widget_nav_menu li a,
.widget_pages li a,
.widget_categories li a,
.recent_entries li,
.dp_related_posts li,
.single-nav li.right,
.content table th,
.content table td,
.content dl,
.content dt,
.content dd,
.comment,
.wp-caption,
.content blockquote,
#toc_container,
p.toc_title{
	border-color:rgba(".$rgb_base_font_color[0].",".$rgb_base_font_color[1].",".$rgb_base_font_color[2].",.16);
}
.site-bar{
	color:".$header_menu_link_color.";
	background-color:".$header_menu_bgcolor.";
	border-color:".$accent_color.";
}
.site-bar a{
	color:".$header_menu_link_color.";
}
.site-bar.bottom-bar{
	color:".$container_bg_color.";
	background-color:".$accent_color.";
}
.site-bar.bottom-bar a{
	color:".$container_bg_color.";
}
.g_menu_wrapper{
	background-color:".$header_menu_bgcolor.";
	color:".$header_menu_link_color.";
}
.g_menu_wrapper a{
	color:".$header_menu_link_color.";
}
.g_menu_wrapper .menu-link,
.nonamplink{
	border-color:rgba(".$rgb_header_menu_link_color[0].",".$rgb_header_menu_link_color[1].",".$rgb_header_menu_link_color[2].",.22);
}
.nonamplink a{
	background-color:rgba(".$rgb_header_menu_link_color[0].",".$rgb_header_menu_link_color[1].",".$rgb_header_menu_link_color[2].",.08);
}
.loop-article,
.single-article,
.recent_entries,
.dp_related_posts ul,
.more-entry-link a,
.tagcloud a,
.dp_feed_widget a,
.commentlist,
.navigation a,
.inside-title,
.widget_categories>ul{
	box-shadow:0 0 4px rgba(".$rgb_base_font_color[0].",".$rgb_base_font_color[1].",".$rgb_base_font_color[2].",.18);
	background-color:".$container_bg_color.";
}
.inside-title::before{
	border-top-color:rgba(".$rgb_base_font_color[0].",".$rgb_base_font_color[1].",".$rgb_base_font_color[2].",.16);
}
.inside-title::after{
	border-top-color:".$container_bg_color.";
}
.loop-date::before{
	background-color:rgba(".$rgb_container_bg_color[0].",".$rgb_container_bg_color[1].",".$rgb_container_bg_color[2].",.2);
}
.loop-share-num i{
	color:".$base_font_color.";
	border-color:rgba(".$rgb_base_font_color[0].",".$rgb_base_font_color[1].",".$rgb_base_font_color[2].",.09);
	background-color:rgba(".$rgb_base_font_color[0].",".$rgb_base_font_color[1].",".$rgb_base_font_color[2].",.05);
}
.entry h1::after,.entry h2::after,.entry h3::after,.entry h4::after,.entry h5::after,.entry h6::after{
	background-color:rgba(".$rgb_base_font_color[0].",".$rgb_base_font_color[1].",".$rgb_base_font_color[2].",.08);
}
code,
.content blockquote,
.content dt,
.content table th,
.entry .wp-caption,
.comment .children,
#wp-calendar caption,
#wp-calendar th,
#wp-calendar td,
#toc_container{
	background-color:rgba(".$rgb_base_font_color[0].",".$rgb_base_font_color[1].",".$rgb_base_font_color[2].",.04);
}
#wp-calendar tbody td#today,
#wp-calendar tbody td a::before{
	background-color:rgba(".$rgb_base_font_color[0].",".$rgb_base_font_color[1].",".$rgb_base_font_color[2].",.14);
}
.comment .children::before{
	border-color:transparent transparent rgba(".$rgb_base_font_color[0].",".$rgb_base_font_color[1].",".$rgb_base_font_color[2].",.05) transparent;
}
.cat-item .count{
	color:".$container_bg_color.";
	background-color:rgba(".$rgb_base_font_color[0].",".$rgb_base_font_color[1].",".$rgb_base_font_color[2].",.5);
}
.footer{
	background-color:".$footer_bgcolor.";
	color:".$footer_text_color.";
	box-shadow:0 0 30px rgba(".$rgb_base_font_color[0].",".$rgb_base_font_color[1].",".$rgb_base_font_color[2].",.2);
}
.footer a{
	color:".$footer_link_color.";
}
.footer .cat-item .count{
	color:".$footer_bgcolor.";
	background-color:rgba(".$rgb_footer_text_color[0].",".$rgb_footer_text_color[1].",".$rgb_footer_text_color[2].",.5);
}
.footer .recent_entries,
.footer .dp_related_posts ul,
.footer .more-entry-link a,
.footer .tagcloud a,
.footer .dp_feed_widget a,
.footer .inside-title,
.footer .widget_categories>ul{
	box-shadow:0 0 4px rgba(".$rgb_footer_text_color[0].",".$rgb_footer_text_color[1].",".$rgb_footer_text_color[2].",.18);
	background-color:".$footer_bgcolor.";
}
.footer .inside-title::before{
	border-top-color:rgba(".$rgb_footer_text_color[0].",".$rgb_footer_text_color[1].",".$rgb_footer_text_color[2].",.16);
}
.footer .inside-title::after{
	border-top-color:".$footer_bgcolor.";
}
.footer .more-entry-link a,
.footer .loop-share-num i,
.footer #wp-calendar tbody td#today,
.footer #wp-calendar tbody td a::before{
	color:".$footer_text_color.";
	background-color:rgba(".$rgb_footer_text_color[0].",".$rgb_footer_text_color[1].",".$rgb_footer_text_color[2].",.1);
}
.footer .widget_nav_menu li a,
.footer .widget_pages li a,
.footer .widget_categories li a,
.footer .recent_entries li,
.footer .dp_related_posts li,
.footer .menu-link,
#ft-widget-content{
	border-color:rgba(".$rgb_footer_text_color[0].",".$rgb_footer_text_color[1].",".$rgb_footer_text_color[2].",.16);
}";

	// Content header color
	if (is_category()) {
		global $cat;
		foreach ($cat_ids as $key => $cat_id) {
			if ($cat_id == $cat && !empty($cat_colors[$key])) {
				$css .=
".ct-hd.cat-color".$cat_id."{
	background-color:".$cat_colors[$key].";
}";
			}
		}
	}

	// User CSS
	$css .= $original_css_amp;
	$css = str_replace(array("\r\n","\r","\n","\t"), '', $css);
	echo $css;
}