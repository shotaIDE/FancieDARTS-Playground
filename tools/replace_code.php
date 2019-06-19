<?php
$WP_HOME_DIR = '/var/www/html/fanciedarts';
$WP_INCLUDES_DIR = $WP_HOME_DIR . '/wp-includes';
$WP_CONTENT_DIR = $WP_HOME_DIR . '/wp-content';
$WP_THEMES_DIR = $WP_CONTENT_DIR . '/themes';
$WP_THEMES_FANCIE_NOTE_DIR = $WP_THEMES_DIR . '/dp-fancie-note';
$WP_THEMES_FANCIE_NOTE_CHILD_DIR = $WP_THEMES_DIR . '/dp-fancie-note-child';
$WP_PLUGINS_DIR = $WP_CONTENT_DIR . '/plugins';

$REPLACE_STRINGS_DIR = '/tmp/wordpress/replace_strings';

$original_code = file_get_contents($WP_INCLUDES_DIR . '/general-template.php');
file_put_contents($WP_INCLUDES_DIR . '/general-template.php',
    str_replace(
        '$title = single_term_title( $tax->labels->name . $t_sep, false );',
        '$title = single_term_title( $t_sep, false ); // for FancieDARTS: 各タクソノミー名だけを表示',
        $original_code)
);

$original_code = file_get_contents($WP_THEMES_FANCIE_NOTE_DIR . '/archive.php');
file_put_contents ($WP_THEMES_FANCIE_NOTE_CHILD_DIR . '/taxonomy-member_post.php',
    str_replace(
        'include (TEMPLATEPATH . "/article-loop.php");',
        'include ("article-loop-member_tax.php"); // for FancieDARTS: 記事ループ処理差し替え',
        $original_code)
);
file_put_contents ($WP_THEMES_FANCIE_NOTE_CHILD_DIR . '/taxonomy-member_office.php',
    str_replace(
        'include (TEMPLATEPATH . "/article-loop.php");',
        'include ("article-loop-member_tax.php"); // for FancieDARTS: 記事ループ処理差し替え',
        $original_code)
);

$original_code = file_get_contents($WP_THEMES_FANCIE_NOTE_DIR . '/single.php');
$before_str = file_get_contents($REPLACE_STRINGS_DIR . '/generate_display_member_tax_before.inc');
$after_str = file_get_contents($REPLACE_STRINGS_DIR . '/generate_display_member_tax_after.inc');
$replaced_code = str_replace($before_str, $after_str,
    $original_code);

$after_str = file_get_contents($REPLACE_STRINGS_DIR . '/insert_html_member_tax_after.inc');
$replaced_code = preg_replace(
    // 置換前
    '#'.
'// Show eyecatch image[\s\S]*// Content
'.'#',
    // 置換後
    $after_str,
    // 対象文字列
    $replaced_code);
file_put_contents($WP_THEMES_FANCIE_NOTE_CHILD_DIR . '/single-member.php', $replaced_code);

$original_code = file_get_contents($WP_THEMES_FANCIE_NOTE_DIR . '/inc/scr/related_posts.php');
file_put_contents($WP_THEMES_FANCIE_NOTE_DIR . '/inc/scr/related_posts.php',
    preg_replace(
        '#'.
'// Probably Custom post type[\s\S]*</aside><\?php
	elseif'.'#',
'// Probably Custom post type
		// ***********************************
	elseif',
        $original_code)
);

$original_code = file_get_contents($WP_PLUGINS_DIR . '/custom-field-template/custom-field-template.php');
file_put_contents($WP_PLUGINS_DIR . '/custom-field-template/custom-field-template.php',
    str_replace(
        '<p class="label">',
        '<p>',
        $original_code)
);
