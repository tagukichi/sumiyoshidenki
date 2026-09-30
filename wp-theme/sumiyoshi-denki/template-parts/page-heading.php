<?php
/**
 * 下層ページのタイトル帯＋パンくず
 *
 * 引数: label（英字の小見出し）, title（見出し・プレーンテキスト）
 *
 * @package sumiyoshi-denki
 */

$sumiyoshi_label = isset( $args['label'] ) ? $args['label'] : '';
$sumiyoshi_title = isset( $args['title'] ) ? $args['title'] : '';
$sumiyoshi_trail = sumiyoshi_breadcrumb_items();
?>
<div class="page-header">
	<div class="container">
		<?php if ( $sumiyoshi_label ) : ?>
			<p class="page-header__label"><?php echo esc_html( $sumiyoshi_label ); ?></p>
		<?php endif; ?>
		<h1 class="page-header__title"><?php echo esc_html( $sumiyoshi_title ); ?></h1>
	</div>
</div>
<nav class="breadcrumb" aria-label="パンくずリスト">
	<div class="container">
		<ol>
			<?php foreach ( $sumiyoshi_trail as $sumiyoshi_crumb ) : ?>
				<?php if ( ! empty( $sumiyoshi_crumb['url'] ) ) : ?>
					<li><a href="<?php echo esc_url( $sumiyoshi_crumb['url'] ); ?>"><?php echo esc_html( $sumiyoshi_crumb['title'] ); ?></a></li>
				<?php else : ?>
					<li aria-current="page"><?php echo esc_html( $sumiyoshi_crumb['title'] ); ?></li>
				<?php endif; ?>
			<?php endforeach; ?>
		</ol>
	</div>
</nav>
