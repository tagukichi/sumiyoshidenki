<?php
/**
 * 沿革（/information/history/）
 *
 * 項目を追加する場合は下の $sumiyoshi_history の末尾に追記してください。
 *
 * @package sumiyoshi-denki
 */

get_header();
sumiyoshi_page_heading( 'History', get_the_title() );

$sumiyoshi_history = array(
	array( '昭和28年 11月', '東京電力（株）を退社し、高橋徳太郎 個人にて川崎市中原区に置いて営業を開始。' ),
	array( '昭和32年 4月', '神奈川県庁及び川崎市役所指名業者に登録される。' ),
	array( '昭和38年 7月', "資本金 2,200,000で住吉電機株式会社設立。\n有限会社住吉電機商会休業。" ),
	array( '昭和39年 6月', '東京電力（株）6KV昇圧工事業者に登録される。' ),
	array( '昭和41年 4月', '日本住宅公団、東京都庁指名業者に登録される。' ),
	array( '昭和44年 8月', '増資 1,200,000により資本金10,000,000となる。' ),
	array( '昭和47年 4月', '住吉ビル完成。' ),
	array( '昭和47年 6月', '増資 5,000,000により資本金 15,000,000となる。' ),
	array( '昭和51年 4月', '現住所に本社ビルを設立して営業開始。' ),
	array( '昭和62年 6月', '高橋 隆 代表取締役社長に就任。' ),
	array( '平成 2年 4月', '増資 10,500,000により資本金 31,500,000となる。' ),
	array( '平成27年 4月', '増資 20,000,000により資本金 51,500,000となる。' ),
	array( '令和元年 8月', '高橋 賢次 代表取締役社長に就任。現在に至る。' ),
);
?>

<section class="section">
	<div class="container">
		<div class="timeline">
			<?php foreach ( $sumiyoshi_history as $sumiyoshi_item ) : ?>
				<div class="timeline__item fade-in">
					<p class="timeline__year"><?php echo esc_html( $sumiyoshi_item[0] ); ?></p>
					<p class="timeline__text"><?php echo nl2br( esc_html( $sumiyoshi_item[1] ) ); ?></p>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<?php
get_footer();
