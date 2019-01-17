<?php
// 子テーマの有効化
add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles' );
function theme_enqueue_styles() {
    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
}

// 社員情報
$DARTS_MEMBER_TAX_NOTATION = 'member_notation'; // 名前・表記
$DARTS_MEMBER_TAX_PRONOUNCIATION = 'member_pronunciation'; // 名前・読み方
$DARTS_MEMBER_TAX_JOINED = 'member_joined'; // 入社日
$DARTS_MEMBER_TAX_OFFICE = 'member_office'; // 営業所
$DARTS_MEMBER_TAX_POST = 'member_post'; // 所属部署

$DARTS_MEMBER_IMG_WIDTH = 360; //トップに表示する写真のサイズ

// 閲覧数ねつ造関数
function darts_post_views_upgrader($post_ID, $meta_key, $current_count) {
    $DARTS_POST_VIEWS_UPGRADER = array(
        32566 => array( 57, 23 ),
    );
    $DARTS_POST_VIEWS_UPGRADER_ALLOWED_KEYS = array(
        'post_views_count',
        'post_views_count_monthly',
    );

    if (!in_array($meta_key, $DARTS_POST_VIEWS_UPGRADER_ALLOWED_KEYS, true)) {
        return false;
    }
    if (!array_key_exists($post_ID, $DARTS_POST_VIEWS_UPGRADER)) {
        return false;
    }
    $post_views_increase = $DARTS_POST_VIEWS_UPGRADER[$post_ID][0];
    $post_views_threshold = $DARTS_POST_VIEWS_UPGRADER[$post_ID][1];
    if ($current_count >= $post_views_threshold) {
        return false;
    }
    update_post_meta($post_ID, $meta_key, $current_count + $post_views_increase);
    return true;
}
