<?php
/**
 * 会社概要（/information/company/）
 *
 * 内容を変更する場合は下の $sumiyoshi_rows を編集してください。
 *
 * @package sumiyoshi-denki
 */

get_header();
sumiyoshi_page_heading( 'Company', get_the_title() );

$sumiyoshi_rows = array(
	'名　称'    => '住吉電機株式会社',
	'代表取締役'  => '高橋　賢次',
	'本社所在地'  => '神奈川県川崎市高津区明津14番地1',
	'電話'     => SUMIYOSHI_TEL . '（代）',
	'資本金'    => '51,500,000 円',
	'許可番号'   => '神奈川県知事（般）　1396号',
	'創立'     => '昭和28年11月1日',
	'営業品目'   => "（1）電気配線工事・計装工事\n　　 消防施設工事・電気通信工事\n（2）貸ビル業",
	'従業員数'   => '10名（2025年3月現在）',
);
?>

<section class="section">
	<div class="container">
		<div class="company-layout">
			<img src="<?php echo esc_url( sumiyoshi_upload_url( '2025/06/Frame-8.png' ) ); ?>" alt="住吉電機株式会社 本社ビル" loading="lazy">
			<table class="info-table">
				<tbody>
					<?php foreach ( $sumiyoshi_rows as $sumiyoshi_th => $sumiyoshi_td ) : ?>
						<tr>
							<th><?php echo esc_html( $sumiyoshi_th ); ?></th>
							<td><?php echo nl2br( esc_html( $sumiyoshi_td ) ); ?></td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	</div>
</section>

<?php
get_footer();
