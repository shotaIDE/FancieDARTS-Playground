<?php
/****************************************************************
* Disable auto paragraph
****************************************************************/
function dp_remove_wpauto($content) {
	global $post, $options;

	if ($options['disable_auto_format'] || (is_singular() && get_post_meta(get_the_ID(), 'disable_wpautop', true) ) ) {
		remove_filter('the_title', 'wptexturize');
		remove_filter('the_content', 'wptexturize');
		remove_filter('the_excerpt', 'wptexturize');
		remove_filter('the_title', 'wpautop');
		remove_filter('the_content', 'wpautop');
		remove_filter('the_excerpt', 'wpautop');
		remove_filter('the_editor_content', 'wp_richedit_pre');

		if ( (isset($options['replace_p_to_br']) && !empty($options['replace_p_to_br'])) || (is_singular() && get_post_meta(get_the_ID(), 'replace_p_to_br', true) )) {
			$content = nl2br($content);
		}
	} else {
		add_filter('the_content', 'dp_remove_wpautop_after_run', 20);
	}
	
	return $content;
}
add_action('the_content', 'dp_remove_wpauto', 7);
add_filter('widget_text_content', 'dp_remove_wpautop_after_run');


function dp_tiny_mce_disable_auto_fix_tag($init) {
	global $allowedposttags,$post, $options;

	if ($options['disable_auto_format'] || (is_singular() && get_post_meta(get_the_ID(), 'disable_wpautop', true) ) ) {
		$init['valid_elements'] = '*[*]';
		$init['extended_valid_elements'] = '*[*]';
		$init['valid_children'] = '+a['.implode('|',array_keys($allowedposttags )).']';
		$init['wpautop'] = false;
		$init['apply_source_formatting'] = true;
	}
	return $init;
}
add_filter('tiny_mce_before_init', 'dp_tiny_mce_disable_auto_fix_tag');

function dp_remove_wpautop_after_run($content){
	$regex = '/<div class="dp_sc_post_list">(.*?)<\/ul>.*<\/div>/s';
	$rep_text = '';
	$match = array();
	if (preg_match($regex, $content, $match)){
		if (!empty($match)) {
			$rep_text = str_replace(array("<p>","</p>"), '', $match[1]);
			$content = str_replace($match[1], $rep_text, $content);
		}
	}
	return $content;
}