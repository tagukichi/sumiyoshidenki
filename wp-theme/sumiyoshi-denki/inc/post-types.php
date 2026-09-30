<?php
/**
 * カスタム投稿タイプ
 *
 * 現行サイトでは ACF で「news（News & Topics）」「jisseki（施工実績）」を登録しています。
 * ACF 側の登録が有効ならそちらを優先し、無い場合（ACF 停止・新規環境）にだけテーマで登録します。
 * どちらの場合も URL は /news/記事名/ ・ /jisseki/記事名/ のまま変わりません。
 *
 * @package sumiyoshi-denki
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function sumiyoshi_register_post_types() {
	if ( ! post_type_exists( 'news' ) ) {
		register_post_type(
			'news',
			array(
				'labels'        => array(
					'name'          => 'News & Topics',
					'singular_name' => 'お知らせ',
					'add_new'       => '新規追加',
					'add_new_item'  => 'お知らせを追加',
					'edit_item'     => 'お知らせを編集',
					'all_items'     => 'お知らせ一覧',
				),
				'public'        => true,
				'has_archive'   => true,
				'show_in_rest'  => true,
				'menu_position' => 5,
				'menu_icon'     => 'dashicons-megaphone',
				'supports'      => array( 'title', 'editor', 'thumbnail', 'excerpt', 'revisions' ),
				'rewrite'       => array(
					'slug'       => 'news',
					'with_front' => false,
				),
			)
		);
	}

	if ( ! post_type_exists( 'jisseki' ) ) {
		register_post_type(
			'jisseki',
			array(
				'labels'        => array(
					'name'          => '施工実績',
					'singular_name' => '施工実績',
					'add_new'       => '新規追加',
					'add_new_item'  => '施工実績を追加',
					'edit_item'     => '施工実績を編集',
					'all_items'     => '施工実績一覧',
				),
				'description'   => 'タイトルは「工事名<br>令和◯年◯月」の形式で入力すると、一覧で工事名と時期が分かれて表示されます。',
				'public'        => true,
				'has_archive'   => true,
				'show_in_rest'  => true,
				'menu_position' => 6,
				'menu_icon'     => 'dashicons-building',
				'supports'      => array( 'title', 'editor', 'thumbnail', 'revisions' ),
				'rewrite'       => array(
					'slug'       => 'jisseki',
					'with_front' => false,
				),
			)
		);
	}
}
// ACF の登録（init の早いタイミング）より後に確認する
add_action( 'init', 'sumiyoshi_register_post_types', 20 );
