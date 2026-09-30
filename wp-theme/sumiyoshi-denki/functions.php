<?php
/**
 * 住吉電機株式会社 テーマ
 *
 * @package sumiyoshi-denki
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'SUMIYOSHI_VERSION', '1.0.0' );

/** 代表電話番号（ヘッダー・求人ページなどで使用） */
define( 'SUMIYOSHI_TEL', '044-755-6161' );

/**
 * Contact Form 7 のフォームID（現行サイトの設定値）。
 * フォームを作り直した場合は wp-config.php 等で上書きしてください。
 */
if ( ! defined( 'SUMIYOSHI_CF7_CONTACT' ) ) {
	define( 'SUMIYOSHI_CF7_CONTACT', 362 );  // お問い合わせフォーム
}
if ( ! defined( 'SUMIYOSHI_CF7_RECRUIT' ) ) {
	define( 'SUMIYOSHI_CF7_RECRUIT', 394 );  // 求人用フォーム
}

require get_template_directory() . '/inc/post-types.php';
require get_template_directory() . '/inc/template-tags.php';
require get_template_directory() . '/inc/navigation.php';
require get_template_directory() . '/inc/shortcodes.php';

/**
 * テーマの基本設定
 */
function sumiyoshi_setup() {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'align-wide' );
	add_theme_support( 'html5', array( 'search-form', 'gallery', 'caption', 'style', 'script', 'navigation-widgets' ) );
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 92,
			'width'       => 400,
			'flex-height' => true,
			'flex-width'  => true,
		)
	);

	// ブロックエディタでも本文と同じ見出し・装飾で表示する
	add_theme_support( 'editor-styles' );
	add_editor_style( array( sumiyoshi_font_url(), 'assets/css/editor-style.css' ) );

	// エディタの色パレットをサイトの配色に合わせる
	add_theme_support(
		'editor-color-palette',
		array(
			array( 'name' => 'ネイビー', 'slug' => 'primary', 'color' => '#0d2c54' ),
			array( 'name' => 'ブルー', 'slug' => 'blue', 'color' => '#1565c0' ),
			array( 'name' => 'アンバー', 'slug' => 'accent', 'color' => '#f2a900' ),
			array( 'name' => 'レッド', 'slug' => 'red', 'color' => '#d64545' ),
			array( 'name' => 'グレー', 'slug' => 'gray', 'color' => '#5f6b76' ),
			array( 'name' => 'ライトグレー', 'slug' => 'light-gray', 'color' => '#f4f7fa' ),
			array( 'name' => 'ホワイト', 'slug' => 'white', 'color' => '#ffffff' ),
		)
	);

	register_nav_menus(
		array(
			'global' => 'グローバルナビ（ヘッダー）',
			'footer' => 'フッターナビ',
		)
	);
}
add_action( 'after_setup_theme', 'sumiyoshi_setup' );

/** 記事本文の最大幅（埋め込みなどの基準） */
function sumiyoshi_content_width() {
	$GLOBALS['content_width'] = 860;
}
add_action( 'after_setup_theme', 'sumiyoshi_content_width', 0 );

function sumiyoshi_font_url() {
	return 'https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;500;700&display=swap';
}

/**
 * CSS / JS の読み込み
 */
function sumiyoshi_enqueue_assets() {
	$dir = get_template_directory();

	wp_enqueue_style( 'sumiyoshi-fonts', sumiyoshi_font_url(), array(), null );
	wp_enqueue_style(
		'sumiyoshi-main',
		get_theme_file_uri( 'assets/css/style.css' ),
		array( 'sumiyoshi-fonts' ),
		(string) filemtime( $dir . '/assets/css/style.css' )
	);
	wp_enqueue_script(
		'sumiyoshi-main',
		get_theme_file_uri( 'assets/js/main.js' ),
		array(),
		(string) filemtime( $dir . '/assets/js/main.js' ),
		array(
			'in_footer' => true,
			'strategy'  => 'defer',
		)
	);
}
add_action( 'wp_enqueue_scripts', 'sumiyoshi_enqueue_assets' );

/** Google Fonts への事前接続 */
function sumiyoshi_resource_hints( $urls, $relation_type ) {
	if ( 'preconnect' === $relation_type ) {
		$urls[] = 'https://fonts.googleapis.com';
		$urls[] = array(
			'href'        => 'https://fonts.gstatic.com',
			'crossorigin' => 'anonymous',
		);
	}
	return $urls;
}
add_filter( 'wp_resource_hints', 'sumiyoshi_resource_hints', 10, 2 );

/**
 * 一覧の表示件数
 * 施工実績は現行サイトと同じ 8件/ページ（4列×2段）
 */
function sumiyoshi_pre_get_posts( $query ) {
	if ( is_admin() || ! $query->is_main_query() ) {
		return;
	}
	if ( $query->is_post_type_archive( 'jisseki' ) ) {
		$query->set( 'posts_per_page', 8 );
	} elseif ( $query->is_post_type_archive( 'news' ) ) {
		$query->set( 'posts_per_page', 20 );
	}
}
add_action( 'pre_get_posts', 'sumiyoshi_pre_get_posts' );

/**
 * タイトルに含まれる <br>（施工実績の「工事名<br>時期」）を
 * ブラウザのタブ等では空白に置き換える
 */
function sumiyoshi_document_title_parts( $parts ) {
	if ( isset( $parts['title'] ) ) {
		$parts['title'] = trim( wp_strip_all_tags( preg_replace( '/<br\s*\/?>/i', ' ', $parts['title'] ) ) );
	}
	return $parts;
}
add_filter( 'document_title_parts', 'sumiyoshi_document_title_parts' );

/** 抜粋の末尾 */
add_filter(
	'excerpt_more',
	function () {
		return '…';
	}
);

/**
 * このテーマがデザインを持つページは、テーマのテンプレートを優先する。
 *
 * 旧サイトの Elementor（ページテンプレート「Elementor Canvas / 全幅」や
 * Theme Builder のシングル・アーカイブ）が残っていても新デザインで表示されるようにするため。
 * 不要な場合は add_filter( 'sumiyoshi_force_theme_templates', '__return_false' ); で無効化できます。
 */
function sumiyoshi_template_include( $template ) {
	if ( ! apply_filters( 'sumiyoshi_force_theme_templates', true ) ) {
		return $template;
	}

	$candidates = array();
	if ( is_front_page() ) {
		$candidates[] = 'front-page.php';
	} elseif ( is_page() ) {
		$candidates[] = 'page-' . get_post_field( 'post_name', get_queried_object_id() ) . '.php';
	} elseif ( is_singular( 'jisseki' ) ) {
		$candidates[] = 'single-jisseki.php';
	} elseif ( is_singular( array( 'news', 'post' ) ) ) {
		$candidates[] = 'single.php';
	} elseif ( is_post_type_archive( 'jisseki' ) ) {
		$candidates[] = 'archive-jisseki.php';
	} elseif ( is_post_type_archive( 'news' ) || is_home() || is_category() || is_tag() || is_date() ) {
		$candidates[] = 'archive.php';
	}

	if ( $candidates ) {
		$located = locate_template( $candidates );
		if ( $located ) {
			return $located;
		}
	}
	return $template;
}
add_filter( 'template_include', 'sumiyoshi_template_include', 999 );

/**
 * SEOプラグイン（Yoast 等）が無い場合のみ meta description を出力
 */
function sumiyoshi_meta_description() {
	if ( defined( 'WPSEO_VERSION' ) || defined( 'AIOSEO_VERSION' ) || class_exists( 'RankMath' ) || defined( 'SEOPRESS_VERSION' ) ) {
		return;
	}
	$desc = '';
	if ( is_front_page() ) {
		$desc = '住吉電機株式会社は、川崎市で電気工事を営んで半世紀以上。技術力と信頼でその間に培ったお客様に安心をお届けします。電気配線工事・計装工事・消防施設工事・電気通信工事。';
	} elseif ( is_singular() ) {
		$post = get_queried_object();
		// 日本語は単語区切りが無いため文字数で切り詰める
		$desc = has_excerpt( $post ) ? get_the_excerpt( $post ) : wp_html_excerpt( strip_shortcodes( $post->post_content ), 110, '…' );
		if ( '' === trim( $desc ) ) {
			$desc = sumiyoshi_plain_title( get_the_title( $post ) ) . '｜住吉電機株式会社';
		}
	} elseif ( is_post_type_archive() ) {
		$desc = post_type_archive_title( '', false ) . '｜住吉電機株式会社';
	}
	if ( $desc ) {
		printf( '<meta name="description" content="%s">' . "\n", esc_attr( $desc ) );
	}
}
add_action( 'wp_head', 'sumiyoshi_meta_description', 1 );

/** テーマ切り替え時にパーマリンク（/news/ /jisseki/）を更新 */
add_action( 'after_switch_theme', 'flush_rewrite_rules' );
