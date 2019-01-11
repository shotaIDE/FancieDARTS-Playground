<?php
/*******************************************************
* description and keyword of meta tag.
*******************************************************/
/** ===================================================
* Create meta tag including description and keywords attributes.
* @param	none
* @return	none
*/
function dp_meta_kw_desc() {
	global $options;
	if (class_exists('amt_add_meta_tags') or class_exists('All_in_One_SEO_Pack') or class_exists('Platinum_SEO_Pack')) {
		return;
	} else {
		$meta_code = '<meta name="description" content="' . create_meta_desc_tag() . '" />';
		if ( !(isset($options['disable_meta_keywords']) && !empty($options['disable_meta_keywords'])) ){
			$meta_code .= '<meta name="keywords" content="' . get_meta_keywords() . '" />';
		}
		echo $meta_code;
	}
}

/*-------------------------------------------
 meta description tag
--------------------------------------------*/
function create_meta_desc_tag() {
	global $options;

	$meta_desc_tag = "";

	if (is_home()) {
		if ($options['enable_meta_def_desc'] && $options['meta_def_desc']) {
			$meta_desc_tag = str_replace(array("\r\n","\r","\n"), "", strip_tags($options['meta_def_desc']));
		} else {
			$meta_desc_tag = get_bloginfo('description');
		}
		if (is_paged()) {
			$meta_desc_tag .= '(' . get_query_var('paged') . ')';
		}
	} else if (is_category()) {	
		$catDesc = str_replace(array("\r\n","\r","\n"), "", strip_tags(category_description()));
		if (empty($catDesc)) {
			if (is_paged()) {
				$meta_desc_tag = wp_title('[', false) 
						. __(' ]Category page is displayed.', 'DigiPress')
						. '(' . get_query_var('paged') . ')';
			} else {
				$meta_desc_tag = wp_title('[', false) 
						. __(' ]Category page is displayed.', 'DigiPress');
			}
		} else {
			if (is_paged()) {
				$meta_desc_tag = $catDesc . '(' . get_query_var('paged') . ')';
			} else {
				$meta_desc_tag = $catDesc;
			}
		}
	} else if (is_year()) {
		if (is_paged()) {
			$meta_desc_tag =  __('Archive of the ', 'DigiPress') 
					. get_the_time(__('Y', 'DigiPress')) 
					. __(' is displayed.', 'DigiPress') 
					. '(' . get_query_var('paged') . ')';
		} else {
			$meta_desc_tag = __('Archive of the ', 'DigiPress')
					. get_the_time(__('Y', 'DigiPress')) 
					. __(' is displayed.', 'DigiPress');
		}
	} else if (is_month()) {
		if (is_paged()) {
			$meta_desc_tag = __('Archive of the ', 'DigiPress')
					. get_the_time(__('Y/m', 'DigiPress')) 
					. __(' is displayed.', 'DigiPress')
					. '(' . get_query_var('paged') . ')';
		} else {
			$meta_desc_tag = __('Archive of the ', 'DigiPress')
					. get_the_time(__('Y/m', 'DigiPress')) 
					. __(' is displayed.', 'DigiPress');
		}
	} else if (is_day()) {
		if (is_paged()) {
			$meta_desc_tag = __('Archive of the ', 'DigiPress')
					. get_the_time(__('Y/m/d', 'DigiPress')) 
					. __(' is displayed.', 'DigiPress')
					. '(' . get_query_var('paged') . ')';
		} else {
			$meta_desc_tag = __('Archive of the ', 'DigiPress')
					. get_the_time(__('Y/m/d', 'DigiPress')) 
					. __(' is displayed.', 'DigiPress');
		}
	} else if (is_time()) {
		if (is_paged()) {
			$meta_desc_tag = __('Archive of the ', 'DigiPress')
					. get_the_time(__('Y/m/d', 'DigiPress')) 
					. __(' is displayed.', 'DigiPress')
					. '(' . get_query_var('paged') . ')';
		} else {
			$meta_desc_tag = __('Archive of the ', 'DigiPress')
					. get_the_time(__('Y/m/d', 'DigiPress')) 
					. __(' is displayed.', 'DigiPress');
		}
	} else if (is_tag()) {
		$tagDesc = str_replace(array("\r\n","\r","\n"), "", strip_tags(tag_description()));
		if (empty($tagDesc)) {
			if (is_paged()) {
				$meta_desc_tag =  wp_title('',false)
						. __(' Tagged posts is displayed.', 'DigiPress')
						. ' Page(' . get_query_var('paged') . ')';
			} else {
				$meta_desc_tag =  wp_title('',false) 
						. __(' Tagged posts is displayed.', 'DigiPress');
			}
		} else {
			if (is_paged()) {
				$meta_desc_tag = $tagDesc . '(' . get_query_var('paged') . ')';
			} else {
				$meta_desc_tag = $tagDesc;
			}
		}
	} else if (is_search()) {
		if (is_paged()) {
			$meta_desc_tag = wp_title('', false, 'right') . '(' .get_query_var('paged') . ')';
		} else {
			$meta_desc_tag = wp_title('', false, 'right') ;
		}
	} else if (is_singular()) {
		while (have_posts()) {
			the_post();
			$desc = str_replace(array("\r\n","\r","\n"), "", strip_tags(get_the_excerpt()));
			if (mb_strlen($desc) > 300) $desc = mb_substr($desc, 0, 300, 'UTF-8').'...';
			$meta_desc_tag = $desc;
		}
	} else if (is_author()){
		$desc = str_replace(array("\r\n","\r","\n"), "", strip_tags(get_the_author_meta('description')));
		if (empty($desc)) {
			$meta_desc_tag =  wp_title('',false) . __(' is displayed.', 'DigiPress');
			if (is_paged()) {
				$meta_desc_tag .= '(' . get_query_var('paged') . ')';
			}
		} else {
			if (mb_strlen($desc) > 300) $desc = mb_substr($desc, 0, 300, 'UTF-8').'...';
			if (is_paged()) {
				$meta_desc_tag = $desc . '(' . get_query_var('paged') . ')';
			} else {
				$meta_desc_tag = $desc;
			}
		}
	} else {
		$meta_desc_tag =  wp_title('',false) . __(' is displayed.', 'DigiPress');
		if (is_paged()) {
			$meta_desc_tag .= '(' . get_query_var('paged') . ')';
		}
	}
	
	return $meta_desc_tag;
}

/*-------------------------------------------
 meta keyword tag
--------------------------------------------*/
function get_meta_keywords() {
	global $options;
	$meta_kw = "";

	if (is_singular()) {
		// get_post_meta(get_the_ID(), 'dp_meta_keyword', true)

		if (get_post_meta(get_the_ID(), 'dp_meta_keyword', true)) {
				$meta_kw = get_post_meta(get_the_ID(), 'dp_meta_keyword', true);
		} else {
			if (is_single()) {
				while (have_posts()) : the_post();
					$posttags = get_the_tags();
					$strTags = "";
					if ($posttags) {
						foreach($posttags as $tag) {
							$strTags =  $strTags . $tag->name . ',';
						}
					}
					if ( ! $strTags == "") {
						$meta_kw = rtrim($strTags, ",");
					}
				endwhile;
			} else if (is_page()) {
				$meta_kw = '';
			} else {
				$meta_kw = wp_title(',', false, 'right');
			}
		}

	} else if (is_archive()) {
		$arcName = wp_title(',', false, 'right');
		$meta_kw = $arcName . trim($options['meta_def_kw']);

	} else if (is_search()) {
		$arcName = wp_title(',', false, 'right');
		$meta_kw = $arcName . trim($options['meta_def_kw']) . ',search result';

	} else if (is_home()) {
		if ($options['enable_meta_def_kw']) {
			$meta_kw = trim($options['meta_def_kw']);
		}
	} else {
		$meta_kw = wp_title(',', false, 'right');
	}

	if (is_paged()) {
		$meta_kw .= ',Paged'. get_query_var('paged');
	}

	return $meta_kw;
}
/**
 * [dp_show_canonical description]
 * @param  boolean $echo [description]
 * @return [type]        [description]
 */
function dp_show_canonical($echo = true) {
	if (is_404()) return;
	global $options, $page, $paged, $wp_query, $IS_AMP_DP;
	$canonical_url = $next_prev_url = $amp_url = '';

	// canonical URL
	if (is_singular()) {
		$canonical_url = wp_get_canonical_url();
	} else {
		if (!$IS_AMP_DP) {
			$canonical_url = is_ssl() ? 'https://' : 'http://';
			$canonical_url .= $_SERVER['HTTP_HOST'].preg_replace('/\/'.DP_AMP_QUERY_VAR.'$|\/'.DP_AMP_QUERY_VAR.'\/$/i', '/', $_SERVER['REQUEST_URI']);
		} else {
			if (is_home() || is_front_page()) {
				$canonical_url = home_url();
			} else if (is_category()) {
				$canonical_url = get_category_link(get_query_var('cat'));
			} else if (is_tag()) {
				$canonical_url = get_tag_link(get_query_var('tag_id'));
			} else if (is_author()) {
				$canonical_url = get_author_posts_url(get_the_author_meta('ID'));
			} else if (is_search()) {
				$canonical_url = get_search_link();
			} else if (is_post_type_archive($options['news_cpt_slug_id'])) {
				$canonical_url = get_post_type_archive_link($options['news_cpt_slug_id']);
			} else {
				$canonical_url = is_ssl() ? 'https://' : 'http://';
				$canonical_url .= $_SERVER['HTTP_HOST'].preg_replace('/\/'.DP_AMP_QUERY_VAR.'$|\/'.DP_AMP_QUERY_VAR.'\/$/i', '/', $_SERVER['REQUEST_URI']);
			}
		}
	}
	if (!empty($canonical_url) && (bool)$echo) $canonical_url = '<link rel="canonical" href="'.esc_url($canonical_url).'" />';

	// Previous / Next page link
	if (!is_singular()) {
		$max_page = (int)$wp_query->max_num_pages;
		if ($max_page > 1) {
			$nextpage = $paged + 1;
			if ( $nextpage <= $max_page ) {
				$next_prev_url = '<link rel="next" href="'.next_posts( $max_page, false ).'" />';
			}
			if( $paged > 1 ){
				$next_prev_url .= '<link rel="prev" href="'.previous_posts( false ).'" />'; 
			}
		}
	}

	// AMP URL
	if (!$IS_AMP_DP && isset($options['use_amp']) && !empty($options['use_amp'])){
		if (is_home() || is_front_page()) {
			if (empty($options['disable_amp_archive'])){
				if( get_option('page_for_posts') && get_queried_object_id() ) {
					$post_id = get_option('page_for_posts');
					$amp_url = trailingslashit(trailingslashit(esc_url((get_permalink($post_id)))).DP_AMP_QUERY_VAR);
				} else {
					$paged_param = (get_query_var('paged')) ? '/page/'.get_query_var('paged') : '';
					$amp_url = trailingslashit(trailingslashit(esc_url(home_url())).DP_AMP_QUERY_VAR.$paged_param);
				}
			}
		} else if (is_archive()) {
			$has_amp = false;
			if (empty($options['disable_amp_archive'])){
				$archive_type = get_post_type_object(get_post_type());
				$arr_amp_target = array(
					'post',
					'page',
					$options['news_cpt_slug_id'],
				);
				if (!empty($options['enable_amp_cpt'])) {
					$arr_cpts = explode(',', $options['enable_amp_cpt']);
					$arr_amp_target = array_merge($arr_amp_target, $arr_cpts);
				}
				foreach ($arr_amp_target as $key => $current_type) {
					if ( $current_type === $archive_type->name){
						$has_amp = true;
						break;
					}
				}
				if ($has_amp) {
					global $wp;
					$current_archive_url = home_url($wp->request);
					if (is_paged()){
						$current_archive_url = preg_replace('/'.esc_url(get_query_var('name')).'\/page\/([0-9]{1,})/i', get_query_var('name').'/'.DP_AMP_QUERY_VAR.'/page/$1', $current_archive_url);
						$amp_url = trailingslashit(esc_url($current_archive_url));
					} else {
						$amp_url = trailingslashit(trailingslashit(esc_url($current_archive_url)).DP_AMP_QUERY_VAR);
					}
				}
			}
		} else if (is_single() || is_page()){
			$has_amp = false;
			$post_type = get_post_type();
			$arr_amp_target = array(
				'post',
				'page',
				$options['news_cpt_slug_id'],
			);
			if (!empty($options['enable_amp_cpt'])) {
				$arr_cpts = explode(',', $options['enable_amp_cpt']);
				$arr_amp_target = array_merge($arr_amp_target, $arr_cpts);
			}
			foreach ($arr_amp_target as $key => $current_type) {
				if ( $current_type === $post_type){
					$has_amp = true;
					break;
				}
			}
			if ($has_amp) {
				$id = get_the_ID();
				$disable_amp = get_post_meta($id, 'disable_amp', true);
				if (!$disable_amp) {
					$amp_url = trailingslashit(trailingslashit(esc_url((get_permalink($id)))).DP_AMP_QUERY_VAR);
				}
			}
		}
		if (!empty($amp_url) && (bool)$echo) $amp_url = '<link rel="amphtml" href="'.$amp_url.'" />';
	}

	if (isset($options['disable_canonical']) && !empty($options['disable_canonical']) && !$IS_AMP_DP){
		$canonical_url = '';
	}
	if ((bool)$echo) {
		echo $canonical_url.$amp_url.$next_prev_url;
	} else {
		return array($canonical_url, $amp_url);
	}
}