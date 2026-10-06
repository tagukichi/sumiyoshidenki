<?php
/**
 * トップページ
 *
 * @package sumiyoshi-denki
 */

get_header();

/**
 * メインビジュアルの画像は 外観 > カスタマイズ > サイト設定（住吉電機） で変更できます。
 * （既定は現行サイトの1枚。2枚以上設定するとスライドショー）
 */
$sumiyoshi_slides = sumiyoshi_hero_slides();

$sumiyoshi_news = new WP_Query(
	array(
		'post_type'           => 'news',
		'posts_per_page'      => 6,
		'ignore_sticky_posts' => true,
		'no_found_rows'       => true,
	)
);

$sumiyoshi_works = new WP_Query(
	array(
		'post_type'      => 'jisseki',
		'posts_per_page' => 4,
		'no_found_rows'  => true,
	)
);
?>

<section class="hero" style="--hero-pos-pc: <?php echo esc_attr( sumiyoshi_hero_position( 'pc' ) ); ?>; --hero-pos-sp: <?php echo esc_attr( sumiyoshi_hero_position( 'sp' ) ); ?>;">
	<?php foreach ( $sumiyoshi_slides as $sumiyoshi_i => $sumiyoshi_slide ) : ?>
		<div class="hero__slide<?php echo 0 === $sumiyoshi_i ? ' is-active' : ''; ?>" style="background-image:url('<?php echo esc_url( $sumiyoshi_slide ); ?>')"></div>
	<?php endforeach; ?>
	<p class="hero__catch">
		<span>川崎市で電気工事を</span>
		<span>営んで半世紀以上。</span>
		<span>技術力と信頼で</span>
		<span>その間に培った</span>
		<span>お客様に安心をお届けします。</span>
	</p>
	<?php if ( count( $sumiyoshi_slides ) > 1 ) : ?>
		<div class="hero__dots" role="tablist" aria-label="スライド切り替え"></div>
	<?php endif; ?>
</section>

<!-- News & Topics -->
<section class="section">
	<div class="container">
		<div class="section__head fade-in">
			<span class="section__label">News &amp; Topics</span>
			<h2 class="section__title">お知らせ</h2>
		</div>
		<?php if ( $sumiyoshi_news->have_posts() ) : ?>
			<ul class="news-list fade-in">
				<?php
				while ( $sumiyoshi_news->have_posts() ) :
					$sumiyoshi_news->the_post();
					get_template_part( 'template-parts/news-item' );
				endwhile;
				wp_reset_postdata();
				?>
			</ul>
		<?php endif; ?>
		<div class="section__more fade-in">
			<a href="<?php echo esc_url( get_post_type_archive_link( 'news' ) ); ?>" class="btn-more">お知らせ一覧はコチラ＞</a>
		</div>
	</div>
</section>

<!-- 会社案内 -->
<section class="section section--gray">
	<div class="container">
		<div class="info-index">
			<div class="info-index__head fade-in">
				<span class="section__label">Information</span>
				<h2 class="section__title">会社案内</h2>
				<p class="info-index__lead">昭和28年の創業以来、川崎の地で電気工事を営んでまいりました。住吉電機株式会社についてご紹介します。</p>
			</div>
			<?php get_template_part( 'template-parts/info-links', null, array( 'style' => 'index' ) ); ?>
		</div>
	</div>
</section>

<!-- 施工実績 -->
<section class="section">
	<div class="container">
		<div class="section__head section__head--center fade-in">
			<span class="section__label">Works</span>
			<h2 class="section__title">施工実績</h2>
		</div>
		<?php if ( $sumiyoshi_works->have_posts() ) : ?>
			<div class="works-grid works-grid--top">
				<?php
				while ( $sumiyoshi_works->have_posts() ) :
					$sumiyoshi_works->the_post();
					get_template_part( 'template-parts/work-card' );
				endwhile;
				wp_reset_postdata();
				?>
			</div>
		<?php endif; ?>
		<div class="section__more fade-in">
			<a href="<?php echo esc_url( get_post_type_archive_link( 'jisseki' ) ); ?>" class="btn-more">施工実績はこちら＞</a>
		</div>
	</div>
</section>

<!-- 求人情報 -->
<section class="section section--cream">
	<div class="container">
		<div class="section__head section__head--center fade-in">
			<span class="section__label">Recruit</span>
			<h2 class="section__title">求人情報</h2>
		</div>
		<div class="recruit-copy fade-in">
			<p class="recruit-copy__main">正社員募集中！<br>川崎市で半世紀以上つづく電気工事業で一緒に働きませんか？</p>
			<p class="recruit-copy__sub">未経験から手に職を。寮完備＆土日祝休みで働きやすい環境！</p>
			<p class="recruit-copy__text">
				川崎市内中心の現場で転勤なし。施工管理者、現場作業員の募集をしています。<br>
				しっかりとしたサポート体制で、未経験者も多数活躍中！<br>
				寮完備で遠方からの応募も歓迎。残業少なめ・土日祝休みで、プライベートも充実。<br>
				「安定して働きたい」「手に職をつけたい」「長く働きたい」そんな方に最適な職場です。
			</p>
		</div>
		<?php get_template_part( 'template-parts/stat-cards' ); ?>
		<div class="section__more fade-in">
			<a href="<?php echo esc_url( sumiyoshi_url( '/recruit/' ) ); ?>" class="btn-more">求人情報はこちら＞</a>
		</div>
	</div>
</section>

<?php
get_footer();
