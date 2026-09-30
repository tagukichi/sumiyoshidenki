<?php
/**
 * フッター
 *
 * @package sumiyoshi-denki
 */

$sumiyoshi_privacy_url = get_privacy_policy_url();
?>
</main>

<footer class="site-footer">
	<div class="container site-footer__inner">
		<div class="footer-company">
			<div class="footer-company__logo">
				<img src="<?php echo esc_url( sumiyoshi_logo_url() ); ?>" alt="<?php bloginfo( 'name' ); ?>">
			</div>
			<address class="footer-company__address">
				神奈川県川崎市高津区明津14番地1<br>
				<a href="<?php echo esc_url( sumiyoshi_tel_href() ); ?>"><?php echo esc_html( SUMIYOSHI_TEL ); ?>（代）</a>
			</address>
		</div>
		<nav class="footer-nav" aria-label="フッターナビ">
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'footer',
					'container'      => false,
					'menu_class'     => 'footer-nav__menu',
					'depth'          => 1,
					'fallback_cb'    => 'sumiyoshi_default_footer_menu',
				)
			);
			?>
		</nav>
	</div>
	<div class="copyright">
		<div class="container">
			<span>Copyright © SUMIYOSHI ELECTRIC CO.,LTD All Rights Reserved.</span>
			<?php if ( $sumiyoshi_privacy_url ) : ?>
				<span><a href="<?php echo esc_url( $sumiyoshi_privacy_url ); ?>">プライバシーポリシー</a></span>
			<?php endif; ?>
			<span>This site is protected by reCAPTCHA and the Google <a href="https://policies.google.com/privacy">Privacy Policy</a> and <a href="https://policies.google.com/terms">Terms of Service</a> apply.</span>
		</div>
	</div>
</footer>
<button class="to-top" aria-label="ページ上部へ戻る">↑</button>

<?php wp_footer(); ?>
</body>
</html>
