<?php
class DP_Post_Thumbnail {

	private static $widget_thumb;
	private static $related_thumb;
	private static $archive_thumb;

	/**
	 * Constructor
	 */
	function __construct() {
		// image size name, width, height, cropped (true or false)
		self::$widget_thumb = array( 'dp-widget-thumb', 140, 96, true );
		self::$related_thumb = array( 'dp-related-thumb', 250, 154, true );
		self::$archive_thumb = array( 'dp-archive-thumb', 450, 320, false );

		add_action( 'after_setup_theme', array( __CLASS__, 'add_thumbnail_size' ) );
	}

	/**
	 * Add custom post thumbnail size
	 */
	public static function add_thumbnail_size() {
		// add_theme_support( 'post-thumbnails' );
		add_image_size( self::$widget_thumb[0], self::$widget_thumb[1], self::$widget_thumb[2], (bool)self::$widget_thumb[3] );
		add_image_size( self::$widget_thumb[0] . '-2x', (int)self::$widget_thumb[1] * 2, (int)self::$widget_thumb[2] * 2, (bool)self::$widget_thumb[3] );
		add_image_size( self::$related_thumb[0], self::$related_thumb[1], self::$related_thumb[2], (bool)self::$related_thumb[3] );
		add_image_size( self::$related_thumb[0] . '-2x', (int)self::$related_thumb[1] * 2, (int)self::$related_thumb[2] * 2, (bool)self::$related_thumb[3] );
		add_image_size( self::$archive_thumb[0], self::$archive_thumb[1], self::$archive_thumb[2], (bool)self::$archive_thumb[3] );
		add_image_size( self::$archive_thumb[0] . '-2x', (int)self::$archive_thumb[1] * 2, (int)self::$archive_thumb[2] * 2, (bool)self::$archive_thumb[3] );
	}

	/**
	 * Retrieve the post thumbnail
	 */
	public static function get_post_thumbnail($args){
		global $options, $IS_MOBILE_DP, $IS_AMP_DP;
		// Default
		$post_id	= null;
		$if_img_tag = true;
		$no_img_replace = false;
		$img_prefix = '<img ';
		$img_suffix = ' />';
		$size = 'medium';
		$width 		= 450;
		$height		= 320;

		// AMP
		if ($IS_AMP_DP) {
			$img_prefix = '<amp-img ';
			$img_suffix = '></amp-img>';
		}
		// Params
		extract($args);

		$post_id = empty($post_id) ? get_the_ID() : $post_id;
		$image_url = $image_url2x = $image_data = $image_data2x = $img_tag = $img_tag2x = "";

		if (has_post_thumbnail($post_id)) {
			$image_id 	= get_post_thumbnail_id($post_id);
			$image_data = wp_get_attachment_image_src($image_id, $size, true);
			$image_url 	= is_ssl() ? str_replace('http:', 'https:', $image_data[0]) : $image_data[0];
			if ($size === self::$widget_thumb[0] || $size === self::$related_thumb[0] || $size === self::$archive_thumb[0]){
				$image_data2x = wp_get_attachment_image_src($image_id, $size . '-2x', true);
				$image_url2x = is_ssl() ? str_replace('http:', 'https:', $image_data2x[0]) : $image_data2x[0];
			}
			$alt = get_post_meta($image_id, '_wp_attachment_image_alt', true);
			$alt = empty($alt) ? esc_attr(get_the_title()) : $alt;
			$srcset = wp_get_attachment_image_srcset($image_id, $size);
			$srcset = $srcset !== false ? ' srcset="'.$srcset.'"' : '';

			if ($if_img_tag) {
				$img_tag = $img_prefix.'src="'.$image_url.'" width="'.$image_data[1].'" height="'.$image_data[2].'" layout="responsive" class="wp-post-image" alt="'.$alt.'"'.$srcset.$img_suffix;
			} else {
				$img_tag = empty($image_url2x) ? $image_url : $image_url2x;
			}
		} else {
			if ((bool)$no_img_replace) {
				$img_tag = false;
			} else {
				// Video ID(YouTube only)
				$videoID = get_post_meta($post_id, 'item_video_id', true);
				// Target video service
				$video_service = get_post_meta($post_id, 'video_service', true);
				// Image code
				if (!empty($videoID)) {
					if ( WP_Filesystem() ) {
					 	global $wp_filesystem;
					 	switch ($video_service) {
							case 'Vimeo':
								$vimeo_hash = unserialize($wp_filesystem->get_contents("https://vimeo.com/api/v2/video/".$videoID.".php"));
								if ($vimeo_hash) {
									$image_url = $vimeo_hash[0]['thumbnail_large'];
								}
								break;
							case 'YouTube':
							default:
								$image_url = '//img.youtube.com/vi/'.$videoID.'/0.jpg';
								break;
						}
					}
					if ($if_img_tag) {
						$img_size = dp_get_image_size($image_url);
						if (is_array($img_size)) {
							$img_tag = $img_prefix.'src="'.$image_url.'" class="wp-post-image" width="'.$img_size[0].'" height="'.$img_size[1].'" layout="responsive" alt="'.esc_attr(get_the_title()).'"'.$img_suffix;
						}
					} else {
						$img_tag = $image_url;
					}
				} else {
					preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"]/i', get_post($post_id)->post_content, $imgurl);
					if (isset($imgurl[1][0])) {
						$image_url = is_ssl() ? str_replace('http:', 'https:', $imgurl[1][0]) : $imgurl[1][0];
						if ($if_img_tag) {
							$img_sizes = ' width="'.$width.'" height="'.$height.'" ';
							$host_name = parse_url($image_url);
							$host_name = $host_name['host'];
							if (strpos($_SERVER["HTTP_HOST"], $host_name) !== false)  {
								$img_sizes = dp_get_image_size($image_url);
								if (is_array($img_sizes)) {
									$img_sizes = ' width="'.$img_sizes[0].'" height="'.$img_sizes[1].'" ';
								}
							}
							$img_tag = $img_prefix.'src="'.$image_url.'"'.$img_sizes.'layout="responsive" class="wp-post-image" alt="'.esc_attr(get_the_title()).'"'.$img_suffix;
						} else {
							$img_tag = $image_url;
						}
					} else {
						$strPattern	=	'/(\.gif|\.jpg|\.jpeg|\.png|\.svg)$/';
						$image = array();
						if ($handle = opendir(DP_THEME_DIR . '/img/post_thumbnail')) {
							$cnt = 0;
							while (false !== ($file = readdir($handle))) {
								if ($file != "." && $file != "..") {
									//Image file only
									if (preg_match($strPattern, $file)) {
										$image[$cnt] = DP_THEME_URI . '/img/post_thumbnail/'.$file;
										//count
										$cnt ++;
									}
								}
							}
							closedir($handle);
						}
						if ($cnt > 0) {
							$randInt = rand(0, $cnt - 1);
							$image_url = is_ssl() ? str_replace('http:', 'https:', $image[$randInt]) : $image[$randInt];
							if ($if_img_tag) {
								$img_sizes = dp_get_image_size($image_url);
								if (is_array($img_sizes)) {
									$img_tag = $img_prefix.'src="'.$image_url.'" width="'.$img_sizes[0].'" height="'.$img_sizes[1].'" layout="responsive" class="wp-post-image noimage" alt="'.esc_attr(get_the_title()).'"'.$img_suffix;
								}
							} else {
								$img_tag = $image_url;
							}
						}
					}
				}
			}
		}
		return $img_tag;
	}

	/**
	 * Check the target size exist
	 */
	public function exist_custom_size($post_id, $name, $str_wxh){
		global $post;
		$post_id = isset($post_id) && !empty($post_id) ? $post_id : $post->ID;
		$name = isset($name) && !empty($name) ? $name : 'medium';

		$the_thumbnail_src = wp_get_attachment_image_src( get_post_thumbnail_id( $post_id ), $name );
		$thumbnail_src = $the_thumbnail_src[0];
		//change this to exact WxH of your custom image size
		$check_thumb = strpos($thumbnail_src, $str_wxh);

		// if custom image size exist
		if( $check_thumb ) return true;

		// not exist
		return false;
	}
}

new DP_Post_Thumbnail;