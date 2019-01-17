<?php
/****************************************************************
* Font 
****************************************************************/
function dp_sc_pi_font($atts, $content = null) {
	if (is_admin()) return;
	if (!$content) return;
	
	extract(shortcode_atts(array(
		'size'  => 14,
		'bold' => false,
		'color' => '',
		'bgcolor'=> '',
		'italic'=> false,
		'class' => ''
	), $atts));

	$code 	= $content;
	$style 	= '';

	$bold 	= !empty($bold) ? ' b' : '';
	$italic = !empty($italic) ? ' i' : '';

	if (!$bold && !$italic && !$class) {
		if ($bgcolor) {
			$class 	= ' class="pd5px"';
		} else {
			$class 	= '';
		}
	} else {
		if ($bgcolor) {
			$class 	= ' class="'.$class.$bold.$italic.' pd5px"';
		} else {
			$class 	= ' class="'.$class.$bold.$italic.'"';
		}
	}

	$color 		= !empty($color) ? 'color:'.$color.';' : '';
	$bgcolor 	= !empty($bgcolor) ? 'background-color:'.$bgcolor.';' : '';
	$size = $size ? $size : 14;
	$size = mb_convert_kana($size);
	if (is_numeric($size)) {
		$size = 'font-size:'.$size.'px;';
	} else {
		$size = 'font-size:'.$size.';';
	}
	$style = ' style="'.$color.$bgcolor.$size.'"';

	$code = <<<_EOD_
<span$style$class>$content</span>
_EOD_;

	return $code;
}


/****************************************************************
* Button
****************************************************************/
function dp_sc_pi_button($atts) {
	if (is_admin()) return;

	extract(shortcode_atts(array(
		'url'  => '',
		'title' => '',
		'color' => '',
		'icon' => '',
		'rel'  => '',
		'newwindow'=> false,
		'class' => '',
		'size' => ''	// big, small
	), $atts));

	if (empty($url)) return;

	// Params
	$rgb = '';
	$hex = '';
	$box_shadow = '';
	$hover = '';
	$btn_id = '';
	$btn_id2 = '';
	$btn_css = '';

	$button_style = defined('DP_BUTTON_STYLE') ? DP_BUTTON_STYLE : 'rich';

	if ($button_style === 'flat') {
		// For FLAT BUTTONS
		$rgb 		= dp_sc_pi_darkenColor($color, 30);
		$hex 		= dp_sc_pi_rgbToHex($rgb);
		$box_shadow = 'box-shadow:0 5px '.$hex.';-moz-box-shadow:0 5px '.$hex.';';
		$hover		= ' onmouseover="this.style.boxShadow=\'0 3px '.$hex.'\'" onmouseout="this.style.boxShadow=\'0 5px '.$hex.'\'"';
	}

	if ($button_style === 'flat5' || $button_style === 'flat6') {
		$btn_id = 'btn'.dp_sc_rand(5);
		$btn_id2 = ' id="'.$btn_id.'"';
		if ($button_style === 'flat5') {
			$btn_css = '<style>#'.$btn_id.'{border-color:'.$color.';}#'.$btn_id.':after{background-color:'.$color.';}#'.$btn_id.':hover{color:'.$color.'!important;}</style>';
		} else {
			$btn_css = '<style>#'.$btn_id.'{border-color:'.$color.';}#'.$btn_id.':after{background-color:'.$color.';}#'.$btn_id.'{color:'.$color.'!important;}#'.$btn_id.':hover{color:#fff!important;}</style>';
		}
		
		$color = '';
	}

	$newwindow 	= !empty($newwindow) ? ' target="_blank"' : '' ;
	$class 		= !empty($class) ? ' '.$class : '' ;
	$icon 		= !empty($icon) ? ' '.$icon : '' ;
	$color 		= !empty($color) ? ' style="background-color:'.$color.';'.$box_shadow.'"' : '';
	$rel 		= !empty($rel) ? ' rel="'.$rel.'"' : '' ;

	if ($size === 'big') {
		$size = ' ft20px';
	} elseif ($size === 'small') {
		$size = ' ft10px';
	} else {
		$size = ' '.$size;
	}

	$code = <<<_EOD_
<a href="$url"$btn_id2 class="btn$class$icon$size"$color$newwindow$rel$hover>$title</a>$btn_css
_EOD_;

	return $code;
}



/****************************************************************
* Labels
****************************************************************/
function dp_sc_pi_label($atts) {
	if (is_admin()) return;

	extract(shortcode_atts(array(
		'color' => '',
		'title' => '',
		'text' => '',
		'icon' => '',
		'class' => '',
		'size' => ''
	), $atts));

	$title 		= !empty($title) ? '<span>'.$title.'</span>' : '' ;
	$text 		= !empty($text) ? '<span>'.$text.'</span>' : '' ;
	$icon 		= !empty($icon) ? ' '.$icon : '';
	$class 		= !empty($class) ? ' '.$class : '' ;
	$color 		= !empty($color) ? ' style="background-color:'.$color.';"' : '' ;

	if ($size === 'big') {
		$size = ' ft18px';
	} elseif ($size === 'small') {
		$size = ' ft10px';
	}

	$code = <<<_EOD_
<span class="label$icon$class$size"$color>$title</span>$text
_EOD_;

	return $code;
}



/****************************************************************
* Table display
****************************************************************/
function dp_sc_pi_table($atts, $content = null) {
	if (is_admin()) return;
	if (!$content) return;

	if (!class_exists('DP_SC_PLUGIN')) return;
	if (!function_exists('dp_sc_plugin_status')) return;
	// Status check
	$status = dp_sc_plugin_status();
	if (!$status) return;

	extract(shortcode_atts(array(
		'id' => '',
		'class' => '',
		'style' => '',
		'width' => '100%',
		'highlight' => false,
		'hoverrowbgcolor' => '',
		'hoverrowfontcolor' => '',
		'hovercellbgcolor' => '',
		'hovercellfontcolor' => '',
		'sort'  => false
	), $atts));

	$content 	= do_shortcode($content);

	$table_id 	= 'tbl-'.dp_sc_rand(4);

	$inline_css = '';
	$sort_css 	= '';


	$style 		.= !empty($width) ? 'width:'.$width.';' : '';
	$style 		= ' style="'.$style.'"';
	$highlight 	= !empty($highlight) ? ' highlight' : '';

	// Highlight color
	if ( !empty($hoverrowbgcolor) ) {
		if ( !empty($hoverrowfontcolor)) {
			$inline_css = 'table.'.$table_id.'.highlight tbody tr:hover{color:'.$hoverrowfontcolor.';background-color:'.$hoverrowbgcolor.';}';
		} else {
			$inline_css = 'table'.$table_id.'.highlight tbody tr:hover{background-color:'.$hoverrowbgcolor.';}';
		}
	}
	if ( !empty($hovercellbgcolor) ) {
$id = !empty($id) ? 'id="'.$id.'" ' : '';		if ( !empty($hovercellfontcolor)) {
			$inline_css .= 'table.'.$table_id.'.highlight tbody td:hover{color:'.$hovercellfontcolor.';background-color:'.$hovercellbgcolor.';}';
		} else {
			$inline_css .= 'table.'.$table_id.'.highlight tbody td:hover{background-color:'.$hovercellbgcolor.';}';
		}
	}
	if ( !empty($inline_css) ) {
		$inline_css = '<style type="text/css">'.$inline_css.'</style>';
	}
	if ((bool)$sort) {
		$sort_css = ' sortable';
	}
	// CSS
	$table_id = ' '.$table_id;
	$class = !empty($class) ? ' class="dp_sc_table '.$class.$highlight.$sort_css.$table_id.'"' : ' class="dp_sc_table'.$highlight.$sort_css.$table_id.'"';
	$code = <<<_EOD_
$inline_css
<table $id$class$style>$content</table>
_EOD_;

	$code = str_replace(array("\r\n","\r","\n","\t"), '', $code);

	return $code;
}
	function dp_sc_pi_table_head($atts, $content = null) {
		if (!$content) return;

		extract(shortcode_atts(array(
			'title' => '',
			'icon' => '',
			'caption'=> '',
			'class' => '',
			'align' => 'center',
			'style' => '',
			'width' => '',
			'bgcolor'=> ''
		), $atts));

		if (!$title) return;

		$content = do_shortcode($content);

		if ($align === 'center') {
			$align = ' al-c'; 
		} else if ($align === 'right') {
			$align = ' al-r';
		} else {
			$align = ' al-l';
		}

		if ($class || $align) {
			$class 	= $class.$align;
		} else {
			$class = '';
		}
		$style 	.= !empty($bgcolor) ? 'background-color:'.$bgcolor.';' : '';
		$style 	.= !empty($width) ? 'width:'.$width.';' : '';
		$style 	= !empty($style) ? ' style="'.$style.'"' : '';

		$caption = $caption ? '<caption><span class="dp_sc_table_cap icon-th">'.$caption.'</span><span class="dp_sc_table_cap_back icon-cross-circled">'.__('Close', DP_SC_PLUGIN_TEXT_DOMAIN).'</span></caption>' : '<caption><span class="dp_sc_table_cap icon-th">'.__('Tap this to show the table', DP_SC_PLUGIN_TEXT_DOMAIN).'</span><span class="dp_sc_table_cap_back icon-cross-circled">Close</span></caption>';

		// Split return
		$arrTitle 	= explode( ',', $title );
		$arrIcon 	= explode( ',', $icon );
		$codeTitle 	= '';

		foreach ($arrTitle as $key => $val) {
			if (!empty($arrIcon[$key])) {
				$iconclass = $arrIcon[$key] ? ' '.$arrIcon[$key] : '';
			} else {
				$iconclass = '';
			}
			$codeTitle .= '<th class="'.$class.$iconclass.'"'.$style.'>'.$val.'</th>';
		}
		$codeTitle = '<thead>'.$codeTitle.'</thead>';
		$code = $caption.$codeTitle.'<tbody class="dp_sc_table_body">'.$content.'</tbody>';
		return $code;
	}

	function dp_sc_pi_table_row($atts, $content = null) {

		if (!$content) return;

		extract(shortcode_atts(array(
			'title' => '',
			'icon' => '',
			'class' => '',
			'align' => 'center',
			'style' => '',
			'width' => '',
			'bgcolor'=> ''
		), $atts));

		$content = do_shortcode($content);

		$icon = !empty($icon) ? ' '.$icon : '';

		if ($align === 'center') {
			$align = ' al-c'; 
		} else if ($align === 'right') {
			$align = ' al-r';
		} else {
			$align = ' al-l';
		}

		if ($class || $align || $icon) {
			$class 	= ' class="'.$class.$align.$icon.'"';
		} else {
			$class = '';
		}
		$style 	.= !empty($bgcolor) ? 'background-color:'.$bgcolor.';' : '';
		$style 	.= !empty($width) ? 'width:'.$width.';' : '';
		$style 	= !empty($style) ? ' style="'.$style.'"' : '';

		$title = $title ? '<th'.$class.$style.'>'.$title.'</th>' : '';

		$code = <<<_EOD_
<tr>$title$content</tr>
_EOD_;
		return $code;
	}
	function dp_sc_pi_table_cell($atts, $content = null) {

		extract(shortcode_atts(array(
			'class' => '',
			'align' => 'center',
			'bgcolor' => '',
			'width' => '',
			'style' => ''
		), $atts));

		if ($align === 'center') {
			$align = ' al-c';
		} else if ($align === 'right') {
			$align = ' al-r';
		} else {
			$align = ' al-l';
		}

		if ($class || $align) {
			$class 	= ' class="'.$class.$align.'"';
		} else {
			$class = '';
		}
		$style 	.= !empty($bgcolor) ? 'background-color:'.$bgcolor.';' : '';
		$style 	.= !empty($width) ? 'width:'.$width.';' : '';
		$style 	= !empty($style) ? ' style="'.$style.'"' : '';

		$code = <<<_EOD_
<td$class$style>$content</td>
_EOD_;
		return $code;
	}



/****************************************************************
* Accordions
****************************************************************/
function dp_sc_pi_accordions($atts, $content = null) {
	if (is_admin()) return;
	if (!$content) return;

	if (!class_exists('DP_SC_PLUGIN')) return;
	if (!function_exists('dp_sc_plugin_status')) return;
	// Status check
	$status = dp_sc_plugin_status();
	if (!$status) return;

	extract(shortcode_atts(array(
		'class' => '',
		'style' => '',
		'type' => 'accordion'
	), $atts));

	$content = do_shortcode($content);

	$type 	= $type === 'accordion' ? 'accordion dp_accordion' : 'accordion dp_toggle'; 
	$class 	= !empty($class) ? ' '. $class : '';
	$style 	= !empty($style) ? ' style="'. $style . '"' : '';

	$code = <<<_EOD_
<dl class="$type$class"$style>$content</dl>
_EOD_;

	return $code;
}
	function dp_sc_pi_accordion($atts, $content = null) {
		if (!$content) return;

		extract(shortcode_atts(array(
			'title' => '',
			'class' => '',
			'style' => ''
		), $atts));

		if (!$title) return;
		$content = do_shortcode($content);

		$class = !empty($class) ? ' class="ft15px ' . $class . '"' : ' class="ft15px"';
		$style = !empty($style) ? ' style="' . $style . '"' : '';

		$code = <<<_EOD_
<dt$class$style>$title</dt><dd>$content</dd>
_EOD_;

		return $code;
	}


/****************************************************************
* Toggles
****************************************************************/
function dp_sc_pi_toggles($atts, $content = null) {
	if (is_admin()) return;
	if (!$content) return;

	if (!class_exists('DP_SC_PLUGIN')) return;
	if (!function_exists('dp_sc_plugin_status')) return;
	// Status check
	$status = dp_sc_plugin_status();
	if (!$status) return;

	extract(shortcode_atts(array(
		'class' => '',
		'style' => ''
	), $atts));

	$content = do_shortcode($content);

	$type 	= 'accordion dp_toggle'; 
	$class 	= !empty($class) ? ' '. $class : '';
	$style 	= !empty($style) ? ' style="'. $style . '"' : '';

	$code = <<<_EOD_
<dl class="$type$class"$style>$content</dl>
_EOD_;

	return $code;
}
	function dp_sc_pi_toggle($atts, $content = null) {
		if (!$content) return;

		extract(shortcode_atts(array(
			'title' => '',
			'class' => '',
			'style' => ''
		), $atts));

		if (!$title) return;

		$content = do_shortcode($content);

		$class = !empty($class) ? ' class="ft15px ' . $class . '"' : ' class="ft15px"';
		$style = !empty($style) ? ' style="' . $style . '"' : '';

		$code = <<<_EOD_
<dt$class$style>$title</dt><dd>$content</dd>
_EOD_;
		return $code;
	}


/****************************************************************
* Tabs
****************************************************************/
function dp_sc_pi_tabs($atts, $content = null) {
	if (is_admin()) return;
	if (!$content) return;

	if (!class_exists('DP_SC_PLUGIN')) return;
	if (!function_exists('dp_sc_plugin_status')) return;
	// Status check
	$status = dp_sc_plugin_status();
	if (!$status) return;

	extract(shortcode_atts(array(
		'class' => ''
	), $atts));

	$delim 		= '[//+++//]';

	$class 		= !empty($class) ? ' class="'. $class . '"' : '';
	$content 	= do_shortcode($content);

	// Split return
	$arrContent = explode( $delim, $content );
	$arrContent = array_filter($arrContent, function($item){return !preg_match("/^(\r|\n)+$/",$item);});
	// $arrContent = array_values(array_filter($arrContent));
	// Separate every 4 elements.
	$arrContent = array_chunk($arrContent, 4);

	$codeTitle 		= '';
	$codeContent	= '';

	foreach ($arrContent as $key => $val) {
		$title 	= str_replace(array("\r\n","\r","\n"), '', $val[0]);
		$class 	= $val[1] ? $val[1] : '';
		$class 	= $val[2] ? $class . ' ' . $val[2] : $class;
		$class 	= $class ? ' class="' . $class . '"' : '';
		$codeTitle 		.= '<li id="tab'.$key.'"><span'.$class.'>' . $title . '</span></li>';
		$codeContent 	.= '<div id="tab_div'.$key.'" class="tab_content">' . $val[3] . '</div>';
	}

	$code = '<div class="dp_sc_tab"><ul class="dp_sc_tab_ul clearfix">'.$codeTitle.'</ul><div class="tab_contents">'.$codeContent.'</div></div>';

	return $code;
}
	function dp_sc_pi_tab($atts, $content = null) {
		extract(shortcode_atts(array(
			'title' => __('No Title', 'DigiPress'),
			'icon' => '',
			'class' => ''
		), $atts));

		$delim 		= '[//+++//]';
		$title 		= !empty($title) ? $title : __('No Title', 'DigiPress');
		$content = do_shortcode($content);

		$return = $title .$delim. $class .$delim. $icon .$delim. $content .$delim;
		return $return;
	}



/****************************************************************
* Image filter
****************************************************************/
function dp_sc_img_filter($atts) {
	if (is_admin()) return;

	if (!class_exists('DP_SC_PLUGIN')) return;
	if (!function_exists('dp_sc_plugin_status')) return;
	// Status check
	$status = dp_sc_plugin_status();
	if (!$status) return;
	
	extract(shortcode_atts(array(
		'url' 	 => '',
		'grayscale' => false,
		'grayscaleval'=> '100%',
		'saturate' => false,
		'saturateval'=> '0%',
		'sepia'	 => false,
		'sepiaval' => '100%',
		'brightness'=> false,
		'brightnessval'=> '80%',
		'contrast'  => false,
		'contrastval'=> '80%',
		'blur' 	 => false,
		'blurval' => '4px',
		'opacity' => false,
		'opacityval'=> '80%',
		'invert' => false,
		'invertval' => '100%',
		'hue'	 => false,
		'hueval' => '180deg',
		'class'	 => '',
		'width'	 => '',
		'height' => '',
		'alt'	 => ''
	), $atts));

	if (!$url) return;

	// Inline CS
	$inline_css = '';

	$blur_param = '';

	// For FireFox (SVG)
	$sepia_style 		= '';
	$contrast_style 	= '';
	$brightness_style 	= '';
	$grayscale_style 	= '';
	$saturate_style 	= '';
	$hue_style 			= '';
	$blur_style 		= '';
	$opacity_style 		= '';
	$invert_style 		= '';

	// IE 
	$filter_ie 			= '';
	$grayscale_ie 		= '';
	$blur_ie			= '';
	$opacity_ie 		= '';

	// Unique key
	$class_id = uniqid('filter');

	// Sepia
	if ($sepia) {
		$sepiaval 			= str_replace('%', '', $sepiaval);
		$sepiaval 			= is_numeric($sepiaval) ? $sepiaval.'%' : '100%' ;
		$sepia 				= ' sepia('.$sepiaval.')';
		$sepia_style 		= "<feColorMatrix type='matrix' values='.8 .8 .8 0 0 .6 .6 .6 0 0 .3 .3 .3 0 0 0 0 0 1 0'/>";
	} else {
		$sepia = '';
	}

	// Brightness
	if ($brightness) {
		$brightnessval 	= str_replace('%', '', $brightnessval);
		$brightnessval 	= is_numeric($brightnessval) ? ($brightnessval/100) : 0.2;
		$brightness 		= ' brightness('.$brightnessval.')';
		$brightness_style 	= "<feColorMatrix type='matrix' values='".$brightnessval." 0 0 0 0 0 ".$brightnessval." 0 0 0 0 0 ".$brightnessval." 0 0 0 0 0 1 0'/>";
	} else {
		$brightness = '';
	}

	// Contrast
	if ($contrast) {
		$contrastval_int 	= str_replace('%', '', $contrastval);
		$contrastval 		= is_numeric($contrastval_int) ? $contrastval_int.'%' : '80%' ;
		$contrastval_int 	= is_numeric($contrastval_int) ? $contrastval_int/100 : 0.8;
		$contrast 			= ' contrast('.$contrastval.')';
		$contrast_style 	= "<feColorMatrix type='matrix' values='".$contrastval_int." 0 0 0 .06 0 ".$contrastval_int." 0 0 .06 0 0 ".$contrastval_int." 0 .06 0 0 0 1 0'/>";
	} else {
		$contrast = '';
	}

	// Opacity
	if ($opacity) {
		$opacityval_int 	= str_replace('%', '', $opacityval);
		$opacityval 		= is_numeric($opacityval_int) ? $opacityval_int.'%' : '80%' ;
		$opacity 			= ' opacity('.$opacityval.')';
		$opacity_ie 		= ' alpha(opacity='.$opacityval_int.')';
		$opacity_style 		= "<feGaussianBlur in='SourceGraphic' stdDeviation='0' />";
	} else {
		$opacity = '';
	}

	// invert
	if ($invert) {
		$invertval_int 		= str_replace('%', '', $invertval);
		$invertval 			= is_numeric($invertval_int) ? $invertval_int.'%' : '100%' ;
		$invertval_int 		= is_numeric($invertval_int) ? -1*($invertval_int/100) : -1;
		$invert 			= ' invert('.$invertval.')';
		$invert_style 		= "<feColorMatrix type='matrix' values='".$invertval_int." 0 0 0 1 0 ".$invertval_int." 0 0 1 0 0 ".$invertval_int." 0 1 0 0 0 1 0'/>";
	} else {
		$invert = '';
	}

	// Grayscale
	if ($grayscale) {
		$grayscaleval_int 	= str_replace('%', '', $grayscaleval);
		$grayscaleval 		= is_numeric($grayscaleval_int) ? $grayscaleval_int.'%' : '100%' ;
		$grayscaleval_int 	= is_numeric($grayscaleval_int) ? (100-$grayscaleval_int)/100 : 0;
		$grayscale 			= 'grayscale('.$grayscaleval.')';
		$grayscale_ie 		= ' gray';
		$grayscale_style 	= "<feColorMatrix type='saturate' values='".$grayscaleval_int."' result='A' />";
	} else {
		$grayscale = '';
	}

	// Blur
	if ($blur) {
		$blurval_int 		= str_replace('px', '', $blurval);
		$blurval 			= is_numeric($blurval_int) ? $blurval_int.'px' : '4px' ;
		$blur 				= ' blur('.$blurval.')';
		$blur_ie			= ' progid:DXImageTransform.Microsoft.Blur(PixelRadius='.$blurval_int.');';
		$blur_param 		= " x='-5%' y='-5%' width='110%' height='110%'";
		$blur_style 		= "<feGaussianBlur in='SourceGraphic' stdDeviation='".$blurval_int."' />";
	} else {
		$blur = '';
	}

	// Saturate
	if ($saturate) {
		$saturateval_int 	= str_replace('%', '', $saturateval);
		$saturateval 		= is_numeric($saturateval_int) ? $saturateval_int.'%' : '100%' ;
		$saturateval_int 	= is_numeric($saturateval_int) ? $saturateval_int/100 : 0.5;
		$saturate 			= ' saturate('.$saturateval.')';
		$saturate_style 	= "<feColorMatrix type='saturate' values='".$saturateval_int."' result='A' />";
	} else {
		$saturate = '';
	}

	// Hue rotate
	if ($hue) {
		$hueval_int 		= str_replace('deg', '', $hueval);
		$hueval 			= is_numeric($hueval_int) ? $hueval_int.'deg' : '180deg' ;
		$hue 				= ' hue-rotate('.$hueval.')';
		$hue_style			= "<feColorMatrix type='hueRotate' values='".$hueval_int."' result='A' />";
	} else {
		$hue = '';
	}

	// Filter for IE
	if ($grayscale_ie || $blur_ie || $opacity_ie) {
		$filter_ie = 'filter:'.$grayscale_ie.$blur_ie.$opacity_ie.';';
	}

	//Alt
	$alt = $alt ? ' alt="'.$alt.'"' : '';
	// Class
	$class = $class ? $class_id.' '.$class : $class_id;
	// Size
	$width = $width ? ' width="'.$width.'"' : '';
	$height = $height ? ' height="'.$height.'"' : '';

	// Inline CSS
	$inline_css = <<<_EOD_
<style type="text/css"><!--
.$class_id {
	filter:url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg'><filter id='img_filter'$blur_param>$sepia_style$contrast_style$brightness_style$grayscale_style$saturate_style$hue_style $blur_style$opacity_style$invert_style</filter></svg>#img_filter");
}
--></style>
_EOD_;

	// Image tag
	$code = <<<_EOD_
<img src="$url" style="filter:$grayscale$saturate$sepia$brightness$contrast$blur$opacity$invert$hue;-webkit-filter:$grayscale$saturate$sepia$brightness$contrast$blur$opacity$invert$hue;-moz-filter:$grayscale$saturate$sepia$brightness$contrast$blur$opacity$invert$hue;-ms-filter:$grayscale$saturate$sepia$brightness$contrast$blur$opacity$invert$hue;$filter_ie" class="$class"$width$height$alt />
_EOD_;

	$code = str_replace(array("\r\n","\r","\n","\t"), '', $inline_css.$code);
	return $code;
}



/****************************************************************
* Promotion box
****************************************************************/
function dp_sc_pi_promo_box($atts, $content = null) {
	if (is_admin()) return;
	if (!$content) return;
	if (!class_exists('DP_SC_PLUGIN')) return;
	if (!function_exists('dp_sc_plugin_status')) return;
	// Status check
	$status = dp_sc_plugin_status();
	if (!$status) return;

	extract(shortcode_atts(array(
		'class' => '',
		'column'=> '',
		'style' => '',
		'plx' => ''
	), $atts));

	if (!$column || (5 < (int)$column) || (1 >= (int)$column) || !is_numeric($column)) return ;

	// Target theme
	$dp_theme_key = defined('DP_THEME_KEY') ? ' '.DP_THEME_KEY : '';

	$delim 		= '[//+++//]';
	$codeContent	= '';

	// Get promos
	$content 	= do_shortcode($content);

	// Split the return
	$arrContent = explode( $delim, $content);
	$arrContent = array_filter($arrContent, function($item){return !preg_match("/^(\r|\n)+$/",$item);});
	$arrContent = array_values(array_filter($arrContent));
	$arrNum 	= count($arrContent) - 1;
	$last 		= '';
	$first 		= '';
	$eachPlx 	= '';

	foreach ($arrContent as $key => $val) {
		// init
		$eachPlx = '';
		if ($arrNum === $key) {
			$last = ' last';
		}
		if ($key === 0) {
			$first = ' first';
			// Parallax params for macciato theme
			if (!empty($plx)){
				$eachPlx = ' data-sr="'.$plx.'"';
			}
		} else {
			$first = '';
			// Parallax params for macciato theme
			if (!empty($plx)){
				$eachPlx = ' data-sr="'.$plx.' wait '.(0.3*$key).'s"';
			}
		}
		$codeContent .=  '<div class="promo num'.($key + 1).$first.$last.'"'.$eachPlx.'>'.$val.'</div>';
	}

	$style 	= !empty($style) ? ' style="'.$style.'"' : '';
	$class 	= !empty($class) ? ' class="dp_sc_promobox '.$class.' col'.$column.$dp_theme_key.' clearfix"' : ' class="dp_sc_promobox col'.$column.$dp_theme_key.' clearfix"' ;

	$code = <<<_EOD_
<div$class$style>$codeContent</div>
_EOD_;

	return $code;
}
	function dp_sc_pi_promo($atts, $content = null) {
		if (!$content) return;

		extract(shortcode_atts(array(
			'icon'	 => '',
			'iconstyle' => '',	// circle, square, round
			'iconsize' => '', 	// Numeric or small, big
			'iconcolor' => '',
			'iconhovercolor'=> '',
			'iconbgcolor'=> '#ebebeb',
			'iconbdwidth'=> '',
			'iconbdcolor' => '',
			'iconalign' => '', // top, right, left
			'imgurl' => '',
			'url'	 => '',
			'target' => '',
			'iconscale' => false,
			'iconrotate'=> 0,	
			'title'	 => '',
			'titlecolor'=> '',
			'titlehovercolor' => '',
			'titlesize' => 16,
			'titlebold' => true,
			'bgcolor' => '',
			'bghovercolor'=> ''
		), $atts));

		// Delimiter
		$delim 			= '[//+++//]';

		// Params
		$iconbdcss = '';
		$iconcolorcopy	= '';
		$iconbgcolorcopy = '';
		$iconsizecopy 	= 0;
		$defaulticoonsize = 38;
		$defaultimgsize = 72;
		$iconboxsize 	= '';
		$anchorcode  	= '';
		$anchoriconcode = '';
		$imgcode 		= '';
		$blockalign		= '';
		$alignflag 		= true;
		$alignmargin	= '';
		$titlecolorcopy = '';
		$titlepaddinigclass = '';

		// icon color
		if (!empty($iconcolor)) {
			$iconcolorcopy 	= $iconcolor;
			$iconcolor 		= 'color:'.$iconcolor.';';
		}
		$iconhovercolor = !empty($iconhovercolor) ? $iconhovercolor : $iconcolorcopy;

		// Title color
		if (!empty($titlecolor)) {
			$titlecolorcopy 	= $titlecolor;
			$titlecolor 		= 'color:'.$titlecolor.';';
		}
		$titlehovercolor = !empty($titlehovercolor) ? $titlehovercolor : $titlecolorcopy;

		// Border
		$iconbdwidth 	= str_replace('px', '', $iconbdwidth);
		$iconbdwidth 	= !empty($iconbdwidth) ? $iconbdwidth.'px' : '';
		$iconbdcolor 	= !empty($iconbdcolor) ? $iconbdcolor : '#222';
		if (!empty($iconbdwidth)) {
			$iconbdcss = 'border:'.$iconbdwidth.' solid '.$iconbdcolor.';';
		}

		// Filled style
		switch ($iconstyle) {
			case 'circle':
				$iconstyle = 'border-radius:50%;-o-border-radius:50%;-moz-border-radius:50%;-webkit-border-radius:50%;';
				$iconbgcolor = !empty($iconbgcolor) ? 'background-color:'.$iconbgcolor.';' : '';
				break;
			case 'round':
				$iconstyle = 'border-radius:8px;-o-border-radius:8px;-moz-border-radius:8px;-webkit-border-radius:8px;';
				$iconbgcolor = !empty($iconbgcolor) ? 'background-color:'.$iconbgcolor.';' : '';
				break;
			case 'square':
				$iconbgcolor = !empty($iconbgcolor) ? 'background-color:'.$iconbgcolor.';' : '';
				break;
			default:
				$iconstyle = '';
				$iconboxsize = '';
				$iconbgcolor = '';
				break;
		}

		// Icon size
		switch ($iconsize) {
			case 'small':
				if (!empty($imgurl)) {
					$iconsize 		= 60;
					$iconsizecopy	= $iconsize;
					$iconboxsize 	= 'width:60px;height:60px;';
				} else {
					if (!empty($iconstyle)) {
						$iconsize 		= 'font-size:26px;';
					} else {
						$iconsize 		= 'font-size:34px;';
					}
					$iconboxsize 	= 'width:52px;height:52px;';
				}
				break;
			case 'big':
				if (!empty($imgurl)) {
					$iconsize 		= 100;
					$iconsizecopy	= $iconsize;
					$iconboxsize 	= 'width:100px;height:100px;line-height:100px;';
				} else {
					if (!empty($iconstyle)) {
						$iconsize 		= 'font-size:54px;';
					} else {
						$iconsize 		= 'font-size:60px;';
					}
					$iconboxsize 		= 'width:108px;height:108px;line-height:108px;';
				}
				break;
			default:
				$iconsize 	= str_replace('px', '', $iconsize);

				if (!empty($imgurl)) {
					$iconsize 		= is_numeric($iconsize) ? $iconsize : $defaultimgsize;
					$iconsizecopy	= $iconsize;
					$iconboxsize 	= 'width:'.$iconsizecopy.'px;height:'.$iconsizecopy.'px;line-height:'.$iconsizecopy.'px;';
				} else {
					$iconsize 	= is_numeric($iconsize) ? $iconsize : $defaulticoonsize;
					$iconsizecopy	= $iconsize + $iconsize * 1.2;
					$iconboxsize = 'width:'.$iconsizecopy.'px;height:'.$iconsizecopy.'px;line-height:'.$iconsizecopy.'px;';
					if (!empty($iconstyle)) {
						$iconsize 	= !empty($iconsize) ? 'font-size:'.($iconsize - 6).'px;' : 'font-size:30px;';
					} else {
						$iconsize 	= !empty($iconsize) ? 'font-size:'.$iconsize.'px;' : 'font-size:34px;';
					}
				}
				break;
		}

		// Icon alignment
		switch ($iconalign) {
			case 'left':
				$iconalign	=  'float:left;margin-right:15px;'; //' fl-l mg15px-r';
				$alignmargin = ' style="margin-left:'.($iconsizecopy + 15).'px;"';
				$titlepaddinigclass = ' t_float';
				break;
			case 'right':
				$iconalign 	= 'float:right;margin-left:15px;'; //' fl-r mg15px-l';
				$alignmargin = ' style="margin-right:'.($iconsizecopy + 15).'px;"';
				$titlepaddinigclass = ' t_float';
				break;
			default:
				$iconalign	= 'margin:0 auto;text-align:center;'; // ' aligncenter';
				$blockalign = 'text-align:center;';
				$alignflag 	= false;
				break;
		}

		// Icon rotate class
		switch ($iconrotate) {
			case 1:
				$iconrotate = ' rotate15-r';
				break;
			case 2:
				$iconrotate = ' rotate45-r';
				break;
			case 3:
				$iconrotate = ' rotate-r';
				break;
			case 4:
				$iconrotate = ' rotate15-l';
				break;
			case 5:
				$iconrotate = ' rotate45-l';
				break;
			case 6:
				$iconrotate = ' rotate-l';
				break;
			default:
				$iconrotate = '';
				break;
		}
		//icon scaling
		$iconscale = !empty($iconscale) ? ' scaling' : '';

		// Target
		$target = !empty($target) ? ' target="_blank"' : '' ;

		// Title bold
		$titlebold = !empty($titlebold) ? 'font-weight:bold;' : '';

		// Title font size
		$titlesize 	= str_replace('px', '', $titlesize);
		if (is_numeric($titlesize)) {
			$titlesize 	= !empty($titlesize) ? 'font-size:'.$titlesize.'px;' : 'font-size:16px;';
		} else {
			$titlesize 	= 'font-size:16px;';
		}

		// Title and anchor text
		if (!empty($url)) {
			if (!empty($titlebold)) {
				$anchorcode 	= '<a href="'.$url.'"'.$target.' class="b" style="'.$titlecolor.$titlesize.'" onmouseover="this.style.color=\''.$titlehovercolor.'\'" onmouseout="this.style.color=\''.$titlecolorcopy.'\'">';
			} else {
				$anchorcode 	= '<a href="'.$url.'"'.$target.' style="'.$titlecolor.$titlesize.'" onmouseover="this.style.color=\''.$titlehovercolor.'\'" onmouseout="this.style.color=\''.$titlecolorcopy.'\'">';
			}
			if (!empty($imgurl)) {
				$anchoriconcode = '<a href="'.$url.'"'.$target.' class="picon">';
			} else {
				$anchoriconcode = '<a href="'.$url.'"'.$target.'  class="picon" style="'.$iconcolor.'" onmouseover="this.style.color=\''.$iconhovercolor.'\'" onmouseout="this.style.color=\''.$iconcolorcopy.'\'">';
			}

			$title 		= !empty($title) ? '<div class="promo_title'.$titlepaddinigclass.'" style="'.$titlebold.$blockalign.'">'.$anchorcode.$title.'</a></div>' : '';
		} else {
			$title 		= !empty($title) ? '<div class="promo_title'.$titlepaddinigclass.'" style="'.$titlecolor.$titlesize.$titlebold.$blockalign.'">'.$title.'</div>' : '';
		}

		// Image or icon
		if (!empty($imgurl)) {
			if (!empty($url)) {
				$icon = $anchoriconcode.'<span class="cover_img" style="background-image:url(\''.$imgurl.'\')"></span></a>';
			} else {
				$icon = '<span class="cover_img" style="background-image:url(\''.$imgurl.'\')"></span>';
			}
		} else {
			if (!empty($icon)) {
				if (!empty($url)) {
					$icon = $anchoriconcode.'<i class="promo_icon '.$icon.'" style="'.$iconsize.'text-align:center;"></i></a>';
				} else {
					$icon = '<i class="promo_icon '.$icon.'" style="'.$iconsize.$iconcolor.'text-align:center;"></i>';
				}
			} else {
				$alignmargin = '';
			}
		}

		// Source code
		if (!empty($icon)) {
			if (!empty($alignflag)) {
				$code = <<<_EOD_
<div class="promo_icon_div$iconrotate$iconscale" style="$iconalign$iconbgcolor$iconbdcss$iconboxsize$iconstyle">$icon</div><div$alignmargin class="promo_text_div">$title<div class="promo_text" style="font-size:12px;$blockalign">$content</div></div>
_EOD_;
			} else {
				$code = <<<_EOD_
<div class="promo_icon_div$iconrotate$iconscale" style="$iconalign$iconbgcolor$iconbdcss$iconboxsize$iconstyle">$icon</div>$title<div class="promo_text" style="font-size:12px;$blockalign">$content</div>
_EOD_;
			}
		} else {
			if (!empty($alignflag)) {
				$code = <<<_EOD_
<div$alignmargin class="promo_text_div">$title<div class="promo_text" style="font-size:12px;$blockalign">$content</div></div>
_EOD_;
			} else {
				$code = <<<_EOD_
$title<div class="promo_text" style="font-size:12px;$blockalign">$content</div>
_EOD_;
			}
		}

		// bg color
		if (!empty($bgcolor) && empty($bghovercolor)) {
			$code = '<div class="promo_inner" style="background-color:'.$bgcolor.';">'.$code.'</div>';
		}
		else if (empty($bgcolor) && !empty($bghovercolor)) {
			$code = '<div class="promo_inner"  onmouseover="this.style.background=\''.$bghovercolor.'\'" onmouseout="this.style.background=\'transparent\'">'.$code.'</div>';
		}else if (!empty($bgcolor) && !empty($bghovercolor)) {
			$code = '<div class="promo_inner" style="background-color:'.$bgcolor.';" onmouseover="this.style.background=\''.$bghovercolor.'\'" onmouseout="this.style.background=\''.$bgcolor.'\'">'.$code.'</div>';
		}

		// Set delimiter
		$code .= $delim;

		return $code;
	}


/****************************************************************
* Highlight content
****************************************************************/
function dp_sc_highlighter($atts, $content = null) {
	if (is_admin()) return;
	if (!$content) return;

	if (!class_exists('DP_SC_PLUGIN')) return;
	if (!function_exists('dp_sc_plugin_status')) return;
	// Status check
	$status = dp_sc_plugin_status();
	if (!$status) return;
	

	extract(shortcode_atts(array(
		'id' => '',
		'class' => '',
		'style' => '',
		'type' => false, // 0 or 1 /  highlight or slideshow
		'fx' => 'fade',
		'time' => 3000,
		'fadetime'=> 2000,
		'pause' => false,
		'loop' => 1
	), $atts));

	$delim 		= '[//+++//]';
	$codeContent	= '';

	$content = do_shortcode($content);

	// Get slides
	$arrContent = explode( $delim, $content );
	$arrContent = array_filter($arrContent, function($item){return !preg_match("/^(\r|\n)+$/",$item);});
	// $arrContent = array_values(array_filter($arrContent));
	// Separate every 3 elements (Remove unnecessary last item).
	$arrContent = array_chunk($arrContent, 3);

	// Each slide
	foreach ($arrContent as $key => $val) {
		$hl_content = str_replace(array("\r\n","\r","\n"), '', $val[0]);
		$fl_class 	= $val[1] ? ' class="dp_sc_highlight hl-target'.$val[1].'"' : ' class="dp_sc_highlight hl-target"';
		$fl_style 	= $val[2];
		$codeContent .= '<div'.$fl_class.$fl_style.'>'.$hl_content.'</div>';
	}

	// Pause on hover (2014.5.2 this is further function)
	if ($pause) {
		$pause = 1;
	} else {
		$pause = 0;
	}

	// Type
	if ($type == 1 || $type == 'slideshow') {
		$type = 'slideshow';
	} else {
		$type = 'default';
	}
 
	// Loop (2014.5.2 this is further function)
	if ($loop == 0 || $loop == 'false') {
		$loop = 0;
	} else {
		$loop = 1;
	}

	// effect (2014.5.2 this is further function)
	switch ($fx) {
		case 'slide':
			$fx = 'slide';
			break;
		default:
			$fx = 'fade';
			break;
	}

	$id = !empty($id) ? ' id="'.$id.'"' : '';
	$class 	= !empty($class) ? 'dp_sc_highlighter '.$class : 'dp_sc_highlighter';
	$style 	= !empty($style) ? ' style="'. $style . '"' : '';

	$code = <<<_EOD_
<div$id class="$class $type clearfix"$style data-time="$time" data-fadetime="$fadetime" data-type="$type" data-fx="$fx" data-loop="$loop">$codeContent</div>
_EOD_;

	$code = str_replace(array("\r\n","\r","\n","\t"), '', $code);
	return $code;
}
	function dp_sc_highlight($atts, $content = null) {
		if (!$content) return;

		extract(shortcode_atts(array(
			'class' => '',
			'style' => ''
		), $atts));

		$delim 		= '[//+++//]';

		$content = do_shortcode($content);
		$class = !empty($class) ? ' '.$class : '';
		$style = !empty($style) ? ' style="' . $style . '"' : '';

		$code = $content . $delim . $class . $delim . $style . $delim;
		return $code;
	}



/****************************************************************
* Profile
****************************************************************/
function dp_sc_profile($atts, $content = null) {
	if (is_admin()) return;
	if (!$content) return;

	if (!class_exists('DP_SC_PLUGIN')) return;
	if (!function_exists('dp_sc_plugin_status')) return;
	// Status check
	$status = dp_sc_plugin_status();
	if (!$status) return;
	extract(shortcode_atts(array(
		'name'  => '',
		'namesize'=> 18,
		'namebold'=> true,
		'nameitalic'=> false,
		'title1' => '',
		'title1size' => 14,
		'title1bold' => false,
		'title1italic'=> false,
		'title2' => '',
		'title2size' => 14,
		'title2bold' => false,
		'title2italic'=> false,
		'hoverfx'=> false,
		'authorurl'=> get_home_url(),
		'authortargetblank' => false,
		'namecolor'=> '#333333',
		'desccolor' => '#888888',
		'descfontsize' => 12,
		'profimgurl'=> '',
		'profshape' => 'circle', // circle, round, square
		'profbdwidth' => 5,
		'profsize' => 100,
		'topbgimgurl'=> '',
		'topbgcolor'=> '#dddddd',
		'bgcolor'=> '#ffffff',
		'border'=> false,
		'bdcolor'=> '#dddddd',
		'twitterurl' => '',
		'facebookurl' => '',
		'googleplusurl' => '',
		'youtubeurl'=> '',
		'pinteresturl'=> '',
		'instagramurl'=> '',
		'width'  => '100%',
		'class'	 => '',
		'plx'	 => ''
	), $atts));

	if (!$name) return;

	$style 	= '';
	$border_class = '';
	$title_html = '';

	$namebold 	= (bool)$namebold ? 'font-weight:bold;' : '';
	$nameitalic = (bool)$nameitalic ? 'font-style:italic;' : '';

	$namesize = str_replace('px', '', $namesize);
	$namesize = (bool)$namesize ? $namesize : 18;
	$namesize = mb_convert_kana($namesize);
	$namesize = is_numeric($namesize) ? 'font-size:'.$namesize.'px;' : '';

	$profsize = str_replace('px', '', $profsize);
	$profsize = (bool)$profsize ? $profsize : 100;
	$profsize = mb_convert_kana($profsize);
	$profsize_style = is_numeric($profsize) ? 'width:'.$profsize.'px;height:'.$profsize.'px;' : '';

	$descfontsize = str_replace('px', '', $descfontsize);
	$descfontsize = (bool)$descfontsize ? $descfontsize : 12;
	$descfontsize = mb_convert_kana($descfontsize);
	$descfontsize = is_numeric($descfontsize) ? 'font-size:'.$descfontsize.'px;' : '';

	$namecolor 		= (bool)$namecolor ? 'color:'.$namecolor.';' : '';
	$desccolor 		= (bool)$desccolor ? 'color:'.$desccolor.';' : '';
	$profbdwidth 	= (bool)$profbdwidth ? 'border-width:'.str_replace('px', '', $profbdwidth).'px;' : '';

	$profimgurl 	= !empty($profimgurl) ? '<span class="cover_img" style="background-image:url(\''.$profimgurl.'\');"></span>' : '';
	$topbgimgurl 	= !empty($topbgimgurl) ? '<div class="dp_sc_prof_top_bgimg"><img src="'.$topbgimgurl.'" alt="Profile image" /></div>' : '';

	$topbgcolor_style 	= !empty($topbgcolor) ? 'background-color:'.$topbgcolor.';' : '';
	$bgcolor_style 		= !empty($bgcolor) ? 'background-color:'.$bgcolor.';' : '';

	$authortargetblank = (bool)$authortargetblank ? ' target="_blank"' : '';

	$title1_style = '';
	if (isset($title1) && !empty($title1)){
		$title1bold 	= (bool)$title1bold ? 'font-weight:bold;' : '';
		$title1italic = (bool)$title1italic ? 'font-style:italic;' : '';

		$title1size = str_replace('px', '', $title1size);
		$title1size = (bool)$title1size ? $title1size : 14;
		$title1size = mb_convert_kana($title1size);
		$title1size = is_numeric($title1size) ? 'font-size:'.$title1size.'px;' : '';
		$title1_style 	= ' style="'.$namecolor.$title1size.$title1bold.$title1italic.'"';
		$title_html = '<div class="dp_sc_prof_title"'.$title1_style.'>'.$title1.'</div>';
	}
	$title2_style = '';
	if (isset($title2) && !empty($title2)){
		$title2bold 	= (bool)$title2bold ? 'font-weight:bold;' : '';
		$title2italic = (bool)$title2italic ? 'font-style:italic;' : '';

		$title2size = str_replace('px', '', $title2size);
		$title2size = (bool)$title2size ? $title2size : 14;
		$title2size = mb_convert_kana($title2size);
		$title2size = is_numeric($title2size) ? 'font-size:'.$title2size.'px;' : '';
		$title2_style 	= ' style="'.$namecolor.$title2size.$title2bold.$title2italic.'"';
		$title_html .= '<div class="dp_sc_prof_title"'.$title2_style.'>'.$title2.'</div>';
	}

	$twitter_icon 	= '';
	$facebook_icon 	= '';
	$gplus_icon 	= '';
	$youtube_icon 	= '';
	$pinterest_icon = '';

	// Class
	$class = (bool)$class ? ' '.$class : '';

	//border
	if ((bool)$border) {
		$border = 'border:1px solid '.$bdcolor.';';
		$border_class = ' has-bd';
	}

	// Profile shape
	switch ($profshape) {
		case 'round':
			$profshape = ' round';
			$twitter_icon 	= 'icon-twitter-rect';
			$facebook_icon 	= 'icon-facebook-rect';
			$gplus_icon 	= 'icon-gplus-squared';
			$youtube_icon 	= 'icon-youtube-rect';
			$pinterest_icon = 'icon-pinterest-rect';
			$instagram_icon = 'icon-instagram';
			break;
		case 'square':
			$profshape = '';
			$twitter_icon 	= 'icon-twitter';
			$facebook_icon 	= 'icon-facebook';
			$gplus_icon 	= 'icon-gplus';
			$youtube_icon 	= 'icon-youtube';
			$pinterest_icon = 'icon-pinterest';
			$instagram_icon = 'icon-instagram';
			break;
		default:
			$profshape = ' circle';
			$twitter_icon 	= 'icon-twitter-circled';
			$facebook_icon 	= 'icon-facebook-circled';
			$gplus_icon 	= 'icon-gplus-circled';
			$youtube_icon 	= 'icon-youtube';
			$pinterest_icon = 'icon-pinterest-circled';
			$instagram_icon = 'icon-instagram';
			break;
	}

	// Hover effect
	switch ($hoverfx) {
		case 1:
		case 'rotate15':
			$hoverfx = ' rotate15';
			break;

		case 2:
		case 'zoom':
			$hoverfx = ' zoom-up';
			break;

		default:
			$hoverfx = '';
			break;
	}

	// Main width
	$width_style 	= !empty($width) ? 'width:'.$width.';' : 'width:100%;'; 
	// CSS
	$name_style 	= ' style="'.$namecolor.$namesize.$namebold.$nameitalic.'"';
	$prof_style 	= ' style="margin-top:-'.intval($profsize/2).'px;border-style:solid;'.'border-color:'.$bgcolor.';background-color:'.$bgcolor.';'.$profbdwidth.$profsize_style.'"';
	$top_style 		= ' style="'.$topbgcolor_style.'"';
	$desc_style 	= ' style="'.$desccolor.$descfontsize.'"';
	$style 			= ' style="'.$width_style.$bgcolor_style.$border.'"';

	if (!empty($authorurl)) {
		// Name
		$name_html = '<div class="dp_sc_prof_name"><a href="'.$authorurl.'"'.$name_style.$authortargetblank.'>'.$name.'</a></div>';
		// Prof
		$prof_html = '<figure class="dp_sc_prof_img'.$profshape.$hoverfx.'"'.$prof_style.'><a href="'.$authorurl.'"'.$authortargetblank.'>'.$profimgurl.'</a></figure>';
	} else {
		// Name
		$name_html = '<div class="dp_sc_prof_name"><span'.$name_style.'>'.$name.'</span></div>';
		// Prof
		$prof_html = '<figure class="dp_sc_prof_img'.$profshape.'"'.$prof_style.'>'.$profimgurl.'</figure>';
	}

	// Top area
	$top_html = '<div class="dp_sc_prof_top_area'.$border_class.'"'.$top_style.'>'.$topbgimgurl.'</div>';

	// description
	$desc_html = '<div class="dp_sc_prof_desc"'.$desc_style.'>'.$content.'</div>';

	// SNS
	$sns_html = '';
	if ($twitterurl) {
		$twitterurl = '<li><a href="'.$twitterurl.'" class="'.$twitter_icon.'" title="Follow on Twitter" target="_blank" style="'.$namecolor.';"><span>Twitter</span></a></li>';
	}
	if ($facebookurl) {
		$facebookurl = '<li><a href="'.$facebookurl.'" class="'.$facebook_icon.'" title="Like on Facebook" target="_blank" style="'.$namecolor.';"><span>Facebook</span></a></li>';
	}
	if ($googleplusurl) {
		$googleplusurl = '<li><a href="'.$googleplusurl.'" class="'.$gplus_icon.'" title="Share on Google+" target="_blank" style="'.$namecolor.';"><span>Google+</span></a></li>';
	}
	if ($youtubeurl) {
		$youtubeurl = '<li><a href="'.$youtubeurl.'" class="'.$youtube_icon.'" title="Subscribe on YouTube" target="_blank" style="'.$namecolor.';"><span>YouTube</span></a></li>';
	}
	if ($pinteresturl) {
		$pinteresturl = '<li><a href="'.$pinteresturl.'" class="'.$pinterest_icon.'" title="Share on Pinterest" target="_blank" style="'.$namecolor.';"><span>Pinterest</span></a></li>';
	}
	if ($instagramurl) {
		$instagramurl = '<li><a href="'.$instagramurl.'" class="'.$instagram_icon.'" title="Share on Instagram" target="_blank" style="'.$namecolor.';"><span>Instagram</span></a></li>';
	}
	if ($twitterurl || $facebookurl || $googleplusurl || $youtubeurl || $pinteresturl || $instagramurl) {
		$sns_html = '<div class="dp_sc_prof_sns'.$hoverfx.'"><ul>'.$googleplusurl.$twitterurl.$facebookurl.$instagramurl.$pinteresturl.$youtubeurl.'</ul></div>';
	}

	// Parallax params for macciato theme
	if (!empty($plx)){
		$plx = ' data-sr="'.$plx.'"';
	}

	$code = <<<_EOD_
<div class="dp_sc_prof$class"$style$plx>$top_html$prof_html$name_html$title_html$desc_html$sns_html</div>
_EOD_;
	return $code;
}



/****************************************************************
* Slideshow content
****************************************************************/
function dp_sc_slideshow($atts, $content = null) {
	if (is_admin()) return;
	if (!$content) return;

	if (!class_exists('DP_SC_PLUGIN')) return;
	if (!function_exists('dp_sc_plugin_status')) return;
	// Status check
	$status = dp_sc_plugin_status();
	if (!$status) return;

	extract(shortcode_atts(array(
		'id' => '',
		'class' => '',
		'style' => '',
		'fx' => 'fade', // fade or slide
		'autoplay'=> 'true', // 0 or 1 /  highlight or slideshow
		'showtime' => 3500,
		'transitiontime'=> 1200,
		'hoverpause'=> 'false',
		'showcontrol'=> 'true',
		'controlpos'=> 'center',	// right, center, left
		'nexttext'=> 'Next',
		'prevtext'=> 'Prev',
		'showpagenavi'=> 'true',
		'pagenavipos'=> 'center',	// right, center, left
		'usecaption'=> 'true',
		'random'=> 'false',
		'responsive'=> 'true',
		'captionblack' => false
	), $atts));

	$delim 		= '[//+++//]';
	$codeContent 	= '';
	$centercontrols = '';
	$centermarkers	= '';

	$content = do_shortcode($content);

	// Get slides
	$arrContent = explode( $delim, $content );
	$arrContent = array_filter($arrContent, function($item){return !preg_match("/^(\r|\n)+$/",$item);});
	// $arrContent = array_values(array_filter($arrContent));
	// Separate every 3 elements (Remove unnecessary last item).
	$arrContent = array_chunk($arrContent, 4);

	// Each slides
	foreach ($arrContent as $key => $val) {
		$val[0] = str_replace(array("\r\n","\r","\n"), '', $val[0]);
		if ($val[0] != '[//null//]') {
			$slide_content = '<div class="dp_sc_slideshow_content">'.$val[0].'</div>';
		} else {
			$slide_content = '';
		}
		
		$slide_class 	= $val[1] ? ' class="dp_sc_slideshow_li '.$val[1].'"' : ' class="dp_sc_slideshow_li"';
		$slide_style 	= $val[2];
		$slide_image 	= $val[3];
		
		$codeContent .= '<li'.$slide_class.$slide_style.'>'.$slide_image.$slide_content.'</li>';
	}

	if (!$fx || $fx !== 'slide') {
		$fx = 'fade';
	}

	if (!$autoplay) {
		$autoplay = 'true';
	}

	$showtime = mb_convert_kana($showtime);
	if (!is_numeric($showtime)) {
		$showtime = 3500;
	}

	$transitiontime = mb_convert_kana($transitiontime);
	if (!is_numeric($transitiontime)) {
		$transitiontime = 1200;
	}

	if (!$hoverpause) {
		$hoverpause = 'false';
	}

	if (!$showcontrol) {
		$showcontrol = 'true';
	}

	switch ($controlpos) {
		case 'right':
			$controlpos = ' control-r';
			$centercontrols = 'false';
			break;
		case 'left':
			$controlpos = '';
			$centercontrols = 'false';
			break;
		default:
			$controlpos = '';
			$centercontrols = 'true';
			break;
	}

	switch ($pagenavipos) {
		case 'right':
			$pagenavipos = ' pagenavi-r';
			$centermarkers = 'false';
			break;
		case 'left':
			$pagenavipos = '';
			$centermarkers = 'false';
			break;
		default:
			$pagenavipos = '';
			$centermarkers = 'true';
			break;
	}

	if ($random != 'true') {
		$random = 'false';
	}

	if ((bool)$captionblack) {
		$captionblack = ' cpt-blk';
	}

	$nexttext = strip_tags($nexttext);
	$prevtext = strip_tags($prevtext);

	$id = !empty($id) ? ' id="'.$id.'"' : '';
	$class 	= !empty($class) ? 'dp_sc_slideshow loading'.$class : 'dp_sc_slideshow loading';
	$style 	= !empty($style) ? ' style="'. $style . '"' : '';

	$code = <<<_EOD_
<div$id class="$class$captionblack$controlpos$pagenavipos clearfix" data-fx="$fx" data-speed="$showtime" data-duration="$transitiontime" data-autoplay="$autoplay" data-control="$showcontrol" data-center-ctrl="$centercontrols" data-next-text="$nexttext" data-prev-text="$prevtext" data-pagenavi="$showpagenavi" data-center-markers="$centermarkers" data-hover-pause="$hoverpause" data-captions="$usecaption" dara-random="$random" data-responsive="$responsive"$style>
	<ul class="bjqs">
		$codeContent
	</ul>
</div>
_EOD_;

	$code = str_replace(array("\r\n","\r","\n","\t"), '', $code);
	return $code;
}
	function dp_sc_slide($atts, $content = null) {
		extract(shortcode_atts(array(
			'class' => '',
			'style' => '',
			'caption'=> '',
			'imgurl'=> '',
			'url' => '',
			'newwindow'=> false
		), $atts));

		$delim 		= '[//+++//]';

		$content = str_replace(array("\r\n","\r","\n","\t"), '', $content);

		if (!$content || $content == "") {
			$content = '[//null//]';
		} else {
			$content = do_shortcode($content);
		}

		$caption = !empty($caption) ? ' title="'.$caption.'"': '';
		$img_code = !empty($imgurl) ? '<img src="'.$imgurl.'"'.$caption.' class="bjqs-img" alt="Slide image" />' : '';

		if ($img_code && $url) {
			if ($newwindow) {
				$img_code = '<a href="'.$url.'" target="_blank">'.$img_code.'</a>';
			} else {
				$img_code = '<a href="'.$url.'">'.$img_code.'</a>';
			}
		}

		$class = !empty($class) ? ' '.$class : '';
		$style = !empty($style) ? ' style="' . $style . '"' : '';

		$code = $content . $delim . $class . $delim . $style . $delim . $img_code . $delim;
		return $code;
	}

/****************************************************************
* Flex box
****************************************************************/
function dp_sc_pi_flex_box($atts, $content = null) {
	if (is_admin()) return;
	if (!$content) return;
	if (!class_exists('DP_SC_PLUGIN')) return;
	if (!function_exists('dp_sc_plugin_status')) return;
	// Status check
	$status = dp_sc_plugin_status();
	if (!$status) return;

	extract(shortcode_atts(array(
		'id' => '',
		'class' => '',
		'direction'=> '',	// row(1), rowreverse(2), col(3), colreverse(4)
		'wrap'  => '',	// nowrap(1), wrap(2), reverse(3)
		'alignh'=> '',	// left(1), right(2), center(3), between(4), around(5)
		'alignv'=> '',	// stretch(1), center(2), top(3), bottom(4), between(5), around(6)
		'alignitems'=> '',	// stretch(1), center(2), start(3), end(4), baseline(5)
		'flexchildren'=> false,
		'width' => '',
		'height'=> '',
		'style' => ''
	), $atts));

	// Target theme
	$dp_theme_key = defined('DP_THEME_KEY') ? ' '.DP_THEME_KEY : '';

	// Flex direction
	if ( $direction === 'rowreverse' || $direction === '2' ) {
		$direction =' dir_row_rev';
	} 
	else if ( $direction === 'col' || $direction === '3' ) {
		$direction =' dir_col';
	}
	else if ( $direction === 'colreverse' || $direction === '4' ) {
		$direction =' dir_col_rev';
	}

	// Flex wrap
	if ( $wrap === 'wrap' || $wrap === '2' ) {
		$wrap =' wrap';
	} 
	else if ( $wrap === 'reverse' || $wrap === '3' ) {
		$wrap =' wrap_rev';
	}

	// Flex justify ( Horizontal)
	if ( $alignh === 'right' || $alignh === '2' ) {
		$alignh =' justify_end';
	} 
	else if ( $alignh === 'center' || $alignh === '3' ) {
		$alignh =' justify_cen';
	}
	else if ( $alignh === 'between' || $alignh === '4' ) {
		$alignh =' justify_bet';
	}
	else if ( $alignh === 'around' || $alignh === '5' ) {
		$alignh =' justify_aro';
	}

	// Flex align content (Vertical)
	if ( $alignv === 'center' || $alignv === '2' ) {
		$alignv =' al_con_cen';
	}
	else if ( $alignv === 'top' || $alignv === '3' ) {
		$alignv =' al_con_sta';
	}
	if ( $alignv === 'bottom' || $alignv === '4' ) {
		$alignv =' al_con_end';
	}
	else if ( $alignv === 'between' || $alignv === '5' ) {
		$alignv =' al_con_bet';
	}
	else if ( $alignv === 'around' || $alignv === '6' ) {
		$alignv =' al_con_aro';
	}

	// Flex align items
	if ( $alignitems === 'center' || $alignitems === '2' ) {
		$alignitems =' al_item_cen';
	}
	else if ( $alignitems === 'start' || $alignitems === '3' ) {
		$alignitems =' al_item_sta';
	}
	else if ( $alignitems === 'end' || $alignitems === '4' ) {
		$alignitems =' al_item_end';
	}
	else if ( $alignitems === 'baseline' || $alignitems === '5' ) {
		$alignitems =' al_item_bas';
	}

	// Flex children?
	if ((bool)$flexchildren) {
		$flexchildren = ' flex_children';
	}

	// Width
	$width = !empty($width) ? 'width:'.$width.';' : '';
	// height
	$height = str_replace('px', '', $height);
	$height = !empty($height) ? 'height:'.$height.';' : '';

	// Get promos
	$content 	= do_shortcode($content);

	$id = !empty($id) ? ' id="'.$id.'"' : '';
	$style 	= !empty($style) || !empty($width) || !empty($height) ? ' style="'.$width.$height.$style.'"' : '';
	$class 	= !empty($class) ? ' '.$class.$dp_theme_key : $dp_theme_key;
	$class 	.= $direction.$wrap.$alignh.$alignitems.$alignv.$flexchildren;

	$code = '<div'.$id.' class="dp_sc_fl_box'.$class.'"'.$style.'>'.$content.'</div>';

	return $code;
}
	function dp_sc_pi_flex_item($atts, $content = null) {
		if (!$content) return;

		extract(shortcode_atts(array(
			'padding'=> '',
			'margin'=> '',
			'width' => '',
			'height'=> '',
			'flex' => '',
			'class' => '',
			'style' => '',
			'plx' => ''
		), $atts));

		$content 	= do_shortcode($content);

		// padding
		$padding = str_replace('px', '', $padding);
		$padding = !empty($padding) ? 'padding:'.$padding.'px;' : '';

		// margin
		$margin = str_replace('px', '', $margin);
		$new_margin = !empty($margin) ? 'margin-left:'.$margin.'px;margin-right:'.$margin.'px;' : '';
		$new_margin = ($margin === '0') ? 'margin-left:0;margin-right:0;' : $new_margin;

		// Width
		$width = !empty($width) ? 'width:'.$width.';' : '';
		// height
		$height = str_replace('px', '', $height);
		$height = !empty($height) ? 'height:'.$height.'px;' : '';

		// Felx paaram
		$flex = !empty($flex) ? 'flex:'.$flex.';-ms-flex:'.$flex.';-webkit-flex:'.$flex.';' : '';

		$class = !empty($class) ? ' '.$class : '';
		$style = (!empty($style) || !empty($padding) || !empty($new_margin
			) || !empty($width) || !empty($height) || !empty($flex)) ? ' style="'.$padding.$new_margin.$width.$height.$flex.$style.'"' : '';

		// Parallax params for macciato theme
		if (!empty($plx)){
			$plx = ' data-sr="'.$plx.'"';
		}

		$content = '<div class="dp_sc_fl_item'.$class.'"'.$style.$plx.'>'.$content.'</div>';
		return $content;
	}


/****************************************************************
* Blog Card
****************************************************************/
function dp_sc_blogcard($atts, $content = null) {
	if (is_admin()) return;

	if (!class_exists('DP_SC_PLUGIN')) return;
	if (!function_exists('dp_sc_plugin_status')) return;
	// Status check
	$status = dp_sc_plugin_status();
	if (!$status) return;
	extract(shortcode_atts(array(
		'url' => '',
		'width' => '',
		'height' => '190px',
		'style'=> '',
		'class'=> ''
	), $atts));

	if (!$url) return;
	$url = urlencode($url);

	$width = empty($width) ? 'width:100%;' : 'width:'.mb_convert_kana($width).';';
	$height = empty($height) ? 'height:190px;' : 'height:'.mb_convert_kana($height).';';
	$class = !empty($class) ? ' '.$class : '';

	$code = '<iframe style="'.$width.$height.'max-height:250px;margin:10px 0px;display:block;'.$style.'" src="https://hatenablog-parts.com/embed?url='.$url.'" class="embed-card embed-blogcard'.$class.'" height="190" width="500" frameborder="0" scrolling="no"></iframe>';

	return $code;
}

/****************************************************************
* Conversation Contents
****************************************************************/
function dp_sc_talk($atts, $content = null) {
	if (is_admin()) return;

	if (!class_exists('DP_SC_PLUGIN')) return;
	if (!function_exists('dp_sc_plugin_status')) return;
	// Status check
	$status = dp_sc_plugin_status();
	if (!$status) return;
	extract(shortcode_atts(array(
		'avatarimg' => '',
		'avataricon' => 'icon-user',
		'avatarbdwidth' => '',
		'avatarbdcolor' => '',
		'avatarsize' => 80,
		'avatarshape' => 'circle',
		'name' => '',
		'words' => '',
		'align' => 'left',
		'marginbtm' => '',
		'bgcolor' => '',
		'color' => '',
		'bdcolor' => '',
		'bdstyle' => '',
		'style'=> '',
		'class'=> ''
	), $atts));

	if (empty($words)) return;

	$words_style = '';
	$avatar_style = '';
	$avatar_img_style = '';
	$name_flag = '';
	$avatarbdwidth_cp = '';
	$arrow_style = '';
	$align_selector = 'left';

	// id
	$talk_id = 'talk-'.dp_sc_rand(4);

	$avatarsize = str_replace('px', '', $avatarsize);
	$avatarsize = !empty($avatarsize) ? $avatarsize : 80;
	$avatarsize = mb_convert_kana($avatarsize);
	$avatarsize_style = is_numeric($avatarsize) ? 'width:'.$avatarsize.'px;height:'.$avatarsize.'px;' : '';

	// avatar border
	if (!empty($avatarbdwidth)) {
		$avatarbdwidth_cp = (int)str_replace('px', '', $avatarbdwidth);
		$avatarbdwidth 	= 'border:'.$avatarbdwidth_cp.'px solid;';
	}
	// avatar borde color
	$avatarbdcolor = !empty($avatarbdcolor) ? 'border-color:'.$avatarbdcolor. ';' : '';

	// Avatar size CSS
	if (!empty($avatarsize_style) ) {
		$avatar_style = ' style="'.$avatarsize_style.'"';
	}

	// avatar image
	if (!empty($avatarimg)) {
		$avatarimg = '<div class="cover_img" style="'.$avatarbdwidth.$avatarbdcolor.'background-image:url(\''.$avatarimg.'\');"></div>';
	} else {
		$avatarimg = '<i class="avatar_icon '.$avataricon.'" style="'.$avatarbdwidth.$avatarbdcolor.'font-size:'.((int)$avatarsize - 25).'px;height:'.((int)$avatarsize - 5).'px;"></i>';
	}
	// avatar image shape
	switch ($avatarshape) {
		case 'round':
		case 2:
			$avatarshape = ' round';
			break;
		case 'square':
		case 3:
			$avatarshape = ' square';
			break;
		default:
			$avatarshape = ' circle';
			break;
	}

	// Name
	if (!empty($name)) {
		$name = '<figcaption class="avatar_name">'.$name.'</figcaption>';
		$name_flag = ' has_name';
	}

	// alignment
	switch ($align) {
		case 2:
		case 'right':
		case 'r':
			$align = ' right';
			$align_selector = 'right';
			break;
		default:
			$align = ' left';
			break;
	}
	// Words CSS
	if (!empty($bgcolor)) {
		if ($align_selector === 'right'){
			$arrow_style = '#'.$talk_id.' .talk_words.right:after{border-color:transparent transparent transparent '.$bgcolor.';}';
		} else {
			$arrow_style = '#'.$talk_id.' .talk_words:after{border-color:transparent '.$bgcolor.' transparent transparent;}';
		}
		$bgcolor = 'background-color:'.$bgcolor.';';
	}
	if (!empty($bdcolor)) {
		if ($align_selector === 'right'){
			$arrow_style .= '#'.$talk_id.' .talk_words.right:before{border-color:transparent transparent transparent '.$bdcolor.';}';
		} else {
			$arrow_style .= '#'.$talk_id.' .talk_words:before{border-color:transparent '.$bdcolor.' transparent transparent}';
		}
		$bdcolor = 'border-color:'.$bdcolor.';';
	}
	if (!empty($color)) {
		$color = 'color:'.$color.';';
	}
	// Border style
	switch ($bdstyle) {
		case 2:
		case 'dotted':
			$bdstyle = 'border-style:dotted;';
			break;
		case 3:
		case 'dashed':
			$bdstyle = 'border-style:dashed;';
			break;
		case 4:
		case 'double':
			$bdstyle = 'border-style:double;';
			break;
		default:
			$bdstyle = '';
			break;
	}
	$words_style = ' style="width:calc(100% - '.((int)$avatarsize + 20).'px);'.$color.$bgcolor.$bdcolor.$bdstyle.'"';

	// Arrow style
	if (!empty($arrow_style)) {
		$arrow_style = '<style>'.$arrow_style.'</style>';
	}

	// Whole style
	if (!empty($style)) {
		$style = ' style="'.$dp_dc_talk.'"';
	}

	// Avatar 
	$code = '<figure class="talk_avatar'.$avatarshape.$align.$name_flag.'"'.$avatar_style.'>'.$avatarimg.$name.'</figure>';
	// Words
	$code .= '<div class="talk_words'.$align.'"'.$words_style.'>'.$words.'</div>';
	// class
	$class = !empty($class) ? ' '.$class : '';
	// Result
	$code = '<div id="'.$talk_id.'" class="dp_sc_talk'.$class.' clearfix"'.$style.'>'.$code.'</div>'.$arrow_style;

	return $code;
}


/****************************************************************
* Pricing Table
****************************************************************/
function dp_sc_pricing_table($atts, $content = null) {
	if (is_admin()) return;
	if (!$content) return;
	if (!class_exists('DP_SC_PLUGIN')) return;
	if (!function_exists('dp_sc_plugin_status')) return;
	// Status check
	$status = dp_sc_plugin_status();
	if (!$status) return;

	extract(shortcode_atts(array(
		'rowbordered' => true,
		'hoverfx' => false,
		'fontcolor' => '',
		'fontsize' => 13,
		'class' => '',
		'style' => '',
		'plx' => ''
	), $atts));

	// Target theme
	$dp_theme_key = defined('DP_THEME_KEY') ? ' '.DP_THEME_KEY : '';

	// For Parallax theme
	$plx = !empty($plx) ? ' data-sr="'.$plx.'"' : '';

	// ************
	// CSS start
	//*************
	$fontsize = str_replace('px', '', $fontsize);
	$fontsize = mb_convert_kana($fontsize);
	$fontsize = 'font-size:'.$fontsize.'px;';
	$fontcolor = !empty($fontcolor) ? 'color:'.$fontcolor.';' : '';
	// ************
	// CSS End
	//*************

	// list border
	if (empty($rowbordered) || $rowbordered === 'false' || $rowbordered === '0') {
		$rowbordered = '';
	} else {
		$rowbordered = ' rowbd';
	}
	// Hover fx
	if (empty($hoverfx) || $hoverfx === 'false' || $hoverfx === '0') {
		$hoverfx = '';
	} else {
		$hoverfx = ' hoverfx';
	}

	// Get table list
	$content 	= do_shortcode($content);

	$style 	= !empty($style) ? ' style="'.$fontsize.$fontcolor.$style.'"' : ' style="'.$fontsize.$fontcolor.'"';
	$class 	= !empty($class) ? ' '.$class : '';
	$class 	.= ' justify_cen flex_children'.$rowbordered.$hoverfx.$dp_theme_key;

	$code = '<div class="dp_sc_ptable dp_sc_fl_box'.$class.'"'.$style.$plx.'>'.$content.'</div>';

	return $code;
}
	function dp_sc_pricing_table_item($atts, $content = null) {
		extract(shortcode_atts(array(
			'title' => '',
			'titlesize' => 18,
			'titlebold' => false,
			'titlecaption' => '',
			'titlecaptionsize' => 12,
			'titlecolor' => '#fff',
			'price' => '',
			'pricesize' => 40,
			'pricebold' => false,
			'pricecaption' => '',
			'pricecaptionsize' => 12,
			'pricecolor' => '#fff',
			'priceper' => '',
			'button' => '',
			'buttonurl' => '',
			'buttonsize' => 18,
			'buttonbold' => false,
			'buttontextcolor' => '#fff',
			'buttonbgcolor' => '',
			'buttonbdsize' => 4,
			'buttonfill' => false,
			'buttonext' => false, 
			'row1' => '',
			'row2' => '',
			'row3' => '',
			'row4' => '',
			'row5' => '',
			'row6' => '',
			'row7' => '',
			'row8' => '',
			'row9' => '',
			'row10' => '',
			'row11' => '',
			'row12' => '',
			'row13' => '',
			'row14' => '',
			'row15' => '',
			'row16' => '',
			'row17' => '',
			'row18' => '',
			'row19' => '',
			'row20' => '',
			'keycolor' => '#82DACA',
			'label' => '',
			'labelcolor' => '#fff',
			'labelbgcolor' => '',
			'boxshadow' => false,
			'border' => false,
			'bdcolor' => '',
			'bgcolor' => '',
			'fontcolor' => '',
			'main' => false,
			'class' => '',
			'style' => ''
		), $atts));

		// ************
		// CSS start
		//*************
		// title
		$titlesize = str_replace('px', '', $titlesize);
		$titlesize = mb_convert_kana($titlesize);
		$titlesize = 'font-size:'.$titlesize.'px;';
		$titlebold = (bool)$titlebold ? ' bold' : '';
		$titlecolor = !empty($titlecolor) ? 'color:'.$titlecolor.';' : '';
		// Title caption
		$titlecaptionsize = str_replace('px', '', $titlecaptionsize);
		$titlecaptionsize = mb_convert_kana($titlecaptionsize);
		$titlecaptionsize = 'font-size:'.$titlecaptionsize.'px;';
		// Price
		$pricesize = str_replace('px', '', $pricesize);
		$pricesize = mb_convert_kana($pricesize);
		$pricesize = 'font-size:'.$pricesize.'px;';
		$pricebold = (bool)$pricebold ? ' bold' : '';
		$pricecolor = !empty($pricecolor) ? 'color:'.$pricecolor.';' : '';
		$pricecaptionsize = str_replace('px', '', $pricecaptionsize);
		$pricecaptionsize = mb_convert_kana($pricecaptionsize);
		$pricecaptionsize = 'font-size:'.$pricecaptionsize.'px;';
		// Button
		$buttonsize = str_replace('px', '', $buttonsize);
		$buttonsize = mb_convert_kana($buttonsize);
		$buttonsize = 'font-size:'.$buttonsize.'px;';
		$buttonbold = (bool)$buttonbold ? ' bold' : '';
		$buttontextcolor = !empty($buttontextcolor) ? 'color:'.$buttontextcolor.';' : '';
		$buttonbgcolor = !empty($buttonbgcolor) ? 'background-color:'.$buttonbgcolor.';' : 'background-color:'.$keycolor.';';

		if ((bool)$buttonfill) {
			$buttonfill = ' btnfill'; 
			$buttonbdsize = '';
		} else {
			$buttonfill = '';
			$buttonbdsize = str_replace('px', '', $buttonbdsize);
			$buttonbdsize = mb_convert_kana($buttonbdsize);
			$buttonbdsize = !empty($buttonbdsize) ? 'border-radius:'.$buttonbdsize.'px;' : '';
		}
	
		// label
		if (!empty($label)) {
			if (!empty($labelcolor)) {
				$labelcolor = 'color:'.$labelcolor.';';
			} else {
				$labelcolor = '#fff';
			}
			if (!empty($labelbgcolor)){
				$labelbgcolor = 'background-color:'.$labelbgcolor.';';
			} else{
				if (!empty($keycolor)) {
					$labelbgdarken = dp_sc_pi_darkenColor($keycolor, 30);
				} else {
					$labelbgdarken = '#999';
				}
				$labelbgcolor = 'background-color:rgba('.$labelbgdarken[0].','.$labelbgdarken[1].','.$labelbgdarken[2].',1);';
			}
			$label = '<div class="lblbox"><div class="plbl" style="'.$labelcolor.$labelbgcolor.'">'.$label.'</div></div>';
		} else {
			$label = '';
		}

		// Key color
		$keycolortitlebg = '';
		if (!empty($keycolor)) {
			$keycolorbg = 'background-color:'.$keycolor.';';
			$keycolortitlebg = dp_sc_pi_darkenColor($keycolor, 12);
			$keycolortitlebg = 'background-color:rgba('.$keycolortitlebg[0].','.$keycolortitlebg[1].','.$keycolortitlebg[2].',1);';
		} else {
			$keycolorbg = '';
			$keycolordarken = '';
		}

		// box shadow
		$boxshadow = (bool)$boxshadow ? ' bshadow' : '';

		// **************
		// ul css
		// **************
		// Main ul colors
		$fontcolor = !empty($fontcolor) ? 'color:'.$fontcolor.';' : '';
		$bgcolor = !empty($bgcolor) ? 'background-color:'.$bgcolor.';' : '';
		// border
		if ((bool)$border) {
			$border = (bool)$border ? ' bordered' : '';
			if (!empty($bdcolor)) {
				$bdcolor = 'border-color:'.$bdcolor.';';
			} else {
				$bdcolor = 'border-color:rgba(0,0,0,0.12);';
			}
		} else {
			$border = '';
			$bdcolor = '';
		}
		$ulstyle = '';
		if (!empty($fontcolor) || !empty($bgcolor) || !empty($bdcolor)){
			$ulstyle = ' style="'.$fontcolor.$bgcolor.$bdcolor.'"';
		}
		// Emphasis mode
		$main = (bool)$main ? ' main' : '';
		// ************
		// CSS end
		//*************

		$class = !empty($class) ? ' '.$class : '';
		$class .= $main;
		$style = !empty($style) ? ' style="'.$style.'"' : '';

		// Main title
		$titlecaption = !empty($titlecaption) ? '<p class="caption" style="'.$titlecaptionsize.'">'.$titlecaption.'</p>' : '';
		$title = !empty($title) ? '<li class="titlerow" style="'.$keycolortitlebg.$titlecolor.'"><h3 class="title'.$titlebold.'" style="'.$titlesize.'">'.$title.'</h3>'.$titlecaption.'</li>' : '';

		// Price
		$pricecaption = !empty($pricecaption) ? '<p class="caption" style="'.$pricecaptionsize.'">'.$pricecaption.'</p>' : '';
		$priceper = !empty($priceper) ? '<span class="per">'.$priceper.'</span>' : '';
		$price = !empty($price) ? '<li class="pricerow" style="'.$keycolorbg.$pricecolor.'"><p class="price'.$pricebold.'" style="'.$pricesize.'">'.$price.$priceper.'</p>'.$pricecaption.'</li>' : '';

		// button
		$btn_ext = '';
		if (!empty($button) && !empty($buttonurl)) {
			if ((bool)$buttonext) {
			$btn_ext = ' target="_blank"';
			}
			$button = '<li class="btnrow'.$buttonfill.'"><a href="'.$buttonurl.'" class="pbtn'.$buttonbold.$buttonfill.'" style="'.$buttontextcolor.$buttonbgcolor.$buttonsize.$buttonbdsize.'"'.$btn_ext.'>'.$button.'</a></li>';
		}

		$rows = '';
		$arr_row = array(
			'row1' => $row1,
			'row2' => $row2,
			'row3' => $row3,
			'row4' => $row4,
			'row5' => $row5,
			'row6' => $row6,
			'row7' => $row7,
			'row8' => $row8,
			'row9' => $row9,
			'row10' => $row10,
			'row11' => $row11,
			'row12' => $row12,
			'row13' => $row13,
			'row14' => $row14,
			'row15' => $row15,
			'row16' => $row16,
			'row17' => $row17,
			'row18' => $row18,
			'row19' => $row19,
			'row20' => $row20
		);
		foreach ($arr_row as $key => $row) {
			if (empty($row)) break;
			$rows .= '<li class="'.$key.'">'.$row.'</li>';
		}
		$rows = '<div class="dp_ptable_item dp_sc_fl_item'.$class.'"'.$style.'><ul class="ptable_list'.$border.$boxshadow.'"'.$ulstyle.'>'.$title.$price.$rows.$button.'</ul>'.$label.'</div>';

		return $rows;
	}


/****************************************************************
* Skill bars
****************************************************************/
function dp_sc_skill_bar($atts, $content = null) {
	if (is_admin()) return;
	if (!$content) return;
	if (!class_exists('DP_SC_PLUGIN')) return;
	if (!function_exists('dp_sc_plugin_status')) return;
	// Status check
	$status = dp_sc_plugin_status();
	if (!$status) return;

	extract(shortcode_atts(array(
		'class' => '',
		'style' => '',
		'plx' => ''
	), $atts));

	// Target theme
	$dp_theme_key = defined('DP_THEME_KEY') ? ' '.DP_THEME_KEY : '';

	// For Parallax theme
	$plx = !empty($plx) ? ' data-sr="'.$plx.'"' : '';

	// Get table list
	$content 	= do_shortcode($content);

	$style 	= !empty($style) ? ' style="'.$style.'"' : '';
	$class 	= !empty($class) ? ' '.$class.$dp_theme_key : $dp_theme_key;

	$code = '<div class="dp_sc_skillbars'.$class.'"'.$style.$plx.'>'.$content.'</div>';

	return $code;
}
	function dp_sc_skill_bar_item($atts, $content = null) {
		extract(shortcode_atts(array(
			'title' => 'Skill',
			'titlesize' => 12,
			'titlebold' => true,
			'titlecolor' => '#fff',
			'ratebarcolor' => '#82daca',
			'rate' => 50,
			'ratetext' => '',
			'ratesize' => 12,
			'ratecolor' => '#666',
			'bgcolor' => 'rgba(0,0,0,0.06)',
			'height' => 40,
			'flat' => false,
			'class'=> ''
		), $atts));

		// title params
		$titlesize = str_replace('px', '', $titlesize);
		$titlesize = mb_convert_kana($titlesize);
		$titlesize = 'font-size:'.$titlesize.'px;';
		$titlebold = !empty($titlebold) && $titlebold !== 'false' ? ' bold' : '';
		$titlecolor = !empty($titlecolor) ? 'color:'.$titlecolor.';' : '';
		$ratebarcolor = !empty($ratebarcolor) ? 'background-color:'.$ratebarcolor.';' : '';

		// Percentage params
		$rate = !empty($rate) ? $rate : '0';
		$rate = str_replace('%', '', $rate);
		$rate = mb_convert_kana($rate);
		$ratesize = str_replace('px', '', $ratesize);
		$ratesize = mb_convert_kana($ratesize);
		$ratesize = 'font-size:'.$ratesize.'px;';
		$ratecolor = !empty($ratecolor) ? 'color:'.$ratecolor.';' : '';

		// Box shadow
		$flat = !(bool)$flat ? ' shadow' : '';

		// background color
		$bgcolor = !empty($bgcolor) ? 'background-color:'.$bgcolor.';' : '';
		// bar height
		$height = str_replace('px', '', $height);
		$height = mb_convert_kana($height);
		if (!empty($height)) {
			$lineheight = 'line-height:'.$height.'px;';
			$height = 'height:'.$height.'px;';
		} else {
			$height = '';
		}
		$skillbarstyle = '';
		if (!empty($bgcolor) || !empty($height)) {
			$skillbarstyle = ' style="'.$height.$lineheight.$bgcolor.'"';
		}

		$class = !empty($class) ? ' '.$class.$flat : $flat;

		// Main title
		$title = '<div class="sbar bar" style="'.$titlesize.$titlecolor.$ratebarcolor.$lineheight.'"><span class="title'.$titlebold.'">'.$title.'</span></div>';
		// Rate
		$ratetext = !empty($ratetext) ? $ratetext : $rate.'%';
		$ratetext = '<div class="sbar rate" style="'.$ratesize.$ratecolor.$lineheight.'">'.$ratetext.'</div>';

		$code = '<div class="skillbar'.$class.'" data-percent="'.$rate.'%"'.$skillbarstyle.'>'.$title.$ratetext.'</div>';

		return $code;
	}

/****************************************************************
* Skill circular bar
****************************************************************/
function dp_sc_skill_circular_bar($atts, $content = null) {
	if (is_admin()) return;
	if (!$content) return;
	if (!class_exists('DP_SC_PLUGIN')) return;
	if (!function_exists('dp_sc_plugin_status')) return;
	// Status check
	$status = dp_sc_plugin_status();
	if (!$status) return;

	extract(shortcode_atts(array(
		'class' => '',
		'style' => '',
		'plx' => ''
	), $atts));

	// Target theme
	$dp_theme_key = defined('DP_THEME_KEY') ? ' '.DP_THEME_KEY : '';

	// For Parallax theme
	$plx = !empty($plx) ? ' data-sr="'.$plx.'"' : '';

	// Get table list
	$content 	= do_shortcode($content);

	$style 	= !empty($style) ? ' style="'.$style.'"' : '';
	$class 	= !empty($class) ? ' '.$class.$dp_theme_key : $dp_theme_key;
	$class .= ' justify_cen flex_children';

	$code = '<div class="dp_sc_skillcircles dp_sc_fl_box'.$class.'"'.$style.$plx.'>'.$content.'</div>';

	return $code;
}
	function dp_sc_skill_circular_bar_item($atts, $content = null) {
		extract(shortcode_atts(array(
			'caption' => '',
			'captionsize' => 13,
			'captionbold' => false,
			'rate' => 50,
			'ratetext' => '',
			'ratesize' => 32,
			'ratecolor' => '',
			'ratebold' => false,
			'barcolor' => '',
			'barcolor2' => '',
			'blankcolor' => 'rgba(0,0,0,0.1)',
			'barwidth' => '',
			'size' => 120,
			'duration' => '',
			'startrate' => 0,
			'startangle' => null,
			'reverse' => false,
			'class'=> ''
		), $atts));

		if (empty($rate)) return;

		// Percentage params
		$rate = str_replace('%', '', $rate);
		$rate = mb_convert_kana($rate);
		if (!is_numeric($rate)) return;
		$rate = $rate / 100;
		$ratesize = str_replace('px', '', $ratesize);
		$ratesize = mb_convert_kana($ratesize);
		$ratesize = 'font-size:'.$ratesize.'px;';
		$ratebold = (bool)$ratebold ? 'font-weight:bold;' : '';
		$ratecolor = !empty($ratecolor) ? 'color:'.$ratecolor.';' : '';

		// caption params
		$caption_code = '';
		if (!empty($caption)) {
			$captionsize = str_replace('px', '', $captionsize);
			$captionsize = mb_convert_kana($captionsize);
			$captionsize = 'font-size:'.$captionsize.'px;';
			$captionbold = !empty($captionbold) ? 'font-weight:bold;' : '';
			// Main caption
			$caption_code = '<div class="caption" style="'.$captionsize.$captionbold.'">'.$caption.'</div>';
		}

		// Chart size
		$size = str_replace('px', '', $size);
		$size = mb_convert_kana($size);
		$size = is_numeric($size) ? $size : 120;
		$ratelineheight = 'line-height:'.$size.'px;';

		// Bar color
		$barcolor = !empty($barcolor) ? ' data-fill-color="'.$barcolor .'"' : '';
		$barcolor2 = !empty($barcolor2) ? ' data-fill-color2="'.$barcolor2 .'"' : '';

		// Blank bar color
		$blankcolor = !empty($blankcolor) ? ' data-blank-color="'.$blankcolor .'"' : '';

		// Duration
		$duration = !empty($duration) ? ' data-duration="'.$duration .'"' : '';

		// Start rate
		$startrate_data = '';
		if (!empty($startrate)) {
			$startrate = str_replace('%', '', $startrate);
			$startrate = mb_convert_kana($startrate);
			$startrate = is_numeric($startrate) ? $startrate : 0;
			$startrate = $startrate / 100;
			$startrate_data = ' data-start-rate="'.$startrate .'"';
		}

		// Bar width
		$barwidth_data = '';
		if (!empty($barwidth)) {
			$barwidth = str_replace('%', '', $barwidth);
			$barwidth = mb_convert_kana($barwidth);
			$barwidth = is_numeric($barwidth) ? $barwidth : 'auto';
			$barwidth_data = ' data-thickness="'.$barwidth.'"';
		}

		// Reverse animation
		$reverse = !empty($reverse) && $reverse !== 'false' ? ' data-reverse="true"' : '';

		// Start angle position
		$startangle = ctype_digit($startangle) ? ' data-start-angle="'.$startangle.'"' : '';

		// Classes
		$class = !empty($class) ? ' '.$class : '';

		// Rate
		if (!empty($ratetext)) {
			$ratetext = $ratetext;
		} else {
			$ratetext = '<span class="rate-num"></span><span class="percent">%</span>';	
		}
		$ratetext = '<div class="srate" style="'.$ratesize.$ratecolor.$ratebold.$ratelineheight.'">'.$ratetext.'</div>';

		$code = '<div class="scircle dp_sc_fl_item'.$class.'" data-rate="'.$rate.'" data-size="'.$size.'"'.$barcolor.$barcolor2.$blankcolor.$duration.$startangle.$barwidth_data.$startrate_data.$reverse.'">'.$ratetext.$caption_code.'</div>';

		return $code;
	}

/****************************************************************
* Count up
****************************************************************/
function dp_sc_count_up($atts, $content = null) {
	if (is_admin()) return;
	if (!$content) return;
	if (!class_exists('DP_SC_PLUGIN')) return;
	if (!function_exists('dp_sc_plugin_status')) return;
	// Status check
	$status = dp_sc_plugin_status();
	if (!$status) return;

	extract(shortcode_atts(array(
		'inline' => false,
		'class' => '',
		'style'=> '',
		'plx' => ''
	), $atts));

	// Target theme
	$dp_theme_key = defined('DP_THEME_KEY') ? ' '.DP_THEME_KEY : '';

	// inline block
	$inline = !empty($inline) ? ' inline' : '';

	// For Parallax theme
	$plx = !empty($plx) ? ' data-sr="'.$plx.'"' : '';

	// Hover fx
	if (empty($hoverfx) || $hoverfx === 'false' || $hoverfx === '0') {
		$hoverfx = '';
	} else {
		$hoverfx = ' hoverfx';
	}

	// Get table list
	$content 	= do_shortcode($content);

	$style 	= !empty($style) ? ' style="'.$style.'"' : '';
	$class 	= !empty($class) ? ' '.$class.$inline.$hoverfx.$dp_theme_key : $inline.$hoverfx.$dp_theme_key;
	$class .= ' justify_cen flex_children';

	$code = '<div class="dp_sc_countup dp_sc_fl_box'.$class.'"'.$style.$plx.'>'.$content.'</div>';

	return $code;
}
	function dp_sc_count_up_item($atts, $content = null) {
		extract(shortcode_atts(array(
			'to' => 100,
			'tosize' => 40,
			'tobold' => true,
			'tocolor' => '',
			'caption' => '',
			'captionsize' => 13,
			'captionbold' => true,
			'captioncolor' => '',
			'captionpos' => 'bottom', // 1 = top, 2 = right, 3 = bottom, 4 = left
			'bgcolor' => '',
			'bdwidth' => 0,
			'bdcolor' => '#eee',
			'bdradius' => 3,
			'decimal' => 0,
			'duration' => 1,
			'maxstep' => null,
			'minstep' => null,
			'class'=> ''
		), $atts));

		$to = mb_convert_kana($to);
		if ( empty($to) ) return;
		$to = ' data-count="'.$to.'"';

		// max count number params
		$tosize = str_replace('px', '', $tosize);
		$tosize = mb_convert_kana($tosize);
		$tosize = 'font-size:'.$tosize.'px;';
		$tobold = !empty($tobold) && $tobold !== 'false' ? 'font-weight:bold;' : 'font-weight:normal;';
		$tocolor = !empty($tocolor) ? 'color:'.$tocolor.';' : '';

		// max count number
		$tohtml = '<span class="count_num" style="'.$tosize.$tobold.$tocolor.'">0</span>';	

		// caption params
		if (!empty($caption)) {
			if (!empty($captionsize)) {
				$captionsize = str_replace('px', '', $captionsize);
				$captionsize = mb_convert_kana($captionsize);
				$captionsize = 'font-size:'.$captionsize.'px;';
			} else {
				$captionsize = '';
			}
			$captioncolor = !empty($captioncolor) ? 'color:'.$captioncolor.';' : '';
			$captionbold = !empty($captionbold) && $captionbold !== 'false' ? 'font-weight:bold;' : 'font-weight:normal;';
			$caption = '<span class="caption" style="'.$captionsize.$captioncolor.$captionbold.'">'.$caption.'</span>';

			switch ($captionpos) {
				case 1:
				case 'top':
					$captionpos = ' cap_t';
					$tohtml = $caption.$tohtml;
					break;
				case 2:
				case 'right':
					$captionpos = ' cap_r';
					$tohtml = $tohtml.$caption;
					break;
				case 4:
				case 'left':
					$captionpos = ' cap_l';
					$tohtml = $caption.$tohtml;
					break;
				default:	// Bottom
					$captionpos = ' cap_b';
					$tohtml = $tohtml.$caption;
					break;
			}
		} else {
			$caption = '';
			$captionpos = '';
		}

		// background color
		$bgcolor = !empty($bgcolor) ? 'background-color:'.$bgcolor.';' : '';
		// border
		$border = '';
		if (!empty($bdwidth) && $bdwidth != 0) {
			$bdwidth = str_replace('px', '', $bdwidth);
			$bdwidth = mb_convert_kana($bdwidth);
			$border = 'border:'.$bdwidth.'px solid '.$bdcolor.';';
			if (!empty($bdradius)) {
				$bdradius = str_replace('px', '', $bdradius);
				$bdradius = mb_convert_kana($bdradius);
				$bdradius = 'border-radius:'.$bdradius.'px;';
			} else {
				$bdradius = '';
			}
			$border .= $bdradius;
		}

		// counter css
		$counterstyle = !empty($bgcolor) || !empty($border) ? ' style="'.$bgcolor.$border.'";' : '';

		// decimals
		$decimal = ctype_digit($decimal) ? ' data-decimal="'.$decimal.'"' : '';
		$duration = ctype_digit($duration) ? ' data-duration="'.$duration.'"' : '';
		$maxstep = ctype_digit($maxstep) ? ' data-maxstep="'.$maxstep.'"' : '';
		$minstep = ctype_digit($minstep) ? ' data-minstep="'.$minstep.'"' : '';

		$class = !empty($class) ? ' '.$class : '';

		$tohtml = '<div class="count_wrap"'.$counterstyle.'>'.$tohtml.'</div>';

		// HTML
		$code = '<div class="counter dp_sc_fl_item'.$captionpos.$class.'"'.$to.$decimal.$duration.$minstep.$maxstep.'>'.$tohtml.'</div>';

		return $code;
	}

/****************************************************************
* Cross Reference table
****************************************************************/
function dp_sc_cross_reference_table($atts, $content = null) {
	if (is_admin()) return;
	if (!$content) return;
	if (!class_exists('DP_SC_PLUGIN')) return;
	if (!function_exists('dp_sc_plugin_status')) return;
	// Status check
	$status = dp_sc_plugin_status();
	if (!$status) return;

	extract(shortcode_atts(array(
		'column'=> '',
		'fontsize' => '',
		'hdfontcolor' => '',
		'hdbgcolor' => '',
		'hdalign' => '', // right, left(*default), center
		'hdtitle' => '',
		'hdcell1' => '',
		'hdcell2' => '',
		'hdcell3' => '',
		'hdcell4' => '',
		'hdcell5' => '',
		'hdcell6' => '',
		'hdcell7' => '',
		'hdcell8' => '',
		'hdcell9' => '',
		'hdcell10' => '',
		'hdcell11' => '',
		'hdcell12' => '',
		'hdcell13' => '',
		'hdcell14' => '',
		'hdcell15' => '',
		'hdcell16' => '',
		'hdcell17' => '',
		'hdcell18' => '',
		'hdcell19' => '',
		'hdcell20' => '',
		'id' => '',
		'class' => '',
		'style' => '',
		'plx' => ''
	), $atts));

	if (empty($column) || !ctype_digit($column)) turn;
	if ( (int)$column < 3 || (int)$column > 8 ) return;
 	$column = ' colnum'.$column;

	$code = $area_font_size = $hd_style = '';

	// Target theme
	$dp_theme_key = defined('DP_THEME_KEY') ? ' '.DP_THEME_KEY : '';

	// For Parallax theme
	$plx = !empty($plx) ? ' data-sr="'.$plx.'"' : '';

	if (!empty($fontsize)) {
		$fontsize = str_replace('px', '', $fontsize);
		$fontsize = mb_convert_kana($fontsize);
		$fontsize = 'font-size:'.$fontsize.'px;';
	}
	$hdfontcolor = !empty($hdfontcolor) ? 'color:'.$hdfontcolor.';' : '';
	$hdbgcolor = !empty($hdbgcolor) ? 'background-color:'.$hdbgcolor.';' : '';
	switch($hdalign) {
			case 'r':
			case 'right':
				$hdalign = 'text-align:right;';
				break;
			case 'c':
			case 'center':
				$hdalign = 'text-align:center;';
				break;
		}
	if (!empty($hdbgcolor) || !empty($hdfontcolor) || !empty($hdalign) ) {
		$hd_style = ' style="'.$hdbgcolor.$hdfontcolor.$hdalign.'"';
	}

	if (!empty($fontsize) || !empty($style)) {
		$style 	= ' style="'.$fontsize.$style.'"';
	}

	// Main section attributes
	$id = !empty($id) ? ' id="'.$id.'"' : '';
	$class 	= !empty($class) ? ' '.$class : '';
	$class 	.= $column.$dp_theme_key;

	// Cells
	$arr_hdcell = array(
		'hdcell1' => $hdcell1,
		'hdcell2' => $hdcell2,
		'hdcell3' => $hdcell3,
		'hdcell4' => $hdcell4,
		'hdcell5' => $hdcell5,
		'hdcell6' => $hdcell6,
		'hdcell7' => $hdcell7,
		'hdcell8' => $hdcell8,
		'hdcell9' => $hdcell9,
		'hdcell10' => $hdcell10,
		'hdcell11' => $hdcell11,
		'hdcell12' => $hdcell12,
		'hdcell13' => $hdcell13,
		'hdcell14' => $hdcell14,
		'hdcell15' => $hdcell15,
		'hdcell16' => $hdcell16,
		'hdcell17' => $hdcell17,
		'hdcell18' => $hdcell18,
		'hdcell19' => $hdcell19,
		'hdcell20' => $hdcell20
	);
	foreach ($arr_hdcell as $key => $hdcell) {
		if (empty($hdcell)) break;
		$code .= '<li class="'.$key.'">'.$hdcell.'</li>';
	}

	$hdtitle =  !empty($hdtitle) ? '<div class="crtable_title hd">'.$hdtitle.'</div>' : '';

	$code = '<div class="crtable_col hd"'.$hd_style.'>'.$hdtitle.'<ul class="crtable_ul hd">'.$code.'</ul></div>';

	// Get table list
	$content 	= do_shortcode($content);

	$code = '<div'.$id.' class="dp_sc_crtable icon-right-open'.$class.'"'.$style.$plx.'>'.$code.'<div class="crtable_wrapper'.$class.'"><div class="crtable_container">'.$content.'</div></div></div>';

	return $code;
}
	function dp_sc_cross_reference_table_col($atts) {
		extract(shortcode_atts(array(
			'title' => '',
			'titlecolor' => '',
			'titlebgcolor' => '',
			'fontcolor' => '',
			'bgcolor' => '',
			'align' => '', // right, left, center(*default)
			'cell1' => '',
			'cell2' => '',
			'cell3' => '',
			'cell4' => '',
			'cell5' => '',
			'cell6' => '',
			'cell7' => '',
			'cell8' => '',
			'cell9' => '',
			'cell10' => '',
			'cell11' => '',
			'cell12' => '',
			'cell13' => '',
			'cell14' => '',
			'cell15' => '',
			'cell16' => '',
			'cell17' => '',
			'cell18' => '',
			'cell19' => '',
			'cell20' => '',
			'class' => '',
			'style' => ''
		), $atts));

		$title_style = $cells = '';

		$titlecolor = !empty($titlecolor) ? 'color:'.$titlecolor.';' : '';
		$titlebgcolor = !empty($titlebgcolor) ? 'background-color:'.$titlebgcolor.';' : '';
		if (!empty($titlecolor) || !empty($titlebgcolor) ) {
			$title_style = ' style="'.$titlecolor.$titlebgcolor.'"';
		}
		$title =  !empty($title) ? '<div class="crtable_title"'.$title_style.'>'.$title.'</div>' : '';

		$fontcolor = !empty($fontcolor) ? 'color:'.$fontcolor.';' : '';
		$bgcolor = !empty($bgcolor) ? 'background-color:'.$bgcolor.';' : '';
		switch($align) {
			case 'r':
			case 'right':
				$align = 'text-align:right;';
				break;
			case 'l':
			case 'left':
				$align = 'text-align:left;';
				break;
		}
		if (!empty($fontcolor) || !empty($bgcolor) || !empty($align) || !empty($style)) {
			$style = ' style="'.$fontcolor.$bgcolor.$align.$style.'"';
		}

		$class = !empty($class) ? ' '.$class : '';

		$arr_cell = array(
			'cell1' => $cell1,
			'cell2' => $cell2,
			'cell3' => $cell3,
			'cell4' => $cell4,
			'cell5' => $cell5,
			'cell6' => $cell6,
			'cell7' => $cell7,
			'cell8' => $cell8,
			'cell9' => $cell9,
			'cell10' => $cell10,
			'cell11' => $cell11,
			'cell12' => $cell12,
			'cell13' => $cell13,
			'cell14' => $cell14,
			'cell15' => $cell15,
			'cell16' => $cell16,
			'cell17' => $cell17,
			'cell18' => $cell18,
			'cell19' => $cell19,
			'cell20' => $cell20
		);
		foreach ($arr_cell as $key => $cell) {
			if (empty($cell)) break;
			$cells .= '<li class="'.$key.'">'.$cell.'</li>';
		}
		$cells = '<div class="crtable_col'.$class.'"'.$style.'>'.$title.'<ul class="crtable_ul">'.$cells.'</ul></div>';

		return $cells;
	}

/****************************************************************
* FAQ List
****************************************************************/
function dp_sc_faq($atts, $content = null) {
	if (is_admin()) return;
	if (!$content) return;
	if (!class_exists('DP_SC_PLUGIN')) return;
	if (!function_exists('dp_sc_plugin_status')) return;
	// Status check
	$status = dp_sc_plugin_status();
	if (!$status) return;

	/**
	 * cat[n] = 'category_title,category_slug'
	 */
	extract(shortcode_atts(array(
		'catcolor' => '',
		'catbgcolor' => '',
		'cat1' => '',
		'cat2' => '',
		'cat3' => '',
		'cat4' => '',
		'cat5' => '',
		'cat6' => '',
		'cat7' => '',
		'cat8' => '',
		'cat9' => '',
		'cat10' => '',
		'cat11' => '',
		'cat12' => '',
		'cat13' => '',
		'cat14' => '',
		'cat15' => '',
		'cat16' => '',
		'cat17' => '',
		'cat18' => '',
		'cat19' => '',
		'cat20' => '',
		'id' => '',
		'class' => '',
		'style' => '',
		'plx' => ''
	), $atts));

	$code = $cat_style = $cat_code = $list_code = '';
	$arr_ul = array();

	// Target theme
	$dp_theme_key = defined('DP_THEME_KEY') ? ' '.DP_THEME_KEY : '';

	// For Parallax theme
	$plx = !empty($plx) ? ' data-sr="'.$plx.'"' : '';

	// Main section attributes
	$id = !empty($id) ? ' id="'.$id.'"' : '';
	$class 	= !empty($class) ? ' '.$class : '';
	$class .= $dp_theme_key;

	$delim = '[//+++//]';

	$catcolor = !empty($catcolor) ? 'color:'.$catcolor.';' : '';
	$catbgcolor = !empty($catbgcolor) ? 'background-color:'.$catbgcolor.';' : '';
	if (!empty($catcolor) || !empty($catbgcolor)) {
		$cat_style = ' style="'.$catcolor.$catbgcolor.'"';
	}

	if (!empty($style)) {
		$style = ' style="'.$style.'"';
	}

	$cat1 = !empty($cat1) ? explode(',',$cat1) : array();
	$cat2 = !empty($cat2) ? explode(',',$cat2) : array();
	$cat3 = !empty($cat3) ? explode(',',$cat3) : array();
	$cat4 = !empty($cat4) ? explode(',',$cat4) : array();
	$cat5 = !empty($cat5) ? explode(',',$cat5) : array();
	$cat6 = !empty($cat6) ? explode(',',$cat6) : array();
	$cat7 = !empty($cat7) ? explode(',',$cat7) : array();
	$cat8 = !empty($cat8) ? explode(',',$cat8) : array();
	$cat9 = !empty($cat9) ? explode(',',$cat9) : array();
	$cat10 = !empty($cat10) ? explode(',',$cat10) : array();
	$cat11 = !empty($cat11) ? explode(',',$cat11) : array();
	$cat12 = !empty($cat12) ? explode(',',$cat12) : array();
	$cat13 = !empty($cat13) ? explode(',',$cat13) : array();
	$cat14 = !empty($cat14) ? explode(',',$cat14) : array();
	$cat15 = !empty($cat15) ? explode(',',$cat15) : array();
	$cat16 = !empty($cat16) ? explode(',',$cat16) : array();
	$cat17 = !empty($cat17) ? explode(',',$cat17) : array();
	$cat18 = !empty($cat18) ? explode(',',$cat18) : array();
	$cat19 = !empty($cat19) ? explode(',',$cat19) : array();
	$cat20 = !empty($cat20) ? explode(',',$cat20) : array();

	$arr_cat = array(
		'cat1' => $cat1,
		'cat2' => $cat2,
		'cat3' => $cat3,
		'cat4' => $cat4,
		'cat5' => $cat5,
		'cat6' => $cat6,
		'cat7' => $cat7,
		'cat8' => $cat8,
		'cat9' => $cat9,
		'cat10' => $cat10,
		'cat11' => $cat11,
		'cat12' => $cat12,
		'cat13' => $cat13,
		'cat14' => $cat14,
		'cat15' => $cat15,
		'cat16' => $cat16,
		'cat17' => $cat17,
		'cat18' => $cat18,
		'cat19' => $cat19,
		'cat20' => $cat20
	);

	// Get table list
	$content 	= do_shortcode($content);
	// Split the return
	$arr_faq = explode( $delim, $content);
	$arr_faq = array_filter($arr_faq, function($item){return !preg_match("/^(\r|\n)+$/",$item);});
	// Separate every 2 elements (Remove unnecessary last item).
	$arr_faq = array_chunk($arr_faq, 2);

	// Each faq list
	foreach ($arr_cat as $cat_key => $cat) {
		if (empty($cat) || !is_array($cat)) break;
		$class = '';
		if ($cat_key === 0) {
			$class = ' selected';
		}
		$cat_code .= '<li><a href="#dp_sc_faq_id-'.$cat[1].'" class="dp_sc_faq_cat icon-plus2'.$class.'"'.$cat_style.'><span>'.$cat[0].'</span></a></li>';
		foreach ($arr_faq as $faq_key => $faq) {
			if ($faq[1] === $cat[1]) {
				$arr_ul[$cat_key][] = $faq[0];
			}
		}
	}
	$cat_code = '<ul class="dp_sc_faq_cats dp_sc_faq_ul">'.$cat_code.'</ul>';

	// re-build HTML list
	foreach ($arr_ul as $ul_key => $ul_val) {
		foreach ($ul_val as $list_key => $list_val) {
			$list_code .= $list_val;
		}
		$code .= '<ul id="dp_sc_faq_id-'.$arr_cat[$ul_key][1].'" class="dp_sc_faq_item_ul dp_sc_faq_ul"><li class="dp_sc_faq_title">'.$arr_cat[$ul_key][0].'</li>'.$list_code.'</ul>';
		$list_code = '';
	}
	$code = '<div'.$id.' class="dp_sc_faq'.$class.'"'.$style.$plx.'>'.$cat_code.'<div class="dp_sc_faq_items">'.$code.'</div><div class="dp_sc_faq_mq_close icon-cross"></div></div>';

	return $code;
}
	function dp_sc_faq_item($atts, $content = null) {
		if (!$content) return;
		extract(shortcode_atts(array(
			'cat' => '',	// Category Slug
			'title' => '',
			'titlesize' => '',
			'titlecolor' => '',
			'titlebgcolor' => '',
			'fontsize' => '',
			'fontcolor' => '',
			'bgcolor' => '',
			'id' => '',
			'class' => '',
			'style' => ''
		), $atts));

		if (empty($title)) return;
		if (empty($cat)) return;

		$faq = $title_style = $content_style = '';
		$delim = '[//+++//]';

		if (!empty($titlesize)){
			$titlesize = str_replace('px', '', $titlesize);
			$titlesize = mb_convert_kana($titlesize);
			$titlesize = 'font-size:'.$titlesize.'px;';
		}
		$titlecolor = !empty($titlecolor) ? 'color:'.$titlecolor.';' : '';
		$titlebgcolor = !empty($titlebgcolor) ? 'background-color:'.$titlebgcolor.';' : '';
		if (!empty($titlesize) || !empty($titlecolor) || !empty($titlebgcolor)) {
			$title_style = ' style="'.$titlesize.$titlecolor.$titlebgcolor.'"';
		}

		if (!empty($fontsize)){
			$fontsize = str_replace('px', '', $fontsize);
			$fontsize = mb_convert_kana($fontsize);
			$fontsize = 'font-size:'.$fontsize.'px;';
		}
		$fontcolor = !empty($fontcolor) ? 'color:'.$fontcolor.';' : '';
		$bgcolor = !empty($bgcolor) ? 'background-color:'.$bgcolor.';' : '';
		if (!empty($fontsize) || !empty($fontcolor) || !empty($bgcolor)) {
			$content_style = ' style="'.$fontsize.$fontcolor.$bgcolor.'"';
		}

		if (!empty($style)) {
			$style = ' style="'.$style.'"';
		}

		$id = !empty($id) ? ' id="'.$id.'"' : '';
		$class = !empty($class) ? ' '.$class : '';

		$faq = '<li class="dp_sc_faq_item'.$class.'"'.$style.$id.'><div class="dp_sc_faq_trigger"'.$title_style.'>'.$title.'</div><div class="dp_sc_faq_content"'.$content_style.'>'.$content.'</div></li>';
		$code = $faq . $delim . $cat . $delim;
		return $code;
	}

/****************************************************************
* Gradient underline
****************************************************************/
function dp_sc_text_underline($atts) {
	if (is_admin()) return;

	extract(shortcode_atts(array(
		'text' => '',
		'color1' => '#FF7E8B',
		'color2' => '#FDCAD8',
		'size' => '',
		'bold' => false,
		'italic' => false,
		'thickness' => 'normal', // thin, thick, thcickest or 1(thin), 2(normal), 3(thick), 4(thickest),
		'hoverfx' => false,
		'id' => '',
		'class' => '',
		'style' => ''
	), $atts));
	if (empty($text)) return;
	if (empty($color1) || empty($color2)) return;

	$underline_color = '';
	$bold 	= (!empty($bold) || $bold != 0)  ? ' b' : '';
	$italic = (!empty($italic) || $italic != 0) ? ' i' : '';

	$class .= ' dp_sc_txtul';
	if ($bold || $italic || $class) {
		$class .= $bold.$italic;
	}
	if ((bool)$hoverfx) {
		$hoverfx = ' hoverfx';
	}
	$class = ' class="'.$class.$hoverfx.'"';

	// Thickness
	switch ($thickness) {
		case 1:
		case 'thin':
			$thickness = 'background-size:100% .2em;';
			break;
		case 3:
		case 'thick':
			$thickness = 'background-size:100% .4em;';
			break;
		case 4:
		case 'thickest':
			$thickness = 'background-size:100% .5em;';
			break;
		default:
			$thickness = 'background-size:100% .3em;';
			break;
	}

	// unserline color
	$underline_color = 'background-image:linear-gradient(120deg,'.$color1.' 0%,'.$color2.' 100%);';

	if (!empty($size)){
		$size = str_replace('px', '', $size);
		$size = mb_convert_kana($size);
		$size = 'font-size:'.$size.'px;';
	}
	$style = ' style="'.$thickness.$underline_color.$size.$style.'"';

	$id = !empty($id) ? ' id="'.$id.'"' : '';

	$code = <<<_EOD_
<span$id$class$style>$text</span>
_EOD_;

	return $code;
}

/****************************************************************
* Text revealing animation
****************************************************************/
function dp_sc_text_revealing($atts) {
	if (is_admin()) return;

	extract(shortcode_atts(array(
		'ltext' => '',
		'rtext' => '',
		'lsize' => '',
		'lcolor' => '',
		'lbold' => false,
		'litalic' => false,
		'rsize' => '',
		'rcolor' => '',
		'rbold' => false,
		'ritalic' => false,
		'fx' => 1,
		'time' => '',
		'easing' => '',
		'noloop' => '',
		'id' => '',
		'class' => '',
		'style' => ''
	), $atts));
	if (empty($ltext)) return;
	if (empty($rtext)) return;

	$rttxt1_style = $rttxt2_style = $anim_style1 = $anim_style2 = '';

	$fx = ' fx'.$fx;
	$lbold 	= ($lbold === 'true' || $lbold === '1')  ? ' b' : '';
	$litalic = ($litalic === 'true' || $litalic === '1') ? ' i' : '';
	$rbold 	= ($rbold === 'true' || $rbold === '1')  ? ' b' : '';
	$ritalic = ($ritalic === 'true' || $ritalic === '1') ? ' i' : '';
	$class = !empty($class) ? ' '.$class : '';

	$time = !empty($time) ? 'animation-duration:'.$time.'s;' : '';
	$easing = !empty($easing) ? 'animation-timing-function:'.$easing.';' : '';
	if (!empty($time) || !empty($easing)) {
		$anim_style1 = $time.$easing;
		$anim_style2 = ' style="'.$anim_style1.'"';
	}

	// Disable now
	$noloop = ($noloop === 'true' || $noloop === '1') ? ' noloop' : '';

	// left text
	$lcolor = !empty($lcolor) ? 'color:'.$lcolor.';' : '';
	if (!empty($lsize)){
		$lsize = str_replace('px', '', $lsize);
		$lsize = mb_convert_kana($lsize);
		$lsize = 'font-size:'.$lsize.'px;';
	}
	$rttxt1_style = ' style="'.$lsize.$lcolor.$anim_style1.'"';

	// right text
	$rcolor = !empty($rcolor) ? 'color:'.$rcolor.';' : '';
	if (!empty($rsize)){
		$rsize = str_replace('px', '', $rsize);
		$rsize = mb_convert_kana($rsize);
		$rsize = 'font-size:'.$rsize.'px;';
	}
	$rttxt2_style = ' style="'.$rsize.$rcolor.$anim_style1.'"';

	// style
	$style = !empty($style) ? ' style="'.$style.'"' : '';

	$id = !empty($id) ? ' id="'.$id.'"' : '';

	$code = <<<_EOD_
<div class="dp_sc_txtreveal$noloop$class"$id$style><div class="rtxt one$fx$lbold$litalic"$rttxt1_style>$ltext</div><div class="rtxt two$fx"$anim_style2><span class="inner$rbold$ritalic"$rttxt2_style>$rtext</span></div></div>
_EOD_;

	return $code;
}


/****************************************************************
* Flipping cards
****************************************************************/
function dp_sc_flip_card($atts, $content = null) {
	if (is_admin()) return;
	if (!$content) return;
	if (!class_exists('DP_SC_PLUGIN')) return;
	if (!function_exists('dp_sc_plugin_status')) return;
	// Status check
	$status = dp_sc_plugin_status();
	if (!$status) return;

	extract(shortcode_atts(array(
		'colnum' => 3,	// 3 upto 6
		'hoverfx' => 1, // 1:horizontal rotate, 2:vertical rotate, 3:3D horizontal rotate
		'txtshadow' => true,
		'boxshadow' => false, // true or 1,
		'flatbgcolor' => false,
		'bdradiuslevel' => 1, // 0:no radius, 1:3px, 2:6px, 3:12px, 4:24px, 5:50%
		'separater' => true,
		'separaterthick' => 2, // 1:1px, 2:2px, 3:3px, 4:4px
		'separaterblack' => false,
		'id' => '',
		'class' => '',
		'style' => '',
		'plx' => ''
	), $atts));

	if (empty($colnum) || !ctype_digit((string)$colnum)) return;
	$colnum = mb_convert_kana($colnum);
	$colnum = (int)$colnum;
	if ($colnum > 6 || $colnum < 3) return;

	// Target theme
	$dp_theme_key = defined('DP_THEME_KEY') ? ' '.DP_THEME_KEY : '';

	// For Parallax theme
	$plx = !empty($plx) ? ' data-sr="'.$plx.'"' : '';

	// Column number
	$colnum = ' colnum'.$colnum;
	// hover effect type
	$hoverfx = ' fx'.$hoverfx;
	// separeater
	$separater = ($separater === 'false' || $separater === '0' ) ? ' no-line' : '';
	$separaterthick = ' sprth'.$separaterthick;
	$separaterblack = ($separaterblack === 'true' || $separaterblack === '1' ) ? ' sprtblack' : '';
	// border radius level
	$bdradiuslevel = ' rdlv'.$bdradiuslevel;

	$txtshadow = ($txtshadow === '0' || $txtshadow === 'false') ? ' noshadow' : '';
	$boxshadow = ($boxshadow === '1' || $boxshadow === 'true') ? ' boxshadow' : '';
	$flatbgcolor = ($flatbgcolor === '1' || $flatbgcolor === 'true') ? ' flatbg' : '';

	$style 	= !empty($style) ? ' style="'.$style.'"' : '';
	$class 	= !empty($class) ? ' '.$class.$dp_theme_key : $dp_theme_key;

	$id = !empty($id) ? ' id="'.$id.'"' : '';

	$code = '<div class="dp_sc_flip_card'.$hoverfx.$colnum.$separater.$separaterthick.$separaterblack.$bdradiuslevel.$txtshadow.$boxshadow.$flatbgcolor.$class.'"'.$style.$plx.$id.'>'.$content.'</div>';

	return $code;
}
	function dp_sc_flip_card_item($atts, $content = null) {
		if (!$content) return;

		extract(shortcode_atts(array(
			'title' => '',
			'titlesize' => '',
			'titlebold' => false,
			'titleitalic' => false,
			'caption' => '',
			'captionsize' => '',
			'fronttxtcolor' => '',
			'backtxtcolor' => '',
			'frontimg' => '',
			'frontimgoverlay' => 1,	// 0:none, 1:black-32%, 2:black-54%, 3:white-36%, 4:white-66%
			'backimg' => '',
			'backimgoverlay' => 1,		// 0:none, 1:black-32%, 2:black-54%, 3:white-36%, 4:white-66%
			'frontbgcolor' => '',	// def: #BBDAED
			'backbgcolor' => '',	// def: #BBDAED
			'url' => '',
			'newwindow' => false,
			'class'=> '',
			'style' => '',
			'plx' => ''
		), $atts));

		$url_data = $url_flag = $front_style = $back_style = '';

		$content 	= do_shortcode($content);

		if (!empty($title)) {
			$titlesize = str_replace('px', '', $titlesize);
			$titlesize = !empty($titlesize) ? ' style="font-size:'.$titlesize.'px;"' : '';
			$titlebold = !empty($titlebold) ? ' bld' : '';
			$titleitalic = !empty($titleitalic) ? ' itl' : '';
		}

		$title = !empty($title) ? '<p class="fr-title'.$titlebold.$titleitalic.'"'.$titlesize.'>'.$title.'</p>' : '';

		// caption param
		if (!empty($caption)) {
			if (!empty($captionsize)){
				$captionsize = str_replace('px', '', $captionsize);
				$captionsize = mb_convert_kana($captionsize);
				$captionsize = ' style="font-size:'.$captionsize.'px;"';
			}
			// Main caption
			$caption = '<div class="caption"'.$captionsize.'>'.$caption.'</div>';
		}

		// target link
		if (!empty($url)){
			$url_flag = ' has-link';
			if (!empty($newwindow)){
				$url_data = ' data-link="'.$url.'" data-link-ext="true"';
			} else {
				$url_data = ' data-link="'.$url.'"';
			}
		}

		// Front font color
		if (!empty($fronttxtcolor)){
			$fronttxtcolor = 'color:'.$fronttxtcolor.';';
		}
		// Back font color
		if (!empty($backtxtcolor)){
			$backtxtcolor = 'color:'.$backtxtcolor.';';
		}

		// front bgcolor
		$frontbgcolor = !empty($frontbgcolor) ? 'background-color:'.$frontbgcolor.';' : '';
		// Back bgcolor
		$backbgcolor = !empty($backbgcolor) ? 'background-color:'.$backbgcolor.';' : '';

		// Front image
		if (!empty($frontimg)) {
			$frontimgoverlay = ' colorfilter'.$frontimgoverlay;
			$frontimg = 'background-image:url(\''.$frontimg.'\');';
		} else {
			$frontimgoverlay = '';
		}
		// Back image
		if (!empty($backimg)) {
			$backimgoverlay = ' colorfilter'.$backimgoverlay;
			$backimg = 'background-image:url(\''.$backimg.'\');';
		} else {
			$backimgoverlay = '';
		}

		// Front inline css
		if (!empty($fronttxtcolor) || !empty($frontbgcolor) || !empty($frontimg)){
			$front_style =' style="'.$fronttxtcolor.$frontbgcolor.$frontimg.'"';
		}
		// Back inline css
		if (!empty($backtxtcolor) || !empty($backbgcolor) || !empty($backimg)){
			$back_style =' style="'.$backtxtcolor.$backbgcolor.$backimg.'"';
		}

		// For Parallax theme
		$plx = !empty($plx) ? ' data-sr="'.$plx.'"' : '';

		// Classes
		$class = !empty($class) ? ' '.$class : '';
		// style
		$style 	= !empty($style) ? ' style="'.$style.'"' : '';

		$code = '<div class="card_item'.$class.'"'.$style.$url_data.$plx.'><div class="cntnr'.$url_flag.'"><div class="flipper front'.$frontimgoverlay.'"'.$front_style.'><div class="cinner">'.$title.$caption.'</div></div><div class="flipper back'.$backimgoverlay.'"'.$back_style.'><div class="cinner">'.$content.'</div></div></div></div>';

		return $code;
	}

/****************************************************************
* Caption box
****************************************************************/
function dp_sc_caption_box($atts, $content = null) {
	if (is_admin()) return;
	if (!$content) return;

	extract(shortcode_atts(array(
		'titleicon' => '',
		'titleiconsize' => '',
		'title' => '',
		'titlesize'  => '',
		'titlebold' => '',
		'titlecolor' => '',
		'titleitalic' => '',
		'titlepos' => 1, //1(left), 2(center), 3(right)
		'titlepattern' => 1, // or 2, 3
		'bdcolor' => '',
		'bdsize' => '',
		'bdstyle' => 1, // or 2(dashed), 3(dotted), 4(double)
		'bgcolor' => '',	// Disable in titlepattern is 2.
		'captioncolor' => '',
		'captionsize' => '',
		'id' => '',
		'class' => '',
		'style' => '',
		'plx' => ''
	), $atts));

	$title_style = $icon_style = $icon_flag = $titlepos_flag = $caption_style = $titlebgcolor = '';

	$titlebold 	= ($titlebold === '0' || $titlebold === 'false') ? '' : ' b';
	$titleitalic = ($titleitalic === '1' || $titleitalic === 'true') ? ' i' : '';

	if (!empty($titlesize)){
		$titlesize = str_replace('px', '', $titlesize);
		$titlesize = mb_convert_kana($titlesize);
		if (is_numeric($titlesize)) {
			$titlesize = 'font-size:'.$titlesize.'px;';
		} else {
			$titlesize = 'font-size:'.$titlesize.';';
		}
	}
	$titlecolor = !empty($titlecolor) ? 'color:'.$titlecolor.';' : '';
	// Title position
	switch ($titlepos) {
		case 2:
		case 'center':
			$titlepos = ' al-c';
			$titlepos_flag = ' tcenter';
			break;
		case 3:
		case 'right':
			$titlepos = ' al-r';
			$titlepos_flag = ' tright';
			break;
		default:
			$titlepos = ' al-l';
			$titlepos_flag = ' tleft';
			break;
	}

	// Title pattern
	if ($titlepattern === '2'){
		$titlepattern = ' pt2';
	} else if ($titlepattern === '3'){
		$titlepattern = ' pt3';
	} else {
		$titlepattern = ' pt1';
	}

	if (!empty($bdcolor)){
		if ($titlepattern === ' pt2'){
			$titlebgcolor = 'color:'.$bdcolor.';';
			if (!empty($titleicon)) {
				$icon_style = 'background-color:'.$bdcolor.';';
			}
		} else {
			$titlebgcolor = 'background-color:'.$bdcolor.';';
		}
		$bdcolor = 'border-color:'.$bdcolor.';';
	}
	if (!empty($bdsize)){
		$bdsize = str_replace('px', '', $bdsize);
		$bdsize = mb_convert_kana($bdsize);
		if (is_numeric($bdsize)) {
			$bdsize = 'border-width:'.$bdsize.'px;';
		} else {
			$bdsize = 'border-width:'.$bdsize.';';
		}
	}
	switch ($bdstyle) {
		case 2:
		case 'dashed':
			$bdstyle = ' bd-dashed';
			break;
		case 3:
		case 'dotted':
			$bdstyle = ' bd-dotted';
			break;
		case 4:
		case 'double':
			$bdstyle = ' bd-double';
			break;
		default:
			$bdstyle = '';
			break;
	}

	$captioncolor = !empty($captioncolor) ? 'color:'.$captioncolor.';' : '';
	if (!empty($captionsize)){
		$captionsize = str_replace('px', '', $captionsize);
		$captionsize = mb_convert_kana($captionsize);
		if (is_numeric($captionsize)) {
			$captionsize = 'font-size:'.$captionsize.'px;';
		} else {
			$captionsize = 'font-size:'.$captionsize.';';
		}
	}
	$bgcolor = !empty($bgcolor) ? 'background-color:'.$bgcolor.';' : '';

	// icon size
	if (!empty($titleiconsize)){
		$titleiconsize = str_replace('px', '', $titleiconsize);
		$titleiconsize = mb_convert_kana($titleiconsize);
		if (is_numeric($titleiconsize)) {
			$icon_style .= 'font-size:'.$titleiconsize.'px;';
		} else {
			$icon_style .= 'font-size:'.$titleiconsize.';';
		}
	}
	if (!empty($icon_style)){
		$icon_style = ' style="'.$icon_style.'"';
	}

	// icon
	if (!empty($titleicon)){
		$titleicon = '<i class="ctitle-icon '.$titleicon.$titlepattern.'"'.$icon_style.'></i>';
		$icon_flag = ' show-icon';
	} else {
		$titleicon = '';
	}

	// title
	if (!empty($title)){
		if (!empty($titlecolor) || !empty($titlesize) || !empty($titlebgcolor)){
			$title_style = ' style="'.$titlecolor.$titlesize.$titlebgcolor.'"';
		}
		$title = '<div class="ctitle_area'.$titlepos.$titlepattern.'"><p class="ctitle'.$titlebold.$titleitalic.$titlepos_flag.$titlepattern.'"'.$title_style.'>'.$titleicon.$title.'</p></div>';
	}
	// Caption
	if (!empty($captioncolor) || !empty($captionsize) || !empty($bgcolor) || !empty($bdcolor) || !empty($bdsize)){
			$caption_style = ' style="'.$captioncolor.$captionsize.$bgcolor.$bdcolor.$bdsize.'"';
	}

	$style 	= !empty($style) ? ' style="'.$style.'"' : '';
	$class = !empty($class) ? ' '.$class : '';

	$id = !empty($id) ? ' id="'.$id.'"' : '';

	// For Parallax theme
	$plx = !empty($plx) ? ' data-sr="'.$plx.'"' : '';

	$code = '<div'.$id.' class="dp_sc_capbox'.$class.'"'.$style.$plx.'>'.$title.'<div class="cap-content'.$icon_flag.$bdstyle.$titlepos_flag.$titlepattern.'"'.$caption_style.'>'.$content.'</div></div>';

	return $code;
}

/****************************************************************
* Register shortcodes
****************************************************************/
add_shortcode('font', 'dp_sc_pi_font');
add_shortcode('button', 'dp_sc_pi_button');
add_shortcode('label', 'dp_sc_pi_label');
add_shortcode('filter', 'dp_sc_img_filter');

// Disable wpautop for shortcodes 
function dp_sc_pi_run_shortcode_before( $content ) {
	if (is_admin()) return;

	global $shortcode_tags;
	// Backup exist shortcode
	// $orig_shortcode_tags = $shortcode_tags;
	// // Delete shortcode temporary
	// remove_all_shortcodes();

	// // Redo exist shortcodes
	// $shortcode_tags = $orig_shortcode_tags;

	// Prefix when conflict name.
	$conflict_prefix = 'dp_';

	// Register shortocodes
	if ( shortcode_exists( 'toggles' ) || shortcode_exists( 'toggle' ) ) {
		add_shortcode($conflict_prefix.'toggles', 'dp_sc_pi_toggles');
		add_shortcode($conflict_prefix.'toggle', 'dp_sc_pi_toggle');
	} else {
		add_shortcode('toggles', 'dp_sc_pi_toggles');
		add_shortcode('toggle', 'dp_sc_pi_toggle');
	}

	if ( shortcode_exists( 'accordions' ) || shortcode_exists( 'accordion' ) ) {
		add_shortcode($conflict_prefix.'accordions', 'dp_sc_pi_accordions');
		add_shortcode($conflict_prefix.'accordion', 'dp_sc_pi_accordion');
	} else {
		add_shortcode('accordions', 'dp_sc_pi_accordions');
		add_shortcode('accordion', 'dp_sc_pi_accordion');
	}

	if ( shortcode_exists( 'tabs' ) || shortcode_exists( 'tab' )) {
		add_shortcode($conflict_prefix."tabs", "dp_sc_pi_tabs");
		add_shortcode($conflict_prefix.'tab', 'dp_sc_pi_tab');
	} else {
		add_shortcode("tabs", "dp_sc_pi_tabs");
		add_shortcode('tab', 'dp_sc_pi_tab');
	}

	if ( shortcode_exists( 'table' ) || shortcode_exists( 'tablehead' ) || shortcode_exists( 'tablerow' ) || shortcode_exists( 'tablecell' ) ) {
		add_shortcode($conflict_prefix.'table', 'dp_sc_pi_table');
		add_shortcode($conflict_prefix.'tablehead', 'dp_sc_pi_table_head');
		add_shortcode($conflict_prefix.'tablerow', 'dp_sc_pi_table_row');
		add_shortcode($conflict_prefix.'tablecell', 'dp_sc_pi_table_cell');
	} else {
		add_shortcode('table', 'dp_sc_pi_table');
		add_shortcode('tablehead', 'dp_sc_pi_table_head');
		add_shortcode('tablerow', 'dp_sc_pi_table_row');
		add_shortcode('tablecell', 'dp_sc_pi_table_cell');
	}

	if ( shortcode_exists( 'promobox' ) || shortcode_exists( 'promo' ) ) {
		add_shortcode($conflict_prefix.'promobox', 'dp_sc_pi_promo_box');
		add_shortcode($conflict_prefix.'promo', 'dp_sc_pi_promo');
	} else {
		add_shortcode('promobox', 'dp_sc_pi_promo_box');
		add_shortcode('promo', 'dp_sc_pi_promo');
	}

	if ( shortcode_exists( 'highlighter' ) || shortcode_exists( 'highlight' )) {
		add_shortcode($conflict_prefix.'highlighter', 'dp_sc_highlighter');
		add_shortcode($conflict_prefix.'highlight', 'dp_sc_highlight');
	} else {
		add_shortcode('highlighter', 'dp_sc_highlighter');
		add_shortcode('highlight', 'dp_sc_highlight');
	}

	if ( shortcode_exists( 'profile' ) ) {
		add_shortcode($conflict_prefix.'profile', 'dp_sc_profile');
	} else {
		add_shortcode('profile', 'dp_sc_profile');
	}

	if ( shortcode_exists( 'dpslideshow' ) ) {
		add_shortcode($conflict_prefix.'dpslideshow', 'dp_sc_slideshow');
		add_shortcode($conflict_prefix.'dpslide', 'dp_sc_slide');
	} else {
		add_shortcode('dpslideshow', 'dp_sc_slideshow');
		add_shortcode('dpslide', 'dp_sc_slide');
	}

	if ( shortcode_exists( 'flexbox' ) || shortcode_exists( 'flexitem' ) ) {
		add_shortcode($conflict_prefix.'flexbox', 'dp_sc_pi_flex_box');
		add_shortcode($conflict_prefix.'flexitem', 'dp_sc_pi_flex_item');
	} else {
		add_shortcode('flexbox', 'dp_sc_pi_flex_box');
		add_shortcode('flexitem', 'dp_sc_pi_flex_item');
	}

	if ( shortcode_exists( 'blogcard' ) ) {
		add_shortcode($conflict_prefix.'blogcard', 'dp_sc_blogcard');
	} else {
		add_shortcode('blogcard', 'dp_sc_blogcard');
	}

	if ( shortcode_exists( 'talk' ) ) {
		add_shortcode($conflict_prefix.'talk', 'dp_sc_talk');
	} else {
		add_shortcode('talk', 'dp_sc_talk');
	}

	if ( shortcode_exists( 'ptable' ) || shortcode_exists( 'ptableitem' ) ) {
		add_shortcode($conflict_prefix.'ptable', 'dp_sc_pricing_table');
		add_shortcode($conflict_prefix.'ptableitem', 'dp_sc_pricing_table_item');
	} else {
		add_shortcode('ptable', 'dp_sc_pricing_table');
		add_shortcode('ptableitem', 'dp_sc_pricing_table_item');
	}

	if ( shortcode_exists( 'skillbars' ) || shortcode_exists( 'sbar' ) ) {
		add_shortcode($conflict_prefix.'skillbars', 'dp_sc_skill_bar');
		add_shortcode($conflict_prefix.'sbar', 'dp_sc_skill_bar_item');
	} else {
		add_shortcode('skillbars', 'dp_sc_skill_bar');
		add_shortcode('sbar', 'dp_sc_skill_bar_item');
	}

	if ( shortcode_exists( 'skillcircles' ) || shortcode_exists( 'scircle' ) ) {
		add_shortcode($conflict_prefix.'skillcircles', 'dp_sc_skill_circular_bar');
		add_shortcode($conflict_prefix.'scircle', 'dp_sc_skill_circular_bar_item');
	} else {
		add_shortcode('skillcircles', 'dp_sc_skill_circular_bar');
		add_shortcode('scircle', 'dp_sc_skill_circular_bar_item');
	}

	if ( shortcode_exists( 'countup' ) || shortcode_exists( 'counter' ) ) {
		add_shortcode($conflict_prefix.'countup', 'dp_sc_count_up');
		add_shortcode($conflict_prefix.'counter', 'dp_sc_count_up_item');
	} else {
		add_shortcode('countup', 'dp_sc_count_up');
		add_shortcode('counter', 'dp_sc_count_up_item');
	}

	if ( shortcode_exists( 'crtable' ) || shortcode_exists( 'crtablecol' ) ) {
		add_shortcode($conflict_prefix.'crtable', 'dp_sc_cross_reference_table');
		add_shortcode($conflict_prefix.'crtableitem', 'dp_sc_cross_reference_table_col');
	} else {
		add_shortcode('crtable', 'dp_sc_cross_reference_table');
		add_shortcode('crtablecol', 'dp_sc_cross_reference_table_col');
	}

	if ( shortcode_exists( 'faq' ) || shortcode_exists( 'faqitem' ) ) {
		add_shortcode($conflict_prefix.'faq', 'dp_sc_faq');
		add_shortcode($conflict_prefix.'faqitem', 'dp_sc_faq_item');
	} else {
		add_shortcode('faq', 'dp_sc_faq');
		add_shortcode('faqitem', 'dp_sc_faq_item');
	}

	if ( shortcode_exists( 'txtul' ) ) {
		add_shortcode($conflict_prefix.'txtul', 'dp_sc_text_underline');
	} else {
		add_shortcode('txtul', 'dp_sc_text_underline');
	}

	if ( shortcode_exists( 'txtreveal' ) ) {
		add_shortcode($conflict_prefix.'txtreveal', 'dp_sc_text_revealing');
	} else {
		add_shortcode('txtreveal', 'dp_sc_text_revealing');
	}

	if ( shortcode_exists( 'flipcard' ) || shortcode_exists( 'flipcarditem' ) ) {
		add_shortcode($conflict_prefix.'flipcard', 'dp_sc_flip_card');
		add_shortcode($conflict_prefix.'flipcarditem', 'dp_sc_flip_card_item');
	} else {
		add_shortcode('flipcard', 'dp_sc_flip_card');
		add_shortcode('flipcarditem', 'dp_sc_flip_card_item');
	}

	if ( shortcode_exists( 'capbox' ) ) {
		add_shortcode($conflict_prefix.'capbox', 'dp_sc_caption_box');
	} else {
		add_shortcode('capbox', 'dp_sc_caption_box');
	}

	// Do the original shortcodes
	$content = do_shortcode($content);
	//Return
	return $content;
}


/****************************************************************
* Enable shortcode in article and text widget.
****************************************************************/
add_filter( 'the_content', 'dp_sc_pi_run_shortcode_before', 7 );
add_filter( 'widget_text', 'dp_sc_pi_run_shortcode_before', 7 );
add_filter( 'dp_widget_text', 'dp_sc_pi_run_shortcode_before', 7 );
add_filter( 'dp_widget_text', 'dp_sc_pi_run_shortcode_before', 7 );
add_filter( 'dp_parallax_widget_description', 'dp_sc_pi_run_shortcode_before', 7 );
add_filter( 'dp_parallax_widget_content', 'dp_sc_pi_run_shortcode_before', 7 );

/****************************************************************
* Make darken or lighten color from hex to rgb
****************************************************************/
function dp_sc_pi_darkenColor($color, $range = 30) {
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
function dp_sc_pi_lightenColor($color, $range = 30) {
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
/****************************
 * HEX to RGB
 ***************************/
function dp_sc_pi_hexToRgb($color) {
	$color = preg_replace("/^#/", '', $color);
	if (mb_strlen($color) == 3) $color .= $color;
	$rgb = array();
	for($i = 0; $i < 6; $i+=2) {
		$hex = substr($color, $i, 2);
		$rgb[] = hexdec($hex);
	}
	return $rgb;
}
/****************************
// RGB to HEX
 ***************************/
function dp_sc_pi_rgbToHex($rgb) {
	if (count($rgb) !== 3) return;
	$hex = '';
	$current = '';
	foreach ($rgb as $val) {
		$current = dechex($val);
		if (mb_strlen($current) === 1) {
			$hex .= '0'.$current;
		} else {
			$hex .= $current;
		}
	}
	return '#'.$hex;
}

/****************************
 Random strings
 ***************************/
function dp_sc_rand($length = 8) {
	return base_convert(mt_rand(pow(36, $length - 1), pow(36, $length) - 1), 10, 36);
}