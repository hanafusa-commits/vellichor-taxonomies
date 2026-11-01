<?php
/**
 * Plugin Name: Vellichor Taxonomies
 * Description: 本棚に猫の蔵書と、著者・出版社・形式・ジャンル・特集・状態・装丁者・切り口・読み放題/聴き放題の分類、蔵書の書誌情報の項目を登録する
 * Version: 1.11.0
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

// 次の蔵書番号を求める（最後に振った番号と、今ある最大の番号の大きいほう＋1）
function vellichor_next_book_number() {

    $last = (int) get_option( 'vellichor_last_book_number', 0 );

    $query = new WP_Query(
        array(
            'post_type'      => 'vellichor_book',
            'post_status'    => array( 'publish', 'future', 'draft', 'pending', 'private', 'trash' ),
            'meta_key'       => '_vellichor_book_number',
            'orderby'        => 'meta_value_num',
            'order'          => 'DESC',
            'posts_per_page' => 1,
            'fields'         => 'ids',
            'no_found_rows'  => true,
        )
    );

    if ( ! empty( $query->posts ) ) {
        $max  = (int) get_post_meta( $query->posts[0], '_vellichor_book_number', true );
        $last = max( $last, $max );
    }

    return $last + 1;
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
	
    // 公開済みで蔵書番号がなければ、次の番号を振ってメモを更新する
    if ( 'publish' === get_post_status( $post_id )
        && ! get_post_meta( $post_id, '_vellichor_book_number', true ) ) {

        $next = vellichor_next_book_number();
        update_post_meta( $post_id, '_vellichor_book_number', $next );
        update_option( 'vellichor_last_book_number', $next );
    }
}
add_action( 'save_post_vellichor_book', 'vellichor_save_book_meta' );

// レビュー（投稿）に、つなぐ蔵書の ID を持たせる
function vellichor_register_review_meta() {
    register_post_meta(
        'post',
        '_vellichor_book_id',
        array(
            'type'          => 'integer',
            'single'        => true,
            'show_in_rest'  => true,
            'auth_callback' => function () {
                return current_user_can( 'edit_posts' );
            },
        )
    );
}
add_action( 'init', 'vellichor_register_review_meta' );

// レビュー（投稿）の編集画面に「つなぐ蔵書」の枠を出す
function vellichor_add_review_meta_box() {
    add_meta_box(
        'vellichor_review_book',            // 枠の内部名
        'つなぐ蔵書',                       // 枠の見出し
        'vellichor_render_review_meta_box', // 中身を表示する関数
        'post',                             // 出す画面（通常の投稿）
        'side',                             // 右側の設定欄に出す
        'high'                              // その中で上のほうに出す
    );
}
add_action( 'add_meta_boxes', 'vellichor_add_review_meta_box' );

// 枠の中身（蔵書を新しく登録した順に並べたプルダウン）
function vellichor_render_review_meta_box( $post ) {
    wp_nonce_field( 'vellichor_save_review_meta', 'vellichor_review_meta_nonce' );

    $current = (int) get_post_meta( $post->ID, '_vellichor_book_id', true );

    $books = get_posts(
        array(
            'post_type'      => 'vellichor_book',
            'post_status'    => array( 'publish', 'future', 'draft', 'pending', 'private' ),
            'posts_per_page' => -1,
            'orderby'        => 'date',
            'order'          => 'DESC',
        )
    );
    ?>
    <select name="vellichor_book_id" id="vellichor_book_id" style="width:100%;">
        <option value="0">（つながない）</option>
        <?php foreach ( $books as $book ) : ?>
            <?php
            $number = (int) get_post_meta( $book->ID, '_vellichor_book_number', true );
            $label  = $number ? sprintf( '%05d', $number ) : '番号なし';
            if ( 'publish' !== $book->post_status ) {
                $label .= '（下書き）';
            }
            ?>
            <option value="<?php echo esc_attr( $book->ID ); ?>" <?php selected( $current, $book->ID ); ?>>
                <?php echo esc_html( $label . ' ' . get_the_title( $book ) ); ?>
            </option>
        <?php endforeach; ?>
    </select>
    <?php
}

// 「つなぐ蔵書」で選ばれた蔵書を保存する
function vellichor_save_review_meta( $post_id ) {

    // 1. 正しい編集画面から送られたか（合言葉の照合）
    if ( ! isset( $_POST['vellichor_review_meta_nonce'] )
        || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['vellichor_review_meta_nonce'] ) ), 'vellichor_save_review_meta' ) ) {
        return;
    }

    // 2. 自動保存のときは何もしない
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }

    // 3. この投稿を編集する権限があるか
    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }

    $book_id = isset( $_POST['vellichor_book_id'] ) ? absint( $_POST['vellichor_book_id'] ) : 0;

    // 選ばれた ID が本当に蔵書なら保存し、そうでなければ（「つながない」を含む）記録を消す
    if ( $book_id && 'vellichor_book' === get_post_type( $book_id ) ) {
        update_post_meta( $post_id, '_vellichor_book_id', $book_id );
    } else {
        delete_post_meta( $post_id, '_vellichor_book_id' );
    }
}
add_action( 'save_post_post', 'vellichor_save_review_meta' );

// 「2026-11-16」を「2026年11月16日」の形にする
function vellichor_format_date( $date ) {
    list( $year, $month, $day ) = explode( '-', $date );
    return sprintf( '%d年%d月%d日', $year, $month, $day );
}

// 本に付いた分類の用語名を「、」でつないで返す（リンクなし）。なければ空
function vellichor_term_names( $post_id, $taxonomy ) {
    $names = wp_get_post_terms( $post_id, $taxonomy, array( 'fields' => 'names' ) );
    if ( is_wp_error( $names ) || empty( $names ) ) {
        return '';
    }
    return esc_html( implode( '、', $names ) );
}

// [vellichor_book_info]：レビューにつないだ蔵書の書誌情報を表示する
function vellichor_book_info_shortcode() {

    // 表示中の記事につないだ蔵書を調べる。なければ何も出さない
    $book_id = (int) get_post_meta( get_the_ID(), '_vellichor_book_id', true );
    if ( ! $book_id || 'vellichor_book' !== get_post_type( $book_id ) ) {
        return '';
    }

    // 見出し => 表示する中身（HTML）。値がある項目だけ、表の順に足していく
    $rows = array();

    // 蔵書番号
    $number = (int) get_post_meta( $book_id, '_vellichor_book_number', true );
    if ( $number ) {
        $rows['蔵書番号'] = esc_html( sprintf( '%05d', $number ) );
    }

    // 書名（公開済みの蔵書なら、蔵書のページへのリンクにする）
    $title = esc_html( get_the_title( $book_id ) );
    if ( 'publish' === get_post_status( $book_id ) ) {
        $title = '<a href="' . esc_url( get_permalink( $book_id ) ) . '">' . $title . '</a>';
    }
    $rows['書名'] = $title;

    // 著者・装丁者・出版社（それぞれの一覧ページへのリンク）
    $linked = array(
        'book_writer'    => '著者',
        'book_designer'  => '装丁者',
        'book_publisher' => '出版社',
    );
    foreach ( $linked as $taxonomy => $label ) {
        $list = get_the_term_list( $book_id, $taxonomy, '', '、' );
        if ( $list && ! is_wp_error( $list ) ) {
            $rows[ $label ] = $list;
        }
    }

    // 刊行日
    $pub_date = get_post_meta( $book_id, '_vellichor_pub_date', true );
    if ( $pub_date ) {
        $rows['刊行日'] = esc_html( vellichor_format_date( $pub_date ) );
    }

    // 形式
    $format = vellichor_term_names( $book_id, 'book_format' );
    if ( $format ) {
        $rows['形式'] = $format;
    }

    // ISBN
    $isbn = get_post_meta( $book_id, '_vellichor_isbn', true );
    if ( $isbn ) {
        $rows['ISBN'] = esc_html( $isbn );
    }

    // 状態
    $status = vellichor_term_names( $book_id, 'book_status' );
    if ( $status ) {
        $rows['状態'] = $status;
    }

    // 読み放題・聴き放題（サービスごとの確認日を添える）
    $checked_keys = array(
        'kindle-plus' => '_vellichor_kindle_checked',
        'audible'     => '_vellichor_audible_checked',
    );
    $services = wp_get_post_terms( $book_id, 'book_subscription' );
    $parts    = array();
    if ( ! is_wp_error( $services ) ) {
        foreach ( $services as $term ) {
            $text = esc_html( $term->name );
            if ( isset( $checked_keys[ $term->slug ] ) ) {
                $checked = get_post_meta( $book_id, $checked_keys[ $term->slug ], true );
                if ( $checked ) {
                    $text .= '（' . esc_html( vellichor_format_date( $checked ) ) . '確認）';
                }
            }
            $parts[] = $text;
        }
    }
    if ( $parts ) {
        $rows['読み放題・聴き放題'] = implode( '、', $parts );
    }

    // 選者・出典
    $selector = get_post_meta( $book_id, '_vellichor_selector', true );
    if ( $selector ) {
        $rows['選者・出典'] = esc_html( $selector );
    }

    // 見出しと中身を、図書カードのような一覧（dl）に組み立てて返す
    $html = '<dl class="vellichor-book-info">';
    foreach ( $rows as $label => $value ) {
        $html .= '<dt>' . esc_html( $label ) . '</dt><dd>' . $value . '</dd>';
    }
    $html .= '</dl>';

    return $html;
}
add_shortcode( 'vellichor_book_info', 'vellichor_book_info_shortcode' );