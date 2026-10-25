<?php
/**
 * Plugin Name: Vellichor Taxonomies
 * Description: 本棚に猫の蔵書と、著者・出版社・形式・ジャンル・特集・状態・装丁者の分類を登録する
 * Version: 1.5.0
 * Author: M.Hanafusa
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function vellichor_labels( $name ) {
	return array(
		'name'          => $name,
		'singular_name' => $name,
		'add_new_item'  => '新しい' . $name . 'を追加',
		'edit_item'     => $name . 'を編集',
		'view_item'     => $name . 'を表示',
		'update_item'   => $name . 'を更新',
		'search_items'  => $name . 'を検索',
		'not_found'     => $name . 'が見つかりません',
		'back_to_items' => '← ' . $name . '一覧に戻る',
	);
}

function vellichor_register_post_types() {

	// 蔵書
	register_post_type(
		'vellichor_book',
		array(
			'labels'        => array(
				'name'          => '蔵書',
				'singular_name' => '蔵書',
				'menu_name'     => '蔵書目録',
				'add_new'       => '新規登録',
				'add_new_item'  => '新しい蔵書を登録',
				'edit_item'     => '蔵書を編集',
				'view_item'     => '蔵書を表示',
				'all_items'     => '蔵書一覧',
				'search_items'  => '蔵書を検索',
				'not_found'     => '蔵書が見つかりません',
			),
			'public'        => true,
			'has_archive'   => true,
			'rewrite'       => array( 'slug' => 'catalog' ),
			'show_in_rest'  => true,
			'menu_position' => 5,
			'menu_icon'     => 'dashicons-book',
			'supports'      => array( 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields' ),
		)
	);
}
add_action( 'init', 'vellichor_register_post_types' );

function vellichor_register_taxonomies() {

	// 著者
	register_taxonomy(
		'book_writer',
		array( 'post', 'vellichor_book' ),
		array(
			'labels'            => vellichor_labels( '著者' ),
			'hierarchical'      => false,
			'public'            => true,
			'show_in_rest'      => true,
			'show_admin_column' => true,
			'rewrite'           => array( 'slug' => 'writer' ),
		)
	);

	// 出版社
	register_taxonomy(
		'book_publisher',
		array( 'post', 'vellichor_book' ),
		array(
			'labels'            => vellichor_labels( '出版社' ),
			'hierarchical'      => false,
			'public'            => true,
			'show_in_rest'      => true,
			'show_admin_column' => true,
			'rewrite'           => array( 'slug' => 'publisher' ),
		)
	);

	// 形式
	register_taxonomy(
		'book_format',
		array( 'post', 'vellichor_book' ),
		array(
			'labels'            => vellichor_labels( '形式' ),
			'hierarchical'      => true,
			'public'            => true,
			'show_in_rest'      => true,
			'show_admin_column' => true,
			'rewrite'           => array( 'slug' => 'format' ),
		)
	);

	// ジャンル
	register_taxonomy(
		'book_genre',
		array( 'post', 'vellichor_book' ),
		array(
			'labels'            => vellichor_labels( 'ジャンル' ),
			'hierarchical'      => true,
			'public'            => true,
			'show_in_rest'      => true,
			'show_admin_column' => true,
			'rewrite'           => array( 'slug' => 'genre' ),
		)
	);

	// 特集
	register_taxonomy(
		'book_feature',
		array( 'post', 'vellichor_book' ),
		array(
			'labels'            => vellichor_labels( '特集' ),
			'hierarchical'      => true,
			'public'            => true,
			'show_in_rest'      => true,
			'show_admin_column' => true,
			'rewrite'           => array( 'slug' => 'feature' ),
		)
	);
	
    // 状態（刊行予定・所蔵・品切れ・絶版）
    register_taxonomy(
        'book_status',
        array( 'vellichor_book' ),
        array(
            'labels'            => vellichor_labels( '状態' ),
            'hierarchical'      => true,
            'public'            => true,
            'show_in_rest'      => true,
            'show_admin_column' => true,
            'rewrite'           => array( 'slug' => 'status' ),
        )
    );
	
    // 装丁者
    register_taxonomy(
        'book_designer',
        array( 'vellichor_book' ),
        array(
            'labels'            => vellichor_labels( '装丁者' ),
            'hierarchical'      => false,
            'public'            => true,
            'show_in_rest'      => true,
            'show_admin_column' => true,
            'rewrite'           => array( 'slug' => 'designer' ),
        )
    );
}
add_action( 'init', 'vellichor_register_taxonomies' );