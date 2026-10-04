<?php
/**
 * Plugin Name: Vellichor Taxonomies
 * Description: 本棚に猫の著者・出版社・形式・ジャンル・特集の分類を登録する
 * Version: 1.1.1
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

function vellichor_register_taxonomies() {

	// 著者
	register_taxonomy(
		'book_writer',
		'post',
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
		'post',
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
		'post',
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
		'post',
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
		'post',
		array(
			'labels'            => vellichor_labels( '特集' ),
			'hierarchical'      => true,
			'public'            => true,
			'show_in_rest'      => true,
			'show_admin_column' => true,
			'rewrite'           => array( 'slug' => 'feature' ),
		)
	);
}
add_action( 'init', 'vellichor_register_taxonomies' );