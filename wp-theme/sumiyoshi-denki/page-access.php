<?php
/**
 * アクセス（/information/access/）
 *
 * @package sumiyoshi-denki
 */

get_header();
sumiyoshi_page_heading( 'Access', get_the_title() );
?>

<section class="section">
	<div class="container">
		<div class="article">
			<table class="info-table access-table">
				<tbody>
					<tr><th>所在地</th><td>神奈川県川崎市高津区明津14番地1</td></tr>
					<tr><th>電話</th><td><a href="<?php echo esc_url( sumiyoshi_tel_href() ); ?>"><?php echo esc_html( SUMIYOSHI_TEL ); ?>（代）</a></td></tr>
				</tbody>
			</table>
			<?php
			// 地図・ストリートビューは 外観 > カスタマイズ > サイト設定（住吉電機） で変更できます
			$sumiyoshi_map    = sumiyoshi_map_src();
			$sumiyoshi_street = sumiyoshi_streetview_src();
			?>
			<?php if ( $sumiyoshi_map ) : ?>
				<iframe class="map-embed" loading="lazy" referrerpolicy="no-referrer-when-downgrade" allowfullscreen
					src="<?php echo esc_url( $sumiyoshi_map ); ?>"
					title="住吉電機株式会社 地図"></iframe>
			<?php endif; ?>
			<?php if ( $sumiyoshi_street ) : ?>
				<iframe class="map-embed" loading="lazy" referrerpolicy="no-referrer-when-downgrade" allowfullscreen
					src="<?php echo esc_url( $sumiyoshi_street ); ?>"
					title="住吉電機株式会社 ストリートビュー"></iframe>
			<?php endif; ?>
		</div>
	</div>
</section>

<?php
get_footer();
