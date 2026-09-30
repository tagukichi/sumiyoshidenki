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
			<iframe class="map-embed map-embed--street"
				src="https://www.google.com/maps/embed?pb=!4v1743146114250!6m8!1m7!1sKf5jSiNYm6P3JBHiGM0gDw!2m2!1d35.56946006058778!2d139.6358572797338!3f35.39!4f52.41999999999999!5f1.009049021386966"
				allowfullscreen loading="lazy" referrerpolicy="no-referrer-when-downgrade"
				title="住吉電機株式会社 ストリートビュー"></iframe>
			<iframe class="map-embed" loading="lazy" referrerpolicy="no-referrer-when-downgrade"
				src="https://maps.google.com/maps?q=%E7%A5%9E%E5%A5%88%E5%B7%9D%E7%9C%8C%E5%B7%9D%E5%B4%8E%E5%B8%82%E9%AB%98%E6%B4%A5%E5%8C%BA%E6%98%8E%E6%B4%A514-1&amp;output=embed"
				title="住吉電機株式会社 所在地"></iframe>
		</div>
	</div>
</section>

<?php
get_footer();
