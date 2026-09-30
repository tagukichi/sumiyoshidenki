<?php
/**
 * ヘッダー
 *
 * @package sumiyoshi-denki
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="skip-link screen-reader-text" href="#main">本文へスキップ</a>

<div class="announce-bar">
	<a href="<?php echo esc_url( sumiyoshi_url( '/recruit/' ) ); ?>">一緒に働ける仲間を住吉電機株式会社では募集しております。</a>
</div>
<header class="site-header">
	<div class="site-header__inner">
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="site-logo">
			<img src="<?php echo esc_url( sumiyoshi_logo_url() ); ?>" alt="<?php bloginfo( 'name' ); ?>">
		</a>
		<nav class="global-nav" id="global-nav" aria-label="グローバルナビ">
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'global',
					'container'      => false,
					'menu_class'     => 'global-nav__list',
					'depth'          => 2,
					'fallback_cb'    => 'sumiyoshi_default_global_menu',
				)
			);
			?>
			<a class="btn-recruit" href="<?php echo esc_url( sumiyoshi_url( '/recruit/' ) ); ?>">求人情報</a>
		</nav>
		<div class="header-contact">
			<p class="header-tel">
				<a class="header-tel__number" href="<?php echo esc_url( sumiyoshi_tel_href() ); ?>"><?php echo esc_html( SUMIYOSHI_TEL ); ?>（代）</a>
				<span class="header-tel__note">お電話でのお問い合わせ</span>
			</p>
			<a class="btn-recruit" href="<?php echo esc_url( sumiyoshi_url( '/recruit/' ) ); ?>">求人情報</a>
			<button class="nav-toggle" aria-expanded="false" aria-controls="global-nav" aria-label="メニューを開く">
				<span></span><span></span><span></span>
			</button>
		</div>
	</div>
</header>

<main id="main">
