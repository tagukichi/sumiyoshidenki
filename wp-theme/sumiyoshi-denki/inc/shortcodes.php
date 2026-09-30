<?php
/**
 * ショートコード
 *
 * @package sumiyoshi-denki
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * [tenure field="shinozuka_hire_date"]
 *
 * 現行サイトの求人ページで使われている勤続年数表示。
 * ACF の日付フィールド（入社日）から満年数を計算して「◯年」と表示する。
 * 既に別の場所（WPCode 等）で登録されている場合はそちらを優先する。
 */
function sumiyoshi_tenure_shortcode( $atts ) {
	$atts = shortcode_atts(
		array(
			'field'   => '',
			'date'    => '',
			'post_id' => get_the_ID(),
		),
		$atts,
		'tenure'
	);

	$date = $atts['date'];
	if ( ! $date && $atts['field'] ) {
		$date = function_exists( 'get_field' )
			? get_field( $atts['field'], $atts['post_id'], false )
			: get_post_meta( $atts['post_id'], $atts['field'], true );
	}
	$years = sumiyoshi_tenure_years( $date );
	return null === $years ? '' : esc_html( $years . '年' );
}

function sumiyoshi_register_shortcodes() {
	if ( ! shortcode_exists( 'tenure' ) ) {
		add_shortcode( 'tenure', 'sumiyoshi_tenure_shortcode' );
	}
}
add_action( 'init', 'sumiyoshi_register_shortcodes', 20 );

/**
 * Contact Form 7 のフォームを表示（未インストール時は案内文）
 *
 * @param int $form_id フォームID
 */
function sumiyoshi_contact_form( $form_id ) {
	if ( shortcode_exists( 'contact-form-7' ) ) {
		echo do_shortcode( sprintf( '[contact-form-7 id="%d"]', (int) $form_id ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		return;
	}
	printf(
		'<p class="form-note">フォームを表示するには Contact Form 7 プラグインを有効にしてください。お急ぎの方はお電話（<a href="%s">%s</a>）にてご連絡ください。</p>',
		esc_url( sumiyoshi_tel_href() ),
		esc_html( SUMIYOSHI_TEL )
	);
}
