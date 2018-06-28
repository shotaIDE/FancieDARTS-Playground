<?php
$WP_HOME = '/var/www/html';
$WP_INCLUDES = $WP_HOME . '/wp-includes';
$WP_CONTENT = $WP_HOME . '/wp-content';
$WP_THEMES = $WP_CONTENT . '/themes';
$WP_THEMES_FANCIE_NOTE = $WP_THEMES . '/dp-fancie-note';
$WP_THEMES_FANCIE_NOTE_CHILD = $WP_THEMES . '/dp-fancie-note-child';
$WP_PLUGINS = $WP_CONTENT . '/plugins';

$src_str = file_get_contents($WP_INCLUDES . '/general-template.php');
file_put_contents ($WP_INCLUDES . '/general-template.php',
    str_replace(
        '$title = single_term_title( $tax->labels->name . $t_sep, false );',
        '$title = single_term_title( $t_sep, false ); // for FancieDARTS: 各タクソノミー名だけを表示',
        $src_str)
);

$src_str = file_get_contents($WP_THEMES_FANCIE_NOTE . '/archive.php');
file_put_contents ($WP_THEMES_FANCIE_NOTE_CHILD . '/taxonomy-member_post.php',
    str_replace(
        'include (TEMPLATEPATH . "/article-loop.php");',
        'include ("article-loop-member_tax.php"); // for FancieDARTS: 記事ループ処理差し替え',
        $src_str)
);
file_put_contents ($WP_THEMES_FANCIE_NOTE_CHILD . '/taxonomy-member_office.php',
    str_replace(
        'include (TEMPLATEPATH . "/article-loop.php");',
        'include ("article-loop-member_tax.php"); // for FancieDARTS: 記事ループ処理差し替え',
        $src_str)
);

$src_str = file_get_contents($WP_THEMES_FANCIE_NOTE . '/article-loop.php');
file_put_contents ($WP_THEMES_FANCIE_NOTE_CHILD . '/article-loop-member_tax.php',
    str_replace(
        // 置換前
        "/**"."\r\n".
        " * Show post list",
        // 置換後
        "// ***********************************"."\r\n".
        "// for FancieDARTS [START]: タクソノミーの説明を表示"."\r\n".
        "// ***********************************"."\r\n".
        "\$term_code = '';"."\r\n".
        "\$term = get_queried_object();"."\r\n".
        "\$term_name = \$term->name;"."\r\n".
        "\$term_description = term_description(); // 整形されたデータ取得のため、\$term->description は利用しない"."\r\n".
        "if (\$term_description !== '') {"."\r\n".
        "	\$term_code = sprintf('<div class=\"widget-content bottom clearfix\"><div id=\"dprecentcustompostswidget-2\" class=\"widget-box dp_recent_custom_posts_widget slider_fx\"><h3 class=\"fancie_darts_taxonomy_name inside-title wow fadeInLeft\" style=\"visibility: visible; animation-name: fadeInLeft;\"><span>%s</span></h3>"."\r\n".
        "		<div class=\"fancie_darts_taxonomy_description entry entry-content\" style=\"padding-left: 10px\">%s</div></div></div>',"."\r\n".
        "		\$term_name,"."\r\n".
        "		\$term_description);"."\r\n".
        "}"."\r\n".
        "echo \$term_code;"."\r\n".
        "// ***********************************"."\r\n".
        "// for FancieDARTS [END]: タクソノミーの説明を表示"."\r\n".
        "// ***********************************"."\r\n".
        "/**"."\r\n".
        " * Show post list",
        // 対象文字列
        $src_str)
);

$src_str = file_get_contents($WP_THEMES_FANCIE_NOTE . '/single.php');
$dst_str = str_replace(
    // 置換前
    "// **********************************"."\n".
    "// show posts",
    // 置換後
    "// ***********************************"."\n".
    "// for FancieDARTS [START]: 社員情報のカスタムフィールド表示のHTML生成"."\n".
    "// ***********************************"."\n".
    "\$member_post_list = get_the_terms(\$post->ID, \$DARTS_MEMBER_TAX_POST);"."\n".
    "asort(\$member_post_list);"."\n".
    "\$html_member_post_list = \"\";"."\n".
    "\$num = 0;"."\n".
    "foreach (\$member_post_list as \$key => \$val) {"."\n".
    "	if (\$num >= 1) \$html_member_post_list .= ' / ';"."\n".
    "	\$html_member_post_list .= '<a href=\"' .get_term_link(\$val->slug , \$DARTS_MEMBER_TAX_POST) .'\">';"."\n".
    "	\$html_member_post_list .= \$val->name .'</a>';"."\n".
    "	\$num++;"."\n".
    "}"."\n".
    "\$member_office_list = get_the_terms(\$post->ID, \$DARTS_MEMBER_TAX_OFFICE);"."\n".
    "asort(\$member_office_list);"."\n".
    "\$html_member_office_list = \"\";"."\n".
    "\$num = 0;"."\n".
    "foreach (\$member_office_list as \$key => \$val) {"."\n".
    "	if (\$num >= 1) \$html_member_office_list .= ' / ';"."\n".
    "	\$html_member_office_list .= '<a href=\"' .get_term_link(\$val->slug , \$DARTS_MEMBER_TAX_OFFICE) .'\">';"."\n".
    "	\$html_member_office_list .= \$val->name .'</a>';"."\n".
    "	\$num++;"."\n".
    "}"."\n".
    "// ***********************************"."\n".
    "// for FancieDARTS [END]: 社員情報のカスタムフィールド表示のHTML生成"."\n".
    "// ***********************************"."\n".
    ""."\n".
    "// **********************************"."\n".
    "// show posts",
    // 対象文字列
    $src_str);

$dst_str = preg_replace(
    // 置換前
    '#'.
'// Show eyecatch image[\s\S]*// Content
'.'#',
    // 置換後
    "// Show eyecatch image"."\n".
    "		// ***********************************"."\n".
    "		// for FancieDARTS [START]: アイキャッチ画像を指定サイズで表示"."\n".
    "		// ***********************************"."\n".
    "		\$image_id	= get_post_thumbnail_id();"."\n".
    "		\$image_data	= wp_get_attachment_image_src(\$image_id, array(\$width, \$height), true);"."\n".
    "		\$image_url 	= is_ssl() ? str_replace('http:', 'https:', \$image_data[0]) : \$image_data[0];"."\n".
    "		\$img_tag	= '<img src=\"'.\$image_url.'\" class=\"fancie_darts_member_image alignnone\" alt=\"'.strip_tags(get_the_title()).'\" width=\"'.\$DARTS_MEMBER_IMG_WIDTH.'\" />';"."\n".
    "		echo '<p>' . \$img_tag . '</p>'; ?>"."\n".
    ""."\n".
    "	<table class=\"fancie_darts_member_description dp_sc_table tbl-em7g\">"."\n".
    "		<tbody>"."\n".
    "			<tr>"."\n".
    "				<th class=\"al-c\">Name</th>"."\n".
    "				<td><?php echo post_custom(\$DARTS_MEMBER_TAX_NOTATION); ?>（<?php echo post_custom(\$DARTS_MEMBER_TAX_PRONOUNCIATION); ?>）</td>"."\n".
    "			</tr>"."\n".
    "			<tr>"."\n".
    "				<th class=\"al-c\">Joined</th>"."\n".
    "				<td><?php echo date(\"d/m/Y\", strtotime(post_custom(\$DARTS_MEMBER_TAX_JOINED))); ?></td>"."\n".
    "			</tr>"."\n".
    "			<tr>"."\n".
    "				<th class=\"al-c\">Office</th>"."\n".
    "				<td><?php echo \$html_member_office_list; ?></td>"."\n".
    "			</tr>"."\n".
    "			<tr>"."\n".
    "				<th class=\"al-c\">Post</th>"."\n".
    "				<td><?php echo \$html_member_post_list; ?></td>"."\n".
    "			</tr>"."\n".
    "		</tbody>"."\n".
    "	</table><?php"."\n".
    "		// ***********************************"."\n".
    "		// for FancieDARTS [END]: アイキャッチ画像を指定サイズで表示"."\n".
    "		// ***********************************"."\n".
    "		// Content"."\n",
    // 対象文字列
    $dst_str);
file_put_contents ($WP_THEMES_FANCIE_NOTE_CHILD . '/single-member.php', $dst_str);

$src_str = file_get_contents($WP_THEMES_FANCIE_NOTE . '/inc/scr/related_posts.php');
file_put_contents ($WP_THEMES_FANCIE_NOTE . '/inc/scr/related_posts.php',
    preg_replace(
        '#'.
'// Probably Custom post type[\s\S]*</aside><\?php
	elseif'.'#',
'// Probably Custom post type
		// ***********************************
	elseif',
        $src_str)
);

$src_str = file_get_contents($WP_PLUGINS . '/custom-field-template/custom-field-template.php');
file_put_contents ($WP_PLUGINS . '/custom-field-template/custom-field-template.php',
    str_replace(
        '<p class="label">',
        '<p>',
        $src_str)
);
