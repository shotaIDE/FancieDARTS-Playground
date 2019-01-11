<?php
/**
 * Default shortcodes
 */
class DigiPress_Theme_Shortcodes {
	function __construct() {
		if (is_admin()){
			add_action('init', array( __CLASS__,'register'), 7);
		} else {
			add_filter( 'the_content', array( __CLASS__,'run_shortcodes'), 7 );
			add_filter( 'widget_text', array( __CLASS__,'run_shortcodes'), 7 );
			add_filter( 'dp_widget_text_content', array( __CLASS__,'run_shortcodes'), 7);
			add_filter( 'dp_parallax_widget_description', array( __CLASS__,'run_shortcodes') );
			add_filter( 'dp_parallax_widget_content', array( __CLASS__,'run_shortcodes') );
		}
	}
	/**
	* Execute shortcodes.
	*/
	public static function run_shortcodes($content){
		self::register();
		$content = do_shortcode($content);
		return $content;
	}
	/**
	 * Register shortcodes
	 */
	public static function register() {
		// shortcodes keys
		$arr_shortcodes = array(
			'youtube' => array(
				'has_child' => false,
				'child_code' => null
			),
			'adsense' => array(
				'has_child' => false,
				'child_code' => null
			),
			'gmaps' => array(
				'has_child' => false,
				'child_code' => null
			),
			'mostviewedposts' => array(
				'has_child' => false,
				'child_code' => null
			),
			'recentposts' => array(
				'has_child' => false,
				'child_code' => null
			),
			'qrcode' => array(
				'has_child' => false,
				'child_code' => null
			),
			'ss' => array(
				'has_child' => false,
				'child_code' => null
			)
		);
		// Prepare to avoid conflict
		$prefix = 'dp_';
		// Add all shortcodes
		foreach ( $arr_shortcodes as $id => $data ) {
			$code_name = $func = '';
			if ( is_callable( array( __CLASS__, $id ) ) ){
				$func = array( __CLASS__, $id );
				$code_name = shortcode_exists( $id ) ? $prefix.$id : $id;
				add_shortcode( $code_name, $func );
				// if parent has child
				if (isset($data['has_child']) && (bool)$data['has_child']){
					$func = array( __CLASS__, $data['child_code'] );
					$code_name = shortcode_exists( $data['child_code'] ) ? $prefix.$data['child_code'] : $data['child_code'];
					add_shortcode( $code_name, $func );
				}
			}
		}
	}

	/**
	 * Create link with the screenshot of target page.
	 */
	public static function ss($atts) {
		extract(shortcode_atts(array(
			'url'		=> home_url(),
			'width'		=> 250,
			'class'		=> '',
			'alt'		=> 'Screenshot',
			'rel'		=> '',
			'ext'		=> 1,
			'title'		=> '',
			'caption'	=> '',
			'hatebu'	=> 0,
			'tweets'	=> 0,
			'likes'		=> 0
		), $atts));

		if ($url == '') return;

		$ss_img = $sns_share_code = '';
		$urlencode 	= urlencode($url);
		$width 		= (int)$width;
		$actual_width = $width * 2;
		$ext 		= (bool)$ext ? ' target="_blank"' : '';
		$rel 		= ($rel == '') ? '' : ' rel="'.$rel.'" ';
		$return 	= '';
		$caption = !empty($caption) ? '<div class="ss_desc ft11px mg12px-top">'.$caption.'</div>' : '';

		// ************* SNS sahre number *****************
		// Count Facebook Like
		if ((bool)$likes) {
			$sns_share_code = '<span class="bg-likes icon-facebook ct-fb"><span class="share-num"></span></span>';
		}
		// tweets
		if ((bool)$tweets) {
			$sns_share_code .= '<span class="bg-tweets icon-twitter ct-tw"><span class="share-num"></span></span>';
		}
		// hatebu
		if ((bool)$hatebu) {
			$sns_share_code .= '<span class="bg-hatebu icon-hatebu ct-hb"><span class="share-num"></span></span>';
		}
		$sns_share_code = !empty($sns_share_code) ? '<div class="loop-share-num ct-shares disp-in-blk ft11px" data-url="'.$url.'">'.$sns_share_code.'</div>' : '';
		// ************* SNS sahre number *****************

		// Thumbnail
		$ss_img = "<a href=\"{$url}\"{$ext}{$rel}><img src=\"https://s0.wordpress.com/mshots/v1/{$urlencode}?w={$actual_width}\" class=\"dp_ss {$class}\" width=\"{$width}\" alt=\"{$alt}\" style=\"margin:0;padding:0;border:4px solid #fff;box-shadow:0 2px 12px -4px rgba(0,0,0,.72);\" /></a>";

		$title = !empty($title) ? "<p style=\"margin:0;padding:0 0 12px 0;\"><a href=\"{$url}\"{$ext}{$rel} class=\"b ft16px\">{$title}</a></p>" : '';

		if (empty($title) && empty($sns_share_code) && empty($caption)) {
			$return = '<div class="dp_sc_ss clearfix" style="margin-bottom:40px;">'.$ss_img.'</div>';
		} else {
			$return = '<div class="dp_sc_ss clearfix" style="margin-bottom:40px;padding:10px;border:1px solid rgba(0,0,0,.12);"><div class="disp-in-blk mg15px-r" style="vertical-align:top;">'.$ss_img.'</div><div class="disp-in-blk" style="width:calc(100% - '.($width + 30).'px);vertical-align:top;">'.$title.$sns_share_code.$caption.'</div></div>';
		}

		return $return;
	}

	/**
	 * Create QR code
	 */
	public static function qrcode($atts) {
		extract(shortcode_atts(array(
			'url'	=> home_url(),
			'size'	=> 150,
			'alt'	=> 'QR Code',
			'class'	=> 'alignnone'
		), $atts));

		$size 		= (int)$size;
		$actual_size = ($size > 273) ? 547 : $size * 2;

		return "<img src=\"https://chart.googleapis.com/chart?chs={$actual_size}x{$actual_size}&cht=qr&chl={$url}&choe=utf-8\" class=\"{$class}\" width=\"{$size}\" height=\"{$size}\" alt=\"{$alt}\" />";
	}

	/**
	 * Show recent posts in specific category in a post.
	 */
	public static function recentposts($atts) {
		extract(shortcode_atts(array(
			'num'		=> '5',
			'date'		=> 0,
			'views'		=> 0,
			'hatebu'	=> 0,
			'tweets'	=> 0,
			'likes'		=> 0,
			'excerpt'	=> 0,
			'thumb' 	=> 0,
			'thumbwidth' => 90,
			'thumbheight' => 53,
			'year'		=> '',
			'month'		=> '',
			'cat'		=> '',
			'order'		=> 'post_date',	// rand, comment_count, modified, title...
			'type'		=> ''
		), $atts));

		global $post;
		$code = '';
		$cat = str_replace("\s", "", $cat);

		if (function_exists('DP_GET_POSTS_BY_QUERY')) {
			$code = DP_GET_POSTS_BY_QUERY(array(
					'number'	=> $num,
					'views' 	=> $views,
					'thumbnail'	=> $thumb,
					'cat_id'	=> str_replace("\s", "", $cat),
					'year'		=> $year,
					'month'		=> $month,
					'excerpt'	=> $excerpt,
					'hatebuNumber'	=> $hatebu,
					'tweetsNumber'	=> $tweets,
					'likesNumber'	=> $likes,
					'post_type' => $type,
					'order_by'	=> $order,
					'pub_date'	=> $date,
					'return'	=> true
					)
			);
		}
		return '<div class="dp_sc_post_list">'.$code.'</div>';
	}

	/**
	 * Show most viewed posts.
	 */
	public static function mostviewedposts($atts) {
		extract(shortcode_atts(array(
			'num'		=> '5',
			'date'		=> 0,
			'views'		=> 0,
			'hatebu'	=> 0,
			'tweets'	=> 0,
			'likes'		=> 0,
			'excerpt'	=> 0,
			'thumb' 	=> 0,
			'thumbwidth' => 90,
			'thumbheight' => 53,
			'ranking' 	=> 0,
			'year'		=> '',
			'month'		=> '',
			'cat'		=> '',
			'type'		=> '',
			'term'		=> 'all' // daily, weekly, monthly
		), $atts));

		global $post;

		$code = '';
		$cat = str_replace("\s", "", $cat);

		switch ($term) {
			case 'all':
				$term = 'post_views_count';
				break;
			case 'daily':
				$term = 'post_views_count_daily';
				break;
			case 'weekly':
				$term = 'post_views_count_weekly';
				break;
			case 'monthly':
				$term = 'post_views_count_monthly';
				break;
			default:
				$term = 'post_views_count';
				break;
		}

		if (function_exists('DP_GET_POSTS_BY_QUERY')) {
			$code = DP_GET_POSTS_BY_QUERY(array(
					'number'	=> $num,
					'views' 	=> $views,
					'thumbnail'	=> $thumb,
					'cat_id'	=> str_replace("\s", "", $cat),
					'year'		=> $year,
					'month'		=> $month,
					'excerpt'	=> $excerpt,
					'hatebuNumber'	=> $hatebu,
					'tweetsNumber'	=> $tweets,
					'likesNumber'	=> $likes,
					'post_type' => $type,
					'meta_key'	=> $term,
					'order_by'	=> 'meta_value_num',
					'pub_date'	=> $date,
					'return'	=> true
					)
			);
		}
		return '<div class="dp_sc_post_list">'.$code.'</div>';
	}

	/**
	 * Google AdSense.
	 */
	public static function adsense($atts) {
		global $options;

		if (!$options['adsense_id']) return;

		extract(shortcode_atts(array(
			'id'		=> $options['adsense_id'],
			'unitid' 	=> '',
			'size'		=> 'rect'
		), $atts));
		$arrSize =array(300, 250);
		switch ($size) {
			case 'rect':
				$arrSize = array(300, 250);
				break;
			case 'rect_big':
				$arrSize = array(336, 280);
				break;
			case 'half_banner':
				$arrSize = array(234, 60);
				break;
			case 'banner':
				$arrSize = array(468, 60);
				break;
			case 'big_banner':
				$arrSize = array(728, 90);
				break;
			case 'square':
				$arrSize = array(250, 250);
				break;
			case 'square_s':
			case 'square_small':
				$arrSize = array(200, 200);
				break;
		}

	$adsCode = <<<_EOD_
<div class="dp_sc_ads"><script type="text/javascript"><!--
google_ad_client = "ca-pub-$id";google_ad_slot = "$unitid";google_ad_width = $arrSize[0];google_ad_height = $arrSize[1];
//--></script><script src="//pagead2.googlesyndication.com/pagead/show_ads.js"></script></div>
_EOD_;

		return $adsCode;
	}

	/**
	 * Show Google Maps.
	 */
	public static function gmaps($atts) {
		extract(shortcode_atts(array(
			'key' => '',
			'address' => 'Tokyo Sky Tree',
			'lat' => '',
			'lng' =>'',
			'width' => '100%',
			'height' => '350px',
			'title' => '',
			'text' => null,
			'name' => '',
			'zoom' => 18,
			'maxzoom' => null,
			'minzoom' => null,
			'nozoom' => false,
			'drag' => false,
			'visiblity' => 'simplified', // on, simplified
			'saturation' => 0, // -100 to 100
			'lightness' => 0, // -100 to 100
			'gamma' => 1,	//0.01 to 9.99
			'invert' => false,
			'marker' => true,	// 0, false
			'hue' => '',
			'animation' => '', //bounce, drop
			'style' => '',
			'class' => '',
			'preview' => false
		), $atts));

		if (empty($address) && empty($lat) && empty($lng)) return;

		global $options;

		// API Key
		if (!empty($key)) {
			$key = '?key='.$key;
		} else if (isset($options['google_api_key']) && !empty($options['google_api_key'])) {
			$key = '?key='.$options['google_api_key'];
		}

		if ($preview || empty($key)){
			return '<div class="dp_sc_gmap emb_video"><iframe width="'.$width.'" height="'.$height.'" frameborder="0" src="//maps.google.com/maps?q='.urlencode($address).'&zoom='.$zoom.'&output=embed"></iframe></div>';
		}

		// Load Google map API
		$api_url = 'https://maps.googleapis.com/maps/api/js'.$key;
		wp_enqueue_script( 'gmapsapi', $api_url, array(), false, true );

		// Unique ID
		$uni_id = dp_rand();
		$map_id = 'dp_sc_gmaps-'.$uni_id;

		// Pin title
		$title = !empty($title) ? "title:'".$title."'," :'';
		// CSS
		$class 	= !empty($class) ? ' class="dp_sc_gmap '.$class.'"' : ' class="dp_sc_gmap"';
		// name
		$name 	= !empty($name) ? "name:'".$name."'" : "name:'stlyedmap".$map_id."'";
		// Draggable
		$drag 	= !empty($drag) ? "draggable:true" : "draggable:false";
		// base color
		$hue 	= !empty($hue) ? "{'hue':'".$hue."'}," : "";
		// Invert map
		$invert = (bool)$invert ? "{'invert_lightness':true}," : "";
		// marker
		if ($marker === "0" || $marker === 'false') {
			$marker = "visible:false,";
		} else {
			$marker = '';
		}

		switch ($animation) {
			case 1:
			case 'drop':
				$animation = 'animation:google.maps.Animation.DROP,';
				break;
			case 2:
			case 'bounce':
				$animation = 'animation:google.maps.Animation.BOUNCE,';
				break;
			default:
				$animation = '';
				break;
		}
		// zoom
		if ((bool)$nozoom) {
			$maxzoom = "maxZoom:".$zoom.",";
			$minzoom = "minZoom:".$zoom.",";
		} else {
			$maxzoom = !empty($maxzoom) ? "maxZoom:".$maxzoom."," : "";
			$minzoom = !empty($minzoom) ? "minZoom:".$minzoom."," : "";
		}

		// Location
		$instance = '';
		$position = '';
		if (!empty($lat) && !empty($lng)) {
			// $position = 'new google.maps.LatLng('.$lat.','.$lng.')';
			$position = '{lat:parseFloat('.$lat.'),lng:parseFloat('.$lng.')}';
			$instance = "$(function(){
	new google.maps.Geocoder().geocode({'location':".$position."},cbRend".$uni_id.");
	});";
		} else {
			$instance = "$(function(){
	var address='".$address."';
	new google.maps.Geocoder().geocode({'address':address},cbRend".$uni_id.");
	});";
			$position = 'results[0].geometry.location';
		}

		// Info window
		$info_window = '';
		if (!empty($text)) {
			$info_window = 
	"var infoWin".$uni_id."=new google.maps.InfoWindow({content:'".$text."',position:pos});
	infoWin".$uni_id.".open(gmap);";
		}

		$code = '<div id="'.$map_id.'"'.$class.' style="width:'.$width.';height:'.$height.';'.$style.'"></div>';
		$code .= 
	"<div><script>
	".$instance."
	function cbRend".$uni_id."(results,status) {
		if(status==google.maps.GeocoderStatus.OK) {
			var pos=".$position.",
				options={
					zoom:".$zoom.",
					".$drag.",
					scrollwheel:false,
					center:pos,
					mapTypeId:google.maps.MapTypeId.ROADMAP
				},
				gmap=new google.maps.Map(document.getElementById('".$map_id."'),options),
				mkOpt={
					map:gmap,
					".$title.$marker.$animation."
					position:pos
				},
				mk=new google.maps.Marker(mkOpt),
				styleOpt=[{
					'stylers':[
						".$hue.$invert."
						{'lightness':".$lightness."},
						{'visibility':'".$visiblity."'},
						{'gamma':".$gamma."},
						{'saturation':".$saturation."}
					]
				}],
				styledMapOpt={
					".$maxzoom.$minzoom.$name."
				},
				mapType=new google.maps.StyledMapType(styleOpt,styledMapOpt);
			gmap.mapTypes.set('".$map_id."',mapType);
			gmap.setMapTypeId('".$map_id."');
			".$info_window."
		} else {
			console.log('gmaps err:'+status);
		}
	}
	</script></div>";

		$code = str_replace(array("\r\n","\r","\n","\t","/^\s(?=\s)/"), '', $code);
		return $code;
	}

	/**
	 * Show YouTube
	 */
	public static function youtube($atts) {
		extract(shortcode_atts(array(
			'id'		=> '',
			'width' 	=> '630',
			'height'	=> '354',
			'rel'		=> 1
		), $atts));
		if ($id == '') return;
		$rel = ((bool)$rel) ? '' : '?rel=0';
		 $youtube_code = '<div class="emb_video"><iframe width="'.$width.'" height="'.$height.'" src="https://www.youtube.com/embed/'.$id.$rel.'" allowfullscreen></iframe></div>';

		return $youtube_code;
	}
}

new DigiPress_Theme_Shortcodes;

class DigiPress_Shortcodes extends DigiPress_Theme_Shortcodes {
	function __construct() {
		parent::__construct();
	}
}