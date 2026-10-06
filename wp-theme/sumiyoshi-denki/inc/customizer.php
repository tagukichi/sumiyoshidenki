<?php
/**
 * テーマ設定（外観 > カスタマイズ > サイト設定）
 *
 * - トップページの画像（1枚。2枚以上設定するとスライドショー）
 * - 画像の表示位置（PC・スマホ別）
 * - Googleマップ（アクセス・求人ページ）
 * - ストリートビュー（アクセスページ）
 *
 * @package sumiyoshi-denki
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/** トップ画像の既定（現行サイトの1枚目） */
define( 'SUMIYOSHI_DEFAULT_HERO', '2025/03/Group-3744.png' );

/** 地図の既定：住吉電機（株）の Googleマップ共有URL */
define( 'SUMIYOSHI_DEFAULT_MAP', 'https://www.google.com/maps/place/%E4%BD%8F%E5%90%89%E9%9B%BB%E6%A9%9F%EF%BC%88%E6%A0%AA%EF%BC%89/@35.569508,139.6334201,17z/data=!3m1!4b1!4m6!3m5!1s0x6018f591e1e52d8d:0x4b8ca0e4c14c615b!8m2!3d35.569508!4d139.635995!16s%2Fg%2F1tgnx7v1' );

/** ストリートビューの既定（現行サイトのアクセスページと同じ） */
define( 'SUMIYOSHI_DEFAULT_STREETVIEW', 'https://www.google.com/maps/embed?pb=!4v1743146114250!6m8!1m7!1sKf5jSiNYm6P3JBHiGM0gDw!2m2!1d35.56946006058778!2d139.6358572797338!3f35.39!4f52.41999999999999!5f1.009049021386966' );

/** 画像位置の選択肢（横方向） */
function sumiyoshi_hero_position_choices() {
	return array(
		'0%'   => '左端',
		'25%'  => '左寄り',
		'50%'  => '中央',
		'75%'  => '右寄り',
		'100%' => '右端',
	);
}

function sumiyoshi_customize_register( $wp_customize ) {
	$wp_customize->add_section(
		'sumiyoshi_settings',
		array(
			'title'       => 'サイト設定（住吉電機）',
			'priority'    => 30,
			'description' => 'トップページの画像と、アクセスページの地図を設定します。',
		)
	);

	// --- トップページの画像 ---
	$labels = array(
		1 => array( 'トップページの画像', '未設定の場合は現行サイトの画像を表示します。横長（4:3〜16:9）の写真がおすすめです。' ),
		2 => array( 'トップページの画像（2枚目・任意）', '2枚目以降を設定すると、6秒ごとに切り替わるスライドショーになります。' ),
		3 => array( 'トップページの画像（3枚目・任意）', '' ),
	);
	foreach ( $labels as $n => $label ) {
		$wp_customize->add_setting(
			"sumiyoshi_hero_image_{$n}",
			array(
				'default'           => '',
				'sanitize_callback' => 'esc_url_raw',
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Image_Control(
				$wp_customize,
				"sumiyoshi_hero_image_{$n}",
				array(
					'label'       => $label[0],
					'description' => $label[1],
					'section'     => 'sumiyoshi_settings',
				)
			)
		);
	}

	$positions = array(
		'sumiyoshi_hero_pos_pc' => array( '画像の表示位置（PC）', '50%', '画面に収まらない部分は切り取られます。見せたい部分に合わせて選んでください。' ),
		'sumiyoshi_hero_pos_sp' => array( '画像の表示位置（スマホ）', '100%', 'スマホは縦長のため、写真の一部だけが表示されます。人物が写っている側を選んでください。' ),
	);
	foreach ( $positions as $id => $pos ) {
		$wp_customize->add_setting(
			$id,
			array(
				'default'           => $pos[1],
				'sanitize_callback' => 'sumiyoshi_sanitize_hero_position',
			)
		);
		$wp_customize->add_control(
			$id,
			array(
				'type'        => 'select',
				'label'       => $pos[0],
				'description' => $pos[2],
				'section'     => 'sumiyoshi_settings',
				'choices'     => sumiyoshi_hero_position_choices(),
			)
		);
	}

	// --- 地図 ---
	$wp_customize->add_setting(
		'sumiyoshi_map_url',
		array(
			'default'           => SUMIYOSHI_DEFAULT_MAP,
			'sanitize_callback' => 'sumiyoshi_sanitize_map_input',
		)
	);
	$wp_customize->add_control(
		'sumiyoshi_map_url',
		array(
			'type'        => 'textarea',
			'label'       => 'Googleマップ（アクセス・求人ページ）',
			'description' => 'Googleマップで会社を表示し「共有」→「リンクをコピー」したURLを貼り付けてください。「地図を埋め込む」のHTML（&lt;iframe …&gt;）を貼り付けることもできます。',
			'section'     => 'sumiyoshi_settings',
		)
	);

	$wp_customize->add_setting(
		'sumiyoshi_streetview_url',
		array(
			'default'           => SUMIYOSHI_DEFAULT_STREETVIEW,
			'sanitize_callback' => 'sumiyoshi_sanitize_map_input',
		)
	);
	$wp_customize->add_control(
		'sumiyoshi_streetview_url',
		array(
			'type'        => 'textarea',
			'label'       => 'ストリートビュー（アクセスページ・任意）',
			'description' => 'ストリートビューの「共有」→「地図を埋め込む」のHTMLまたはURL。空欄にすると表示しません。',
			'section'     => 'sumiyoshi_settings',
		)
	);
}
add_action( 'customize_register', 'sumiyoshi_customize_register' );

function sumiyoshi_sanitize_hero_position( $value ) {
	return array_key_exists( $value, sumiyoshi_hero_position_choices() ) ? $value : '50%';
}

/** URL または &lt;iframe&gt; コードを受け付け、埋め込み用のURLだけを保存する */
function sumiyoshi_sanitize_map_input( $value ) {
	$value = trim( (string) $value );
	if ( '' === $value ) {
		return '';
	}
	if ( preg_match( '/<iframe[^>]+src=["\']([^"\']+)["\']/i', $value, $m ) ) {
		$value = html_entity_decode( $m[1] );
	}
	return esc_url_raw( sumiyoshi_expand_map_short_url( $value ), array( 'https', 'http' ) );
}

/**
 * 「共有 → リンクをコピー」の短縮URL（maps.app.goo.gl / goo.gl/maps）を
 * 座標入りの通常URLに展開する（保存時に1回だけ実行）
 */
function sumiyoshi_expand_map_short_url( $url ) {
	for ( $i = 0; $i < 4; $i++ ) {
		$host = (string) wp_parse_url( $url, PHP_URL_HOST );
		if ( ! in_array( $host, array( 'maps.app.goo.gl', 'goo.gl' ), true ) ) {
			break;
		}
		$response = wp_remote_head(
			$url,
			array(
				'redirection' => 0,
				'timeout'     => 8,
			)
		);
		$location = is_wp_error( $response ) ? '' : wp_remote_retrieve_header( $response, 'location' );
		if ( is_array( $location ) ) {
			$location = reset( $location );
		}
		if ( ! $location ) {
			break;
		}
		$url = $location;
	}
	return $url;
}

/**
 * トップページのスライド画像URL（設定順。1枚以上を保証）
 *
 * @return string[]
 */
function sumiyoshi_hero_slides() {
	$slides = array();
	foreach ( array( 1, 2, 3 ) as $n ) {
		$url = get_theme_mod( "sumiyoshi_hero_image_{$n}", '' );
		if ( $url ) {
			$slides[] = $url;
		}
	}
	if ( ! $slides ) {
		$slides[] = sumiyoshi_upload_url( SUMIYOSHI_DEFAULT_HERO );
	}
	return apply_filters( 'sumiyoshi_hero_slides', $slides );
}

/** トップ画像の表示位置（CSS の background-position-x） */
function sumiyoshi_hero_position( $device ) {
	$default = 'sp' === $device ? '100%' : '50%';
	return sumiyoshi_sanitize_hero_position( get_theme_mod( "sumiyoshi_hero_pos_{$device}", $default ) );
}

/**
 * Googleマップの URL を、iframe で表示できる埋め込みURLに変換する
 *
 * - 埋め込み用URL（/maps/embed や output=embed）はそのまま使う
 * - 共有URL（/maps/place/… や maps.app.goo.gl 展開後のURL）は、
 *   ピンの座標（!3d緯度!4d経度 → 無ければ @緯度,経度）から埋め込みURLを作る
 */
function sumiyoshi_map_embed_src( $url ) {
	$url = trim( (string) $url );
	if ( '' === $url ) {
		return '';
	}
	if ( false !== strpos( $url, '/maps/embed' ) || false !== strpos( $url, 'output=embed' ) ) {
		return $url;
	}

	$decoded = rawurldecode( $url );
	$lat     = null;
	$lng     = null;
	if ( preg_match( '/!3d(-?\d+(?:\.\d+)?)!4d(-?\d+(?:\.\d+)?)/', $decoded, $m ) ) {
		list( , $lat, $lng ) = $m;
	} elseif ( preg_match( '/@(-?\d+(?:\.\d+)?),(-?\d+(?:\.\d+)?)/', $decoded, $m ) ) {
		list( , $lat, $lng ) = $m;
	} elseif ( preg_match( '/[?&](?:q|ll|query)=(-?\d+(?:\.\d+)?),\s*(-?\d+(?:\.\d+)?)/', $decoded, $m ) ) {
		list( , $lat, $lng ) = $m;
	}

	if ( null !== $lat ) {
		$zoom = preg_match( '/,(\d+(?:\.\d+)?)z/', $decoded, $z ) ? (int) $z[1] : 17;
		return sprintf( 'https://maps.google.com/maps?q=%s,%s&z=%d&hl=ja&output=embed', $lat, $lng, $zoom );
	}

	// 座標が取れないURL（短縮URLなど）は場所の名前で検索
	if ( preg_match( '#/maps/place/([^/@]+)#', $decoded, $m ) ) {
		return 'https://maps.google.com/maps?q=' . rawurlencode( str_replace( '+', ' ', $m[1] ) ) . '&z=17&hl=ja&output=embed';
	}
	return '';
}

/** アクセス・求人ページの地図の埋め込みURL */
function sumiyoshi_map_src() {
	return sumiyoshi_map_embed_src( get_theme_mod( 'sumiyoshi_map_url', SUMIYOSHI_DEFAULT_MAP ) );
}

/** アクセスページのストリートビューの埋め込みURL（空なら非表示） */
function sumiyoshi_streetview_src() {
	return sumiyoshi_map_embed_src( get_theme_mod( 'sumiyoshi_streetview_url', SUMIYOSHI_DEFAULT_STREETVIEW ) );
}
