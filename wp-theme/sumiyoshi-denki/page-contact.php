<?php
/**
 * お問い合わせ（/contact/）
 *
 * フォームは Contact Form 7「お問い合わせフォーム」（SUMIYOSHI_CF7_CONTACT）を表示します。
 * 個人情報の同意文もフォーム側の設定に含まれています。
 *
 * @package sumiyoshi-denki
 */

get_header();
sumiyoshi_page_heading( 'Contact', get_the_title() );
?>

<section class="section">
	<div class="container">
		<div class="contact-form">
			<p class="form-note">お見積り・ご相談はお気軽にどうぞ。お急ぎの方はお電話（<a href="<?php echo esc_url( sumiyoshi_tel_href() ); ?>"><?php echo esc_html( SUMIYOSHI_TEL ); ?></a>）にてご連絡ください。</p>
			<?php sumiyoshi_contact_form( SUMIYOSHI_CF7_CONTACT ); ?>
		</div>
	</div>
</section>

<?php
get_footer();
