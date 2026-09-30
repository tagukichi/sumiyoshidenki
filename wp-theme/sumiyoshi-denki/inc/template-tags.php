<?php
/**
 * テンプレート用のヘルパー関数
 *
 * @package sumiyoshi-denki
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * メディアライブラリ（wp-content/uploads）内のファイルURL
 *
 * @param string $path 例: '2025/03/logo251106.svg'
 */
function sumiyoshi_upload_url( $path ) {
	$uploads = wp_get_upload_dir();
	return $uploads['baseurl'] . '/' . ltrim( $path, '/' );
}

/** ロゴ画像URL（外観 > カスタマイズ > サイト基本情報 で変更可能） */
function sumiyoshi_logo_url() {
	$logo_id = get_theme_mod( 'custom_logo' );
	if ( $logo_id ) {
		$url = wp_get_attachment_image_url( $logo_id, 'full' );
		if ( $url ) {
			return $url;
		}
	}
	return sumiyoshi_upload_url( '2025/03/logo251106.svg' );
}

/** サイト内URL（'/information/company/' → https://…/information/company/） */
function sumiyoshi_url( $path = '/' ) {
	return home_url( user_trailingslashit( '/' . trim( $path, '/' ) ) );
}

/** tel: リンク用の番号 */
function sumiyoshi_tel_href() {
	return 'tel:' . preg_replace( '/[^0-9+]/', '', SUMIYOSHI_TEL );
}

/**
 * 施工実績のタイトル「工事名<br>令和◯年◯月」を [工事名, 時期] に分ける
 *
 * @return string[] array( 0 => 工事名, 1 => 時期（無ければ ''） )
 */
function sumiyoshi_split_title( $title ) {
	$parts = preg_split( '/<br\s*\/?>/i', (string) $title, 2 );
	return array(
		trim( wp_strip_all_tags( $parts[0] ) ),
		isset( $parts[1] ) ? trim( wp_strip_all_tags( $parts[1] ) ) : '',
	);
}

/** タグを除いたタイトル（<br> は空白に） */
function sumiyoshi_plain_title( $title ) {
	return trim( wp_strip_all_tags( preg_replace( '/<br\s*\/?>/i', ' ', (string) $title ) ) );
}

/**
 * 施工実績のギャラリー画像
 *
 * 旧サイトでは SiteOrigin Page Builder（panels_data）で写真を配置していたため、
 * そのプラグインが無効な場合はここから写真を取り出して表示する。
 *
 * @return string[] 画像URLの配列
 */
function sumiyoshi_jisseki_gallery( $post_id ) {
	if ( class_exists( 'SiteOrigin_Panels' ) ) {
		return array(); // プラグインが本文として描画する
	}
	$data = get_post_meta( $post_id, 'panels_data', true );
	if ( ! is_array( $data ) || empty( $data['widgets'] ) ) {
		return array();
	}
	$urls = array();
	foreach ( $data['widgets'] as $widget ) {
		if ( ! empty( $widget['attachment_id'] ) ) {
			$url = wp_get_attachment_image_url( (int) $widget['attachment_id'], 'large' );
			if ( $url ) {
				$urls[] = $url;
				continue;
			}
		}
		if ( ! empty( $widget['url'] ) ) {
			$urls[] = $widget['url'];
		}
	}
	return array_values( array_unique( $urls ) );
}

/**
 * 入社日から勤続年数（満年数）を計算
 *
 * @param string $date 'Ymd'（ACF の日付フィールド形式）または 'Y-m-d'
 */
function sumiyoshi_tenure_years( $date ) {
	$date = preg_replace( '/[^0-9]/', '', (string) $date );
	if ( 8 !== strlen( $date ) ) {
		return null;
	}
	$hire = DateTime::createFromFormat( 'Ymd', $date, wp_timezone() );
	if ( ! $hire ) {
		return null;
	}
	return $hire->diff( new DateTime( 'now', wp_timezone() ) )->y;
}

/**
 * 勤続年数の表示用文字列（例: '56年'）
 * ページの ACF フィールド（入社日）を優先し、未設定なら $fallback の日付を使う。
 */
function sumiyoshi_tenure_label( $post_id, $field, $fallback ) {
	$date  = get_post_meta( $post_id, $field, true );
	$years = sumiyoshi_tenure_years( $date ? $date : $fallback );
	return null === $years ? '' : $years . '年';
}

/**
 * 下層ページのタイトル帯＋パンくず
 *
 * @param string $label 英字ラベル（例: 'Company'）
 * @param string $title 見出し（プレーンテキスト）
 */
function sumiyoshi_page_heading( $label, $title ) {
	get_template_part(
		'template-parts/page-heading',
		null,
		array(
			'label' => $label,
			'title' => $title,
		)
	);
}

/**
 * パンくずリストの項目を返す
 *
 * @return array[] array( array( 'title' => …, 'url' => …|null ), … )
 */
function sumiyoshi_breadcrumb_items() {
	$items = array(
		array(
			'title' => 'ホーム',
			'url'   => home_url( '/' ),
		),
	);

	if ( is_page() ) {
		$page = get_queried_object();
		foreach ( array_reverse( get_post_ancestors( $page ) ) as $ancestor_id ) {
			$items[] = array(
				'title' => get_the_title( $ancestor_id ),
				'url'   => get_permalink( $ancestor_id ),
			);
		}
		$items[] = array( 'title' => get_the_title( $page ) );
	} elseif ( is_singular() ) {
		$post      = get_queried_object();
		$post_type = get_post_type( $post );
		if ( in_array( $post_type, array( 'news', 'jisseki' ), true ) ) {
			$items[] = array(
				'title' => sumiyoshi_post_type_label( $post_type ),
				'url'   => get_post_type_archive_link( $post_type ),
			);
		} elseif ( 'post' === $post_type && get_option( 'page_for_posts' ) ) {
			$items[] = array(
				'title' => get_the_title( get_option( 'page_for_posts' ) ),
				'url'   => get_permalink( get_option( 'page_for_posts' ) ),
			);
		}
		$items[] = array( 'title' => sumiyoshi_plain_title( get_the_title( $post ) ) );
	} elseif ( is_post_type_archive() ) {
		$items[] = array( 'title' => sumiyoshi_post_type_label( get_query_var( 'post_type' ) ) );
	} elseif ( is_home() ) {
		$items[] = array( 'title' => sumiyoshi_archive_title() );
	} elseif ( is_archive() ) {
		$items[] = array( 'title' => wp_strip_all_tags( get_the_archive_title() ) );
	} elseif ( is_search() ) {
		$items[] = array( 'title' => '検索結果' );
	} elseif ( is_404() ) {
		$items[] = array( 'title' => 'ページが見つかりません' );
	}

	return $items;
}

/** 投稿タイプの表示名 */
function sumiyoshi_post_type_label( $post_type ) {
	$labels = array(
		'news'    => 'News & Topics',
		'jisseki' => '施工実績',
		'post'    => 'ブログ',
	);
	if ( is_array( $post_type ) ) {
		$post_type = reset( $post_type );
	}
	return isset( $labels[ $post_type ] ) ? $labels[ $post_type ] : get_post_type_object( $post_type )->labels->name;
}

/** 投稿タイプの英字ラベル（タイトル帯の小見出し） */
function sumiyoshi_post_type_en( $post_type ) {
	$labels = array(
		'news'    => 'News & Topics',
		'jisseki' => 'Works',
		'post'    => 'Blog',
	);
	return isset( $labels[ $post_type ] ) ? $labels[ $post_type ] : 'Archive';
}

/** ブログ一覧・アーカイブの見出し */
function sumiyoshi_archive_title() {
	if ( is_post_type_archive() ) {
		return sumiyoshi_post_type_label( get_query_var( 'post_type' ) );
	}
	if ( is_home() ) {
		$page_for_posts = get_option( 'page_for_posts' );
		return $page_for_posts ? get_the_title( $page_for_posts ) : 'ブログ';
	}
	if ( is_category() || is_tag() || is_tax() ) {
		return single_term_title( '', false );
	}
	if ( is_date() ) {
		return wp_strip_all_tags( get_the_archive_title() );
	}
	if ( is_search() ) {
		/* translators: %s: 検索キーワード */
		return sprintf( '「%s」の検索結果', get_search_query() );
	}
	return wp_strip_all_tags( get_the_archive_title() );
}

/** ページ送り */
function sumiyoshi_pagination() {
	the_posts_pagination(
		array(
			'mid_size'           => 1,
			'prev_text'          => '‹<span class="screen-reader-text">前のページ</span>',
			'next_text'          => '<span class="screen-reader-text">次のページ</span>›',
			'screen_reader_text' => 'ページ送り',
		)
	);
}
