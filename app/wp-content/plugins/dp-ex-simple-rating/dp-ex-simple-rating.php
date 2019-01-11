<?php
/*
Plugin Name: DigiPress Ex - Simple Rating
Plugin URI: http://digipress.digi-state.com/
Description: Add rating system for DigiPress theme.
Version: 1.0.2.1
Author: digistate co.,ltd.
Author URI: http://www.digistate.co.jp/
Last Update	: 2018/5/1
*/
define('DP_EX_SIMPLE_RATING_NAME', 'DigiPress Ex - Simple Rating'); 
define('DP_EX_SIMPLE_RATING_AUTHOR', 'digistate'); 
define('DP_EX_SIMPLE_RATING_AUTHOR_URL', 'http://digipress.digi-state.com/'); 
define('DP_EX_SIMPLE_RATING_VERSION', '1.0.2.1');
define('DP_EX_SIMPLE_RATING_STORE_URL', 'http://digipress.digi-state.com/'); 
define('DP_EX_SIMPLE_RATING_LICENSE_KEY_PHRASE', 'dp_ex_simple_rating_license_key');
define('DP_EX_SIMPLE_RATING_LICENSE_OPT_UNIQUE_ID', 'dp-ex-simple-rating-license');
define('DP_EX_SIMPLE_RATING_TEXT_DOMAIN', 'dpexsimrat');


if ( !class_exists( 'DP_EX_SIMPLE_RATING' ) ) :
	/****************************************************************
	* Main Class
	****************************************************************/
	final class DP_EX_SIMPLE_RATING {
		/**
		 * Private params
		 *
		 */
		private static $instance;
		// public static $voted_like_count_key;

		/**
		 * Main Instance
		 *
		 * Ensures that only one instance exists in memory at any one
		 * time. Also prevents needing to define globals all over the place.
		 *
		 *
		 */
		public static function get_instance() {
			if ( ! isset( self::$instance ) && ! ( self::$instance instanceof DP_EX_SIMPLE_RATING ) ) {
				self::$instance = new DP_EX_SIMPLE_RATING;
				self::$instance->setup_globals();
				self::$instance->includes();
				self::$instance->requires();
				self::$instance->hooks();
			}
			return self::$instance;
		}


		/**
		 * Reset the instance of the class
		 *
		 * @access public
		 * @static
		 */
		public static function reset() {
			self::$instance = null;
		}

		/**
		 * Constructor Function
		 *
		 * @access private
		 */
		private function __construct() {
			self::$instance = $this;
			// initialing
			add_action( 'after_setup_theme', array( $this, 'init' ) );
			// Clean up
			if (function_exists('register_deactivation_hook')) {
				register_deactivation_hook(__FILE__, array($this, 'dp_ex_simple_rating_deactivation'));
			}
		}

		/**
		 * Globals
		 *
		 * @return void
		 */
		private function setup_globals() {
			$this->version 		= DP_EX_SIMPLE_RATING_VERSION;
			$this->title 		= DP_EX_SIMPLE_RATING_NAME;
			$this->text_domain 	= DP_EX_SIMPLE_RATING_TEXT_DOMAIN;
			$this->def_options = array(
									'expiretime' 	=> 365,
									'like_icon' 	=> 'icon-thumbs-up',
									'bad_icon' 		=> 'icon-thumbs-down',
									'show_bad' 		=> false,
									'available_post'=> true,
									'available_page'=> false,
									'position' 		=> 'top',
									'alignment' 	=> 'right',
									'show_polls_top' => true,
									'show_polls_archive' => true,
									'rate_caption'	=> '',
									'show_chart'	=> false,
									'liked_chart_color' => '#90def4',
									'bad_chart_color' => '#f7646e',
									'chart_btn_code' => htmlspecialchars(stripslashes('<span class="icon-chart"></span>'))
								);
			$this->file         = __FILE__;
			$this->basename     = apply_filters( 'dp_ex_simple_rating_plugin_basenname', plugin_basename( $this->file ) );
			$this->plugin_dir   = apply_filters( 'dp_ex_simple_rating_plugin_dir_path',  plugin_dir_path( $this->file ) );
			$this->plugin_url   = apply_filters( 'dp_ex_simple_rating_plugin_dir_url',   plugin_dir_url ( $this->file ) );
			$this->option_key 	= 'dp_ex_simple_rating_options';
			$this->cookie_name 	= 'dp_ex_simrat_vote';
			$this->voted_like_count_key = 'dp_ex_sr_votes_like_count';
			$this->voted_bad_count_key 	= 'dp_ex_sr_votes_bad_count';
			// self::$voted_like_count_key = $this->voted_like_count_key;
			$this->options 		= get_option($this->option_key);
			$this->status 		= false;
			$this->custom_field = 'dp_ex_simrat_disable';
		}


		/**
		 * Function fired on init
		 *
		 * This function is called on WordPress 'after_setup_theme'. It's triggered from the
		 * constructor function.
		 *
		 * @access public
		 *
		 *
		 * @return void
		 */
		public function init() {
			// Don't use 'load_plugin_textdomain'
			load_theme_textdomain(DP_EX_SIMPLE_RATING_TEXT_DOMAIN, dirname(__FILE__ ).'/languages/');
		}


		/**
		 * Includes
		 *
		 * @access private
		 * @return void
		 */
		private function includes() {
			// Widget
			if ( !class_exists( 'DP_EX_SIMRAT_BEST_RATED_WIDGET' )) {
				include( dirname( $this->file ) . '/inc/scr/widgets.php' );
			}
		}
		/**
		 * Requres
		 *
		 * @access private
		 * @return void
		 */
		private function requires() {
			// Shortcodes
			require_once( dirname( $this->file ) . '/inc/scr/shortcodes.php' );
		}

		/**
		 * Setup the default hooks and actions
		 *
		 * @return void
		 */
		private function hooks() {
			$options = $this->options;

			add_action('admin_menu', array($this, 'update_plugin_setting'));
			add_action('dp_ex_simple_rating_license_page_bottom', array($this, 'display_config_panel'));
			add_action('wp_enqueue_scripts', array($this, 'insert_js_css_in_header'));

			// Ajax
			add_action('wp_ajax_nopriv_dp-post-rate', array($this, 'dp_post_rate'));
			add_action('wp_ajax_dp-post-rate', array($this, 'dp_post_rate'));

			// Show rating in single page
			if ($options['position'] === 'top') {
				add_filter('dp_single_meta_top_first', array($this, 'add_rating_element'));
			} else if ($options['position'] === 'bottom') {
				add_filter('dp_single_meta_bottom_first', array($this, 'add_rating_element'));
			}
			// Show rating in archive page
			add_filter('dp_top_insert_sns_content', array($this, 'add_polls_in_archive'));
			add_filter('dp_archive_insert_sns_content', array($this, 'add_polls_in_archive'));

			// Custom field
			add_filter('dp_custom_field_add_params', array($this, 'add_custom_field'));
			add_filter('dp_custom_field_single_form', array($this, 'custom_field_form'));
			add_filter('dp_custom_field_page_form', array($this, 'custom_field_form'));

			// widgets_init
			add_action('widgets_init', array($this, 'best_rated_widget'));
		}

		/**
		 * Return option parameters
		 *
		 * @access public
		 * @return array
		 */
		public function get_plugin_setting() {
			// Default options
			$def_options = $this->def_options;
			// Get options
			$options = get_option($this->option_key);
			// Nothing has found
			if (!is_array($options)) {
				//Set default
				$options = $def_options;
				//Update
				update_option($this->option_key, $options);
			}
			return $options;
		}
		/**
		 * update options
		 *
		 * @access public
		 * @return void
		 */
		public function update_plugin_setting() {
			include( dirname( $this->file ) . '/inc/scr/update_plugin_setting.php' );
		}


		/****************************************************************
		* Count views (Show POST result from admin-ajax.php)
		****************************************************************/
		public function dp_post_rate() {
			$status = dp_ex_simple_rating_status();
			if (!$status) return;

			// Check for nonce security
			$nonce = $_POST['nonce'];
			if ( ! wp_verify_nonce( $nonce, 'ajax-nonce' ) ) {
				die (0);
			}

			// Post request
			if (isset($_POST['dp_post_rate'])) {
				$options 	= $this->options;
				$cookie_name = $this->cookie_name;
				$post_id 	= $_POST['post_id'];
				$vote_type 	= $_POST['vote_type'];	// like or bad

				// Use has already voted ?
				if ( !(bool)$this->has_already_rated($post_id) ) {
					// Count key custom field
					if ($vote_type === "like") {
						$voted_count_key = $this->voted_like_count_key;
					} else {
						$voted_count_key = $this->voted_bad_count_key;
					}

					// Get votes count for the current post
					$meta_count = get_post_meta($post_id, $voted_count_key, true);
					if (is_null($meta_count) ) {
						add_post_meta($post_id, $voted_count_key, 0);
					}

					// Increase votes count
					update_post_meta($post_id, $voted_count_key, ++$meta_count);
					// Display count (ie jQuery return value)
					echo $meta_count;

				} else {
					echo "already";
				}
			}
			exit;
		}

		/****************************************************************
		* HTML source
		****************************************************************/
		public function add_rating_element($post_id) {
			$status = dp_ex_simple_rating_status();
			if (!$status) return;
			if (empty($post_id)) return;
			if ( (bool)get_post_meta($post_id, $this->custom_field, true) ) return;

			// Current
			$options 		= $this->options;
			$cookie_name 	= $this->cookie_name;
			$rate_caption 	= $options['rate_caption'];

			$both_class 	= '';
			$rating_html	= '';
			$like_html 		= '';
			$bad_html 		= '';
			$bad_js 		= '';
			$chart_code 	= '';
			$chart_data 	= '';
			$chart_link 	= '';
			$chart_open_link = '';
			$rating_num 	= '';

			// Target check
			if ( (is_single() && (bool)$options['available_post']) || (is_page() && (bool)$options['available_page']) ) {

				// Check the cookie and the status of liked or bad
				$voted_result = $this->has_already_rated($post_id);

				// Get liked count
				$voted_like_count_key = $this->voted_like_count_key;
				$like_count = get_post_meta($post_id, $voted_like_count_key, true);
				if ($like_count === "" || is_array($like_count)) {
					$like_count = "0";
					update_post_meta($post_id, $voted_like_count_key, 0);
				}

				// alignment
				$alignment = ' al-r';
				switch ($options['alignment']) {
					case 'center':
						$alignment = ' al-c';
						break;
					case 'left':
						$alignment = ' al-l';
						break;
					default:
						$alignment = ' al-r';
						break;
				}

				// get rating
				$rating_num = get_post_meta($post_id, 'dp_star_rating', true);
				if (!empty($rating_num)) {
					$rating_num = '<meta itemprop="rating" content="'.$rating_num.'" />';
				} else {
					$rating_num = '';
				}

				// Like button
				if ( $voted_result === "like" ) {
					$rating_html = '<div class="vote-btn voted like '.$options['like_icon'].'" title="'.__('You have already rated this post.',  $this->text_domain).'">';

				} else if ( $voted_result === "bad" ) {
					$rating_html = '<div class="vote-btn disabled like '.$options['like_icon'].'" title="'.__('You have already rated this post.',  $this->text_domain).'">';
				} else {
					$rating_html = '<div data-post_id="'.$post_id.'" data-cookie_name="'.$cookie_name.'" data-expire_days="'.$options['expiretime'].'" data-vote_type="like" class="vote-btn unvoted like '.$options['like_icon'].'" title="'.__('I like this article!', $this->text_domain).'">';
				}
				$rating_html .= $rating_num.'<span itemprop="votes" class="count like-count" title="'.$like_count.__(' likes',  $this->text_domain).'">'.$like_count.'</span></div>'; 

				// Show bad or not
				if ((bool)$options['show_bad']) {
					$both_class = ' likebad';

					// Get bad count
					$voted_bad_count_key = $this->voted_bad_count_key;
					$bad_count = get_post_meta($post_id, $voted_bad_count_key, true);
					if ($bad_count === "" || is_array($bad_count)) {
						$bad_count = "0";
						update_post_meta($post_id, $voted_bad_count_key, 0);
					}

					if ( $voted_result === "like" ) {
						$rating_html .= '<div class="vote-btn disabled bad '.$options['bad_icon'].'" title="'.__('You have already rated this post.',  $this->text_domain).'">';

					} else if ( $voted_result === "bad" ) {
						$rating_html .= '<div class="vote-btn voted bad '.$options['bad_icon'].'" title="'.__('You have already rated this post.',  $this->text_domain).'">';
					} else {
						$rating_html .= '<div data-post_id="'.$post_id.'" data-cookie_name="'.$cookie_name.'" data-expire_days="'.$options['expiretime'].'" data-vote_type="bad" class="vote-btn unvoted bad '.$options['bad_icon'].'" title="'.__('Not so good...', $this->text_domain).'">';
					}
					$bad_js = ',{value:'.$bad_count.',color:"'.$options['bad_chart_color'].'",label:"'.__("Not so good",$this->text_domain).'"}';
					$rating_html .= '<span class="count bad-count" title="'.$bad_count.__(' unlikes',  $this->text_domain).'">'.$bad_count.'</span></div>';
				}

				/***
				 * Chart
				 */
				if ($options['show_chart']) {
					// chart code
					$chart_code = '<div class="canvas_holder"><canvas id="chart_id_'.$post_id.'" class="chart_area" width="480" height="480" /></div>';
					// Chart open link
					$chart_open_link = '<span class="chart_trigger tooltip" title="'.__('Show chart',$this->text_domain).'">'.htmlspecialchars_decode($options['chart_btn_code']).'</span>';
					// chart data
					$chart_data = '<script>
var chart_data_'.$post_id.'=[
{
	value:'.$like_count.',
	color:"'.$options['liked_chart_color'].'",
	label:"'.__("Like!",$this->text_domain).'"
}'.$bad_js.'];
j$(window).ready(function(){
	dp_ex_simrat_chart("chart_id_'.$post_id.'",chart_data_'.$post_id.');
});
</script>';
					$chart_data = str_replace(array("\r\n","\r","\n","\t"), '', $chart_data);
				}

				// Append chart trigger link
				$rating_html .= $chart_open_link;

				// Caption
				if (!empty($rate_caption)) {
					$rate_caption = htmlspecialchars_decode($rate_caption);
					$rate_caption = '<p class="rate_caption">'.$rate_caption.'</p>';
				}

				// HTML
				$rating_html = '<div itemprop="aggregateRating" itemscope itemtype="http://data-vocabulary.org/AggregateRating" class="dp_ex_rating'.$alignment.$both_class.'">'.$rate_caption.$rating_html.$chart_code.'</div>'.$chart_data;

				return $rating_html;
			}
		}

		/****************************************************************
		* Rating count source for archive page
		****************************************************************/
		public function add_polls_in_archive($post_id) {
			if (empty($post_id)) return;
			$options 	= $this->options;
			if ( (is_home() && !$options['show_polls_top']) || (is_archive() && !$options['show_polls_archive']) ) {
				return;
			}
			$show_flag 	= get_post_meta($post_id, $this->custom_field, true);
			if ($show_flag) return;

			// Code
			$code 		= '';
			if (!empty($options['like_icon'])) {
				$icon_class = ' class="'.$options['like_icon'].'"';
			}
			$code = get_post_meta($post_id, $this->voted_like_count_key, true);
			$code = empty($code) ? '0': $code;
			$code = '<div class="dp_ex_simrat"><span class="share-icon"><i'.$icon_class.'></i></span><span class="share-num">'.$code.'</span></div>';

			return $code;
		}

		/****************************************************************
		* Check the user has already voted
		****************************************************************/
		function has_already_rated($post_id){
			global $post;
			$result 	= false;
			$post_id 	= empty($post_id) ? $post->ID : $post_id;
			$array_type = array('like', 'bad');

			foreach ($array_type as $type) {
				if (isset($_COOKIE[$this->cookie_name.'_'.$post_id.'_'.$type])) {
					$cookie = $_COOKIE[$this->cookie_name.'_'.$post_id.'_'.$type];
					$cookie = explode(",", $cookie);
		 			if ( in_array($post_id, $cookie) ) {
		 				$result = $type;
		 				break;
		 			}
		 		}
			}
			return $result;
		}


		/****************************************************************
		* Custom field
		****************************************************************/
		public function add_custom_field($post_id) {
			return $this->custom_field;
		}
		public function custom_field_form($post_id) {
			$custom_field = $this->custom_field;
			$param = get_post_meta( $post_id, $custom_field);
			$checked = (bool)$param[0] ? ' checked' : '';

			$form = '<div class="dp_cf_item_box"><input name="'.$custom_field.'" id="'.$custom_field.'" type="checkbox" value="true"'.$checked.' /><label for="'.$custom_field.'" class="b"> '.__("Check to hide the rating button.", $this->text_domain).'</label><p>'.__('You can hide the rating button of this post when you check this option.', $this->text_domain).'</p></div>';
			return $form;
		}

		/****************************************************************
		* Add widget
		****************************************************************/
		public function best_rated_widget() {
			$status = dp_ex_simple_rating_status();
			if (!$status) return;
			return register_widget("DP_EX_SIMRAT_BEST_RATED_WIDGET");
		}


		/****************************************************************
		* Insert javascript into html header in widget.php
		****************************************************************/
		public function insert_js_css_in_header() {
			if (is_admin()) return;
			$options = $this->options;

			// CSS
			wp_enqueue_style( 'dp-ex-simple-rating', $this->plugin_url . 'css/style.css', array('digipress') );
			// Javascript ( load from footer)
			wp_enqueue_script('dp_ex_simple_ratinig_js', $this->plugin_url . 'inc/js/script.min.js', array('jquery'),false,true);
			wp_localize_script('dp_ex_simple_ratinig_js', 'ajax_var', 
				array(
				'url' => admin_url('admin-ajax.php'),
				'nonce' => wp_create_nonce('ajax-nonce')
				)
			);
			if ($options['show_chart']) {
				wp_enqueue_script('chart_js', DP_THEME_URI. '/inc/js/Chart.min.js', array('jquery'),false,true);
			}
		}

		/****************************************************************
		* Include configulation panel
		****************************************************************/
		public function display_config_panel() {
			include( dirname( $this->file ) . '/inc/scr/config.php' );
		}

		/****************************************************************
		* Admin messages
		****************************************************************/
		public function show_admin_notice_message() {
			if ( $messages = get_site_transient( 'dp-ex-simrat-notices' ) ) : 
?>
<div class="updated"><ul>
<?php 
			foreach( $messages as $message ): 
?><li><?php echo $message; ?></li><?php 
			endforeach;
?></ul></div>
<?php
			endif;
		}

		/**
		 * Do something when this plugin is about to be deactivated 
		*/
		function dp_ex_simple_rating_deactivation() {
			delete_post_meta_by_key( $this->custom_field );
			delete_post_meta_by_key( $this->voted_like_count_key );
			delete_post_meta_by_key( $this->voted_bad_count_key );
		}
	}	// -- End of class --

	/**
	 * Loads a single instance of this plugin
	 *
	 * This follows the PHP singleton design pattern.
	 */
	function dp_ex_simple_rating() {
		return DP_EX_SIMPLE_RATING::get_instance();
	}
	/**
	 * Loads plugin after all the others have loaded and have registered their hooks and filters
	*/
	add_action( 'plugins_loaded', 'dp_ex_simple_rating', apply_filters( 'dp_ex_simple_rating_action_priority', 10, 2 ) );
endif;

function dp_ex_simple_rating_updater() {
	if( ! class_exists( 'DP_EX_Plugin_Updater' ) ) {
		include( dirname( __FILE__ ) . '/inc/scr/DP_EX_Plugin_Updater.php' );
	}
	if ( class_exists( 'DP_EX_Plugin_Updater' )) {
		include( dirname( __FILE__ ) . '/inc/scr/updater.php' );
		// retrieve our license key from the DB
		$license_key = trim( get_option( DP_EX_SIMPLE_RATING_LICENSE_KEY_PHRASE ) );
		// setup the updater
		$dp_plugin_updater = new DP_EX_Plugin_Updater( DP_EX_SIMPLE_RATING_STORE_URL, __FILE__, array( 
				'version' 	=> DP_EX_SIMPLE_RATING_VERSION,
				'license' 	=> $license_key,
				'item_name' => DP_EX_SIMPLE_RATING_NAME, 
				'author' 	=> 'digistate co.,ltd.' 
			)
		);
		set_site_transient( DP_EX_SIMPLE_RATING_LICENSE_KEY_PHRASE, $license_key);
	} else {
		delete_site_transient(DP_EX_SIMPLE_RATING_LICENSE_KEY_PHRASE);
	}
}
add_action( 'plugins_loaded', 'dp_ex_simple_rating_updater', 0 );