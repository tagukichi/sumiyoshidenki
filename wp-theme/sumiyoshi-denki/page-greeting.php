<?php
/**
 * 代表挨拶（/information/greeting/）
 *
 * @package sumiyoshi-denki
 */

get_header();
sumiyoshi_page_heading( 'Greeting', get_the_title() );
?>

<section class="section">
	<div class="container">
		<div class="greeting-layout">
			<img src="<?php echo esc_url( sumiyoshi_upload_url( '2025/06/top.png' ) ); ?>" alt="代表取締役 高橋賢次" loading="lazy">
			<div>
				<h2 class="greeting__title">ごあいさつ</h2>
				<div class="greeting__body">
					<p>1953年の創業から70年以上を数え、昭和、平成、令和と3つの時代で様々な電気工事を行ってまいりました。最近、電気工事ってどんなことをするのかと良く聞かれます。弊社の電気工事は身近なコンセントやスイッチ、照明器具から太陽光発電設備や施設内の変電所まで幅広く行っております。今後も時代の流れに合わせた電気工事をご提供してまいります。</p>
					<p>これからの時代の目標として従業員を始め弊社に関わる方々の幸せ、地域の皆様への貢献、電気工事業を含めた建設業の発展を掲げ、邁進していく所存です。どうかお付合い頂けると幸いです。</p>
				</div>
				<p class="greeting__sign">住吉電機株式会社<br>代表取締役 高橋　賢次</p>
			</div>
		</div>
	</div>
</section>

<?php
get_footer();
