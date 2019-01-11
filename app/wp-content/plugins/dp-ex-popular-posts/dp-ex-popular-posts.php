<?php
/*
Plugin Name: DigiPress Ex - Popular Posts
Plugin URI: http://digipress.digi-state.com/
Description: Get daily, weekly and monthly popular posts in original widget of DigiPress.
Version: 1.0.1.1
Author: digistate co.,ltd.
Author URI: http://www.digistate.co.jp/
Last Update	: 2016/2/9
*/
define('DP_EX_POPULAR_POSTS_NAME', 'DigiPress Ex - Popular Posts'); 
define('DP_EX_POPULAR_POSTS_AUTHOR', 'digistate'); 
define('DP_EX_POPULAR_POSTS_AUTHOR_URL', 'https://digipress.digi-state.com/'); 
define('DP_EX_POPULAR_POSTS_VERSION', '1.0.1.1');
define('DP_EX_POPULAR_POSTS_STORE_URL', 'https://digipress.digi-state.com/'); 
define('DP_EX_POPULAR_POSTS_LICENSE_KEY_PHRASE', 'dp_ex_popular_posts_license_key');
define('DP_EX_POPULAR_POSTS_LICENSE_OPT_UNIQUE_ID', 'dp-ex-popular-posts-license');
define('DP_EX_POPULAR_POSTS_TEXT_DOMAIN', 'dp_ex_pp');


if ( !class_exists( 'DP_EX_POPULAR_POSTS' ) ) :

	final class DP_EX_POPULAR_POSTS {
		/**
		 * Holds the instance
		 *
		 * @var object
		 */
		private static $instance;

		/**
		 * Main Instance
		 *
		 * Ensures that only one instance exists in memory at any one
		 * time. Also prevents needing to define globals all over the place.
		 *
		 *
		 */
		public static function get_instance() {
			if ( ! isset( self::$instance ) && ! ( self::$instance instanceof DP_EX_POPULAR_POSTS ) ) {
				self::$instance = new DP_EX_POPULAR_POSTS;
				self::$instance->setup_globals();
				// self::$instance->includes();
				self::$instance->hooks();
			}

			return self::$instance;
		}

		/**
		 * Constructor Function
		 *
		 * @access private
		 */
		private function __construct() {
			self::$instance = $this;
			add_action( 'init', array( $this, 'init' ) );
			if (function_exists('register_deactivation_hook')) {
				register_deactivation_hook(__FILE__, array($this, 'dp_ex_popular_posts_deactivation'));
			}
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
		 * Globals
		 *
		 * @return void
		 */
		private function setup_globals() {
			$this->version 		= DP_EX_POPULAR_POSTS_VERSION;
			$this->title 		= DP_EX_POPULAR_POSTS_NAME;
			// paths
			$this->file         = __FILE__;
			$this->basename     = apply_filters( 'dp_ex_popular_posts_plugin_basenname', plugin_basename( $this->file ) );
			$this->plugin_dir   = apply_filters( 'dp_ex_popular_posts_plugin_dir_path',  plugin_dir_path( $this->file ) );
			$this->plugin_url   = apply_filters( 'dp_ex_popular_posts_plugin_dir_url',   plugin_dir_url ( $this->file ) );
			$this->status 		= false;
			$this->count_key 	= 'post_views_count';
			$this->terms 		= array('_daily','_weekly', '_monthly');
			$this->weekday 		= array('mon', 'tue', 'wed', 'thu', 'fri', 'sat', 'sun');
			$this->reset_weekday = 1; // Monday
			$this->reset_monthday = 1; // Every month
		}

		/**
		 * Function fired on init
		 *
		 * This function is called on WordPress 'init'. It's triggered from the
		 * constructor function.
		 *
		 * @access public
		 *
		 * @uses DP_EX_POPULAR_POSTS::load_textdomain()
		 *
		 * @return void
		 */
		public function init() {
			load_theme_textdomain(DP_EX_POPULAR_POSTS_TEXT_DOMAIN, dirname(__FILE__ ).'/languages/');
			// $this->load_textdomain();
		}


		/**
		 * Includes
		 *
		 * @access private
		 * @return void
		 */
		// private function includes() {
		// 	include( dirname( $this->file ) . '/inc/scr/updater.php' );
		// }

		/**
		 * Setup the default hooks and actions
		 *
		 * @return void
		 */
		private function hooks() {
			// add_action( 'admin_print_scripts-widgets.php', array($this, 'insert_script_in_header'));
			add_action('dp_count_post_views', array($this, 'count_post_views') );
			add_filter('dp_tab_widget_most_viewed_form', array($this, 'add_form_element'));
			add_filter('dp_most_viewed_widget_form', array($this, 'add_form_element'));
			add_filter('dp_custom_post_widget_form', array($this, 'add_form_element'));
		}

		/****************************************************************
		* Count views
		****************************************************************/
		public function count_post_views($params) {
			if (is_admin()) return;
			// $status = dp_ex_popular_posts_status();
			// if (!$status) return;
			// Check the current situation
			if (!is_singular() || is_paged()) return;
			if (!is_array($params)) return;
			$post_ID 	= $params[0];
			$update 	= $params[1];

			if (empty($post_ID)) return;

			//Set the name of the Posts Custom Field.
			$count_key = $this->count_key;
			// Set the surfix of term to array.
			$arrTerms = $this->terms;
			// Set reset weekday
			$reset_weekday = $this->reset_weekday;
			// Set reset day of the month
			$reset_monthday = $this->reset_monthday;

			// For get the dateinfo to reset data
			$datetime = new DateTime(NULL, new DateTimeZone('Asia/Tokyo'));
			$hour_today = (int)$datetime->format('G');
			$week_today = (int)$datetime->format('w');
			$day_today = (int)$datetime->format('d');

			// ********************
			// Reset daily counter 
			// ********************
			$current_count_key = $count_key . $arrTerms[0];
			$clear_flag_key = $current_count_key. '_flag';
			$clear_flag_val = get_post_meta($post_ID, $clear_flag_key, true);

			if ($hour_today >= 0 && $hour_today <= 4 ) {
				if ($clear_flag_val == 1) {
					// Clear all counter
					delete_post_meta_by_key( $current_count_key );
					delete_post_meta_by_key( $clear_flag_key );
					// re-add counter flag
					delete_post_meta($post_ID, $clear_flag_key); 
					add_post_meta($post_ID, $clear_flag_key, 0);
				} else {
					if ($clear_flag_val != 0) {
						update_post_meta($post_ID, $clear_flag_key, 0);
					}
				}
			
			} else {				
				if ( $clear_flag_val == "" ) {
					add_post_meta($post_ID, $clear_flag_key, 1);
				} else if ($clear_flag_key != 1) {
					update_post_meta($post_ID, $clear_flag_key, 1);
				}
			}

			// ********************
			// Reset weekly counter 
			// ********************
			$current_count_key = $count_key . $arrTerms[1];
			$clear_flag_key = $current_count_key . '_flag';
			$clear_flag_val = get_post_meta($post_ID, $clear_flag_key, true);
			if ($week_today == $reset_weekday) {
				if ($clear_flag_val == 1) {
					// Clear all counter
					delete_post_meta_by_key( $current_count_key );
					delete_post_meta_by_key( $clear_flag_key );
					// re-add counter flag
					delete_post_meta($post_ID, $clear_flag_key); 
					add_post_meta($post_ID, $clear_flag_key, 0);
				} else {
					if ($clear_flag_val != 0) {
						update_post_meta($post_ID, $clear_flag_key, 0);
					}
				}
			} else {
				if ( $clear_flag_val == "" ) {
					add_post_meta($post_ID, $clear_flag_key, 1);
				} else if ($clear_flag_key != 1) {
					update_post_meta($post_ID, $clear_flag_key, 1);
				}
			}

			// ********************
			// Reset monthly counter
			// ********************
			$current_count_key = $count_key . $arrTerms[2];
			$clear_flag_key = $current_count_key . '_flag';
			$clear_flag_val = get_post_meta($post_ID, $clear_flag_key, true);
			if ($day_today == $reset_monthday) {
				if ($clear_flag_val == 1) {
					// Clear all counter
					delete_post_meta_by_key( $current_count_key );
					delete_post_meta_by_key( $clear_flag_key );
					add_post_meta($post_ID, $clear_flag_key, 0);
				} else {
					if ( $clear_flag_val == "" ) {
						add_post_meta($post_ID, $clear_flag_key, 0);
					} else if ($clear_flag_val != 0) {
						update_post_meta($post_ID, $clear_flag_key, 0);
					}
				}
			} else {
				if ( $clear_flag_val == "" ) {
					add_post_meta($post_ID, $clear_flag_key, 1);
				} else if ($clear_flag_key != 1) {
					update_post_meta($post_ID, $clear_flag_key, 1);
				}
			}

			// ********************
			// Update counter
			// ********************
			foreach ($arrTerms as $key => $value) {
				$current_count_key = $count_key . $value;
				//Returns values of the custom field with the specified key from the specified post.
				$count = get_post_meta($post_ID, $current_count_key, true);

				//If the the Post Custom Field value is empty. 
				if ( $count == null ) {
					$count = 0; // set the counter to zero.
					//Delete all custom fields with the specified key from the specified post. 
					delete_post_meta($post_ID, $current_count_key); 
					//Add a custom (meta) field (Name/value)to the specified post.
					add_post_meta($post_ID, $current_count_key, 0);
					
				} else {	//If the the Post Custom Field value is NOT empty.
					if ((bool)$update) {
						//increment the counter by 1.
						//Update the value of an existing meta key (custom field) for the specified post.
						$count++;
						update_post_meta($post_ID, $current_count_key, $count);
					}
				}
			}
		}


		/****************************************************************
		* Add form element into popular posts setting
		****************************************************************/
		public function add_form_element($params) {
			// $status = dp_ex_popular_posts_status();
			// if (!$status) return;
			if (!is_array($params)) return;

			$post_type 		= $params[0];
			$meta_key 		= $params[1];
			$meta_key_name 	= $params[2];
			$meta_key_id 	= $params[3];

			// Only custom post type
			$ranking 		= $params[4];
			$ranking_name 	= $params[5];
			$ranking_id 	= $params[6];

			$enable_code 	= '';
			$term_code 		= '';
			$count_key 		= $this->count_key;
			$array_term 	= $this->terms;
			array_unshift($array_term, '');

			if ($post_type === 'custom') {
				if ((bool)$ranking) {
					$enable_code = '<p><input name="'.$ranking_name.'" id="'.$ranking_id.'" type="checkbox" value="true" checked /> <label for="'.$ranking_id.'">'.__('Show as popular posts','DigiPress').'</label></p>';
				} else {
					$enable_code = '<p><input name="'.$ranking_name.'" id="'.$ranking_id.'" type="checkbox" value="true" /> <label for="'.$ranking_id.'">'.__('Show as popular posts','DigiPress').'</label></p>';
				}
			}

			foreach ($array_term as $key => $value) {
				if ($meta_key === $count_key.$value) {
					$term_code .= '<option value="'.$count_key.$value.'" selected>'.__($count_key.$value, DP_EX_POPULAR_POSTS_TEXT_DOMAIN).'</option>';
				} else {
					$term_code .= '<option value="'.$count_key.$value.'">'.__($count_key.$value, DP_EX_POPULAR_POSTS_TEXT_DOMAIN).'</option>';
				}
			}

			$term_code = '<div>'.$enable_code.'<label for="'.$meta_key_id.'">'.__('Target term',DP_EX_POPULAR_POSTS_TEXT_DOMAIN).':</label> <select name="'.$meta_key_name.'" id="'.$meta_key_id.'" class="select_term">'.$term_code.'</select></div>';

			return $term_code;
		}

		/****************************************************************
		* Insert javascript into html header in widget.php
		****************************************************************/
		public function insert_script_in_header() {
			if (!is_admin()) return;
			// wp_enqueue_script('dp_ex_popular_posts_js', $this->plugin_url . 'inc/js/script.min.js', array('jquery'));
		}

		/**
		 * Do something when this plugin is about to be deactivated 
		*/
		function dp_ex_popular_posts_deactivation() {
			$count_key 	= $this->count_key;
			$arrTerms 	= $this->terms;
			foreach ($arrTerms as $key => $value) {
				// Clear counter
				delete_post_meta_by_key( $count_key . $arrTerms[$key] );
				delete_post_meta_by_key( $count_key . $arrTerms[$key] .'_flag');
			}
		}
	}
	// Start count
	// $dp_ex_popular_posts = new DP_EX_POPULAR_POSTS();

	/**
	 * Loads a single instance of this plugin
	 *
	 * This follows the PHP singleton design pattern.
	 */
	function dp_ex_popular_posts() {
		return DP_EX_POPULAR_POSTS::get_instance();
	}
	/**
	 * Loads plugin after all the others have loaded and have registered their hooks and filters
	*/
	add_action( 'plugins_loaded', 'dp_ex_popular_posts', apply_filters( 'dp_ex_popular_posts_action_priority', 10, 2 ) );
endif;

function dp_ex_popular_posts_updater() {
	if( ! class_exists( 'DP_EX_Plugin_Updater' ) ) {
		include( dirname( __FILE__ ) . '/inc/scr/DP_EX_Plugin_Updater.php' );
	}
	if ( class_exists( 'DP_EX_Plugin_Updater' )) {
		include( dirname( __FILE__ ) . '/inc/scr/updater.php' );
		// retrieve our license key from the DB
		$license_key = trim( get_option( DP_EX_POPULAR_POSTS_LICENSE_KEY_PHRASE ) );
		// setup the updater
		$dp_plugin_updater = new DP_EX_Plugin_Updater( DP_EX_POPULAR_POSTS_STORE_URL, __FILE__, array( 
				'version' 	=> DP_EX_POPULAR_POSTS_VERSION,
				'license' 	=> $license_key,
				'item_name' => DP_EX_POPULAR_POSTS_NAME, 
				'author' 	=> 'digistate co.,ltd.' 
			)
		);
		set_site_transient( DP_EX_POPULAR_POSTS_LICENSE_KEY_PHRASE, $license_key);
	} else {
		delete_site_transient(DP_EX_POPULAR_POSTS_LICENSE_KEY_PHRASE);
	}
}
add_action( 'plugins_loaded', 'dp_ex_popular_posts_updater', 0 );