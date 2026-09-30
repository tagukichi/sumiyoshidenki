<?php
/**
 * 会社案内の4項目（トップページ：番号付きリスト / 会社案内ページ：カード）
 *
 * 引数: style = 'index'（トップ用） | 'cards'（会社案内ページ用）
 *
 * @package sumiyoshi-denki
 */

$sumiyoshi_style = isset( $args['style'] ) ? $args['style'] : 'cards';
$sumiyoshi_links = array(
	array( '代表挨拶', 'Greeting', '/information/greeting/', '2025/05/image-9.png' ),
	array( '会社概要', 'Company', '/information/company/', '2025/06/Frame-8.png' ),
	array( '沿革', 'History', '/information/history/', '2025/06/56b7b0de4be422688604b363ad508afd-3.png' ),
	array( 'アクセス', 'Access', '/information/access/', '2025/05/image-8.png' ),
);
?>
<?php if ( 'index' === $sumiyoshi_style ) : ?>
	<ul class="info-index__list">
		<?php foreach ( $sumiyoshi_links as $sumiyoshi_i => $sumiyoshi_link ) : ?>
			<li class="fade-in">
				<a class="info-index__link" href="<?php echo esc_url( sumiyoshi_url( $sumiyoshi_link[2] ) ); ?>">
					<span class="info-index__num"><?php echo esc_html( sprintf( '%02d', $sumiyoshi_i + 1 ) ); ?></span>
					<img class="info-index__thumb" src="<?php echo esc_url( sumiyoshi_upload_url( $sumiyoshi_link[3] ) ); ?>" alt="" loading="lazy">
					<span class="info-index__title"><?php echo esc_html( $sumiyoshi_link[0] ); ?><small><?php echo esc_html( $sumiyoshi_link[1] ); ?></small></span>
					<span class="info-index__arrow" aria-hidden="true">→</span>
				</a>
			</li>
		<?php endforeach; ?>
	</ul>
<?php else : ?>
	<div class="info-cards">
		<?php foreach ( $sumiyoshi_links as $sumiyoshi_link ) : ?>
			<a class="info-card fade-in" href="<?php echo esc_url( sumiyoshi_url( $sumiyoshi_link[2] ) ); ?>">
				<img class="info-card__img" src="<?php echo esc_url( sumiyoshi_upload_url( $sumiyoshi_link[3] ) ); ?>" alt="<?php echo esc_attr( $sumiyoshi_link[0] ); ?>" loading="lazy">
				<div class="info-card__body">
					<span class="info-card__title"><?php echo esc_html( $sumiyoshi_link[0] ); ?></span>
					<span class="info-card__arrow" aria-hidden="true">→</span>
				</div>
			</a>
		<?php endforeach; ?>
	</div>
<?php endif; ?>
