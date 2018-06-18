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
