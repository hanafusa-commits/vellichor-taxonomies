<?php
/**
 * Plugin Name: Vellichor Taxonomies
 * Description: 本棚に猫の蔵書と、著者・出版社・形式・ジャンル・特集・状態・装丁者・切り口・読み放題/聴き放題の分類、蔵書の書誌情報の項目を登録する
 * Version: 1.8.0
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
	
    // 切り口（「切り口で探す」区画の入口）
    register_taxonomy(
        'book_angle',
        array( 'vellichor_book' ),
        array(
            'labels'            => vellichor_labels( '切り口' ),
            'hierarchical'      => true,
            'public'            => true,
            'show_in_rest'      => true,
            'show_admin_column' => true,
            'rewrite'           => array( 'slug' => 'angle' ),
        )
    );
	
    // 読み放題・聴き放題（Kindle+・Audible。確認日は項目で別に記録）
    register_taxonomy(
        'book_subscription',
        array( 'vellichor_book' ),
        array(
            'labels'            => vellichor_labels( '読み放題・聴き放題' ),
            'hierarchical'      => true,
            'public'            => true,
            'show_in_rest'      => true,
            'show_admin_column' => true,
            'rewrite'           => array( 'slug' => 'subscription' ),
        )
    );
}
add_action( 'init', 'vellichor_register_taxonomies' );

// 蔵書の書誌情報の項目一覧（label：見出し、input：入力欄の形、type：保存する種類）
function vellichor_book_meta_fields() {
    return array(
        '_vellichor_book_number'     => array( 'label' => '蔵書番号',       'input' => 'number', 'type' => 'integer' ),
        '_vellichor_isbn'            => array( 'label' => 'ISBN',           'input' => 'text',   'type' => 'string' ),
        '_vellichor_pub_date'        => array( 'label' => '刊行日',         'input' => 'date',   'type' => 'string' ),
        '_vellichor_selector'        => array( 'label' => '選者・出典',     'input' => 'text',   'type' => 'string' ),
        '_vellichor_kindle_checked'  => array( 'label' => 'Kindle+ 確認日', 'input' => 'date',   'type' => 'string' ),
        '_vellichor_audible_checked' => array( 'label' => 'Audible 確認日', 'input' => 'date',   'type' => 'string' ),
    );
}

function vellichor_register_book_meta() {

    // 蔵書の書誌情報（項目名 => 種類）
    $fields = vellichor_book_meta_fields(
	);
    

    foreach ( $fields as $key => $field ) {
        register_post_meta(
            'vellichor_book',
            $key,
            array(
                'type'          => $field['type'],
                'single'        => true,
                'show_in_rest'  => true,
                'auth_callback' => function () {
                    return current_user_can( 'edit_posts' );
                },
            )
        );
    }
}
add_action( 'init', 'vellichor_register_book_meta' );

// 蔵書の編集画面に「書誌情報」の枠を出す
function vellichor_add_book_meta_box() {
    add_meta_box(
        'vellichor_book_info',            // 枠の内部名
        '書誌情報',                       // 枠の見出し
        'vellichor_render_book_meta_box', // 中身を表示する関数
        'vellichor_book',                 // 出す画面（蔵書だけ）
        'normal',                         // 本文の下に出す
        'high'                            // その中で上のほうに出す
    );
}
add_action( 'add_meta_boxes', 'vellichor_add_book_meta_box' );

// 枠の中身（台帳の項目を一つずつ入力欄にする）
function vellichor_render_book_meta_box( $post ) {
    wp_nonce_field( 'vellichor_save_book_meta', 'vellichor_book_meta_nonce' );
    ?>
    <table class="form-table">
    <?php foreach ( vellichor_book_meta_fields() as $key => $field ) : ?>
        <?php $value = get_post_meta( $post->ID, $key, true ); ?>
        <tr>
            <th><label for="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $field['label'] ); ?></label></th>
            <td><input type="<?php echo esc_attr( $field['input'] ); ?>" id="<?php echo esc_attr( $key ); ?>" name="<?php echo esc_attr( $key ); ?>" value="<?php echo esc_attr( $value ); ?>" class="regular-text"></td>
        </tr>
    <?php endforeach; ?>
    </table>
    <?php
}

// 「書誌情報」の枠に入力された値を保存する
function vellichor_save_book_meta( $post_id ) {

    // 1. 正しい編集画面から送られたか（合言葉の照合）
    if ( ! isset( $_POST['vellichor_book_meta_nonce'] )
        || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['vellichor_book_meta_nonce'] ) ), 'vellichor_save_book_meta' ) ) {
        return;
    }

    // 2. 自動保存のときは何もしない
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }

    // 3. この本を編集する権限があるか
    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }

    // 台帳の項目を一つずつ、形を整えて保存する
    foreach ( vellichor_book_meta_fields() as $key => $field ) {

        $raw = isset( $_POST[ $key ] ) ? trim( wp_unslash( $_POST[ $key ] ) ) : '';

        if ( 'integer' === $field['type'] ) {
            $value = absint( $raw );
        } elseif ( 'date' === $field['input'] ) {
            $value = preg_match( '/^\d{4}-\d{2}-\d{2}$/', $raw ) ? $raw : '';
        } else {
            $value = sanitize_text_field( $raw );
        }

        // 空欄（蔵書番号は 0）なら記録を消す。値があれば保存する
        if ( '' === $value || 0 === $value ) {
            delete_post_meta( $post_id, $key );
        } else {
            update_post_meta( $post_id, $key, $value );
        }
    }
}
add_action( 'save_post_vellichor_book', 'vellichor_save_book_meta' );