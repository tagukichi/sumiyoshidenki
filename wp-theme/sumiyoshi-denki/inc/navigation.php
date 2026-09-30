<?php
/**
 * ナビゲーション
 *
 * 外観 > メニュー でメニューを設定すればそれを表示し、
 * 未設定の場合は現行サイトと同じ構成の既定メニューを表示する。
 *
 * @package sumiyoshi-denki
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * 既定メニューの項目
 * children を持つ項目はドロップダウン（スマホでは展開表示）になる。
 */
function sumiyoshi_default_menu_items() {
	return array(
		array(
			'title'  => 'トップページ',
			'url'    => home_url( '/' ),
			'active' => is_front_page(),
		),
		array(
			'title'  => 'News & Topics',
			'url'    => sumiyoshi_url( '/news/' ),
			'active' => is_post_type_archive( 'news' ) || is_singular( 'news' ),
		),
		array(
			'title'    => '会社案内',
			'url'      => sumiyoshi_url( '/information/' ),
			'active'   => sumiyoshi_is_information_page(),
			'children' => array(
				array(
					'title' => '代表挨拶',
					'url'   => sumiyoshi_url( '/information/greeting/' ),
				),
				array(
					'title' => '会社概要',
					'url'   => sumiyoshi_url( '/information/company/' ),
				),
				array(
					'title' => '沿　革',
					'url'   => sumiyoshi_url( '/information/history/' ),
				),
				array(
					'title' => 'アクセス',
					'url'   => sumiyoshi_url( '/information/access/' ),
				),
			),
		),
		array(
			'title'  => '施工実績',
			'url'    => sumiyoshi_url( '/jisseki/' ),
			'active' => is_post_type_archive( 'jisseki' ) || is_singular( 'jisseki' ),
		),
		array(
			'title'  => '求人情報',
			'url'    => sumiyoshi_url( '/recruit/' ),
			'active' => is_page( 'recruit' ),
		),
		array(
			'title'  => 'お問い合わせ',
			'url'    => sumiyoshi_url( '/contact/' ),
			'active' => is_page( 'contact' ),
		),
	);
}

/** 「会社案内」配下（会社案内ページ自身を含む）を表示中か */
function sumiyoshi_is_information_page() {
	if ( ! is_page() ) {
		return false;
	}
	$page = get_queried_object();
	if ( 'information' === $page->post_name ) {
		return true;
	}
	foreach ( get_post_ancestors( $page ) as $ancestor_id ) {
		if ( 'information' === get_post_field( 'post_name', $ancestor_id ) ) {
			return true;
		}
	}
	return false;
}

/** グローバルナビ（メニュー未設定時の既定表示） */
function sumiyoshi_default_global_menu() {
	echo '<ul class="global-nav__list">';
	foreach ( sumiyoshi_default_menu_items() as $item ) {
		$has_children = ! empty( $item['children'] );
		$current      = $item['active'] ? ' aria-current="page"' : '';
		printf(
			'<li%s><a href="%s"%s>%s</a>',
			$has_children ? ' class="has-dropdown"' : '',
			esc_url( $item['url'] ),
			$current, // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			esc_html( $item['title'] )
		);
		if ( $has_children ) {
			echo '<ul class="dropdown">';
			foreach ( $item['children'] as $child ) {
				printf( '<li><a href="%s">%s</a></li>', esc_url( $child['url'] ), esc_html( $child['title'] ) );
			}
			echo '</ul>';
		}
		echo '</li>';
	}
	echo '</ul>';
}

/** フッターナビ（メニュー未設定時の既定表示：2列） */
function sumiyoshi_default_footer_menu() {
	$columns = array(
		array(
			'トップページ'      => home_url( '/' ),
			'News & Topics' => sumiyoshi_url( '/news/' ),
			'施工実績'         => sumiyoshi_url( '/jisseki/' ),
			'求人情報'         => sumiyoshi_url( '/recruit/' ),
		),
		array(
			'会社案内'   => sumiyoshi_url( '/information/' ),
			'代表挨拶'   => sumiyoshi_url( '/information/greeting/' ),
			'会社概要'   => sumiyoshi_url( '/information/company/' ),
			'沿革'     => sumiyoshi_url( '/information/history/' ),
			'アクセス'   => sumiyoshi_url( '/information/access/' ),
			'お問い合わせ' => sumiyoshi_url( '/contact/' ),
		),
	);
	foreach ( $columns as $links ) {
		echo '<ul>';
		foreach ( $links as $title => $url ) {
			printf( '<li><a href="%s">%s</a></li>', esc_url( $url ), esc_html( $title ) );
		}
		echo '</ul>';
	}
}

/**
 * 管理画面で設定したメニューでも、
 * News・施工実績の記事ページではそれぞれの一覧メニューを現在地として表示する
 */
function sumiyoshi_nav_menu_css_class( $classes, $item ) {
	foreach ( array( 'news', 'jisseki' ) as $post_type ) {
		if ( is_singular( $post_type ) && untrailingslashit( $item->url ) === untrailingslashit( (string) get_post_type_archive_link( $post_type ) ) ) {
			$classes[] = 'current-menu-ancestor';
		}
	}
	return $classes;
}
add_filter( 'nav_menu_css_class', 'sumiyoshi_nav_menu_css_class', 10, 2 );
