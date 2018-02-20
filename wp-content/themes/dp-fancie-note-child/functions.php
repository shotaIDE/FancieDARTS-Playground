<?php
// 子テーマの有効化
add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles' );
function theme_enqueue_styles() {
    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
}

// 投稿タイプの追加
add_action( 'init', 'create_post_type' );
function create_post_type() {
    // 社員情報の追加
    register_post_type( 'member',
        array(
            'labels' => array('name' => __( '社員情報' )),
            'public' => true,
            'menu_position' => 5)
    );
    // 社員情報のカテゴリ有効化
    register_taxonomy(
        'member-cat',
        'member',
        array(
            'hierarchical' => true,
            'update_count_callback' => '_update_post_term_count',
            'label' => '社員の所属',
            'singular_label' => '社員の所属',
            'public' => true,
            'show_ui' => true)
    );
}
