<?php
/**
 * 求人の数字カード（トップページ・求人情報ページ共通）
 *
 * @package sumiyoshi-denki
 */

$sumiyoshi_stats = array(
	array( '年間休日日数', '2025/06/Group-3750.svg', '120～125', '日' ),
	array( '平均有給取得日数', '2025/06/Frame-1.svg', '10', '日' ),
	array( '創業年', '2025/06/Group-3747.svg', '1962', '年' ),
	array( '平均残業時間', '2025/06/Frame-2.svg', '0.5', '時間' ),
	array( '平均勤続年数', '2025/06/Group-3748.svg', '20', '年' ),
	array( '賞与支給日', '2025/06/Group-3746.svg', '2', '回' ),
);
?>
<div class="stat-cards">
	<?php foreach ( $sumiyoshi_stats as $sumiyoshi_stat ) : ?>
		<div class="stat-card fade-in">
			<span class="stat-card__label"><?php echo esc_html( $sumiyoshi_stat[0] ); ?></span>
			<img class="stat-card__icon" src="<?php echo esc_url( sumiyoshi_upload_url( $sumiyoshi_stat[1] ) ); ?>" alt="" loading="lazy">
			<p class="stat-card__value"><?php echo esc_html( $sumiyoshi_stat[2] ); ?><small><?php echo esc_html( $sumiyoshi_stat[3] ); ?></small></p>
		</div>
	<?php endforeach; ?>
</div>
