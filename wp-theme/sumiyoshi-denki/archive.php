<?php
/**
 * 一覧（News & Topics・ブログ・カテゴリー・日付アーカイブ）
 *
 * @package sumiyoshi-denki
 */

get_header();

$sumiyoshi_post_type = is_post_type_archive( 'news' ) ? 'news' : 'post';
sumiyoshi_page_heading( sumiyoshi_post_type_en( $sumiyoshi_post_type ), sumiyoshi_archive_title() );
?>

<section class="section">
	<div class="container">
		<?php if ( have_posts() ) : ?>
			<ul class="news-list fade-in">
				<?php
				while ( have_posts() ) :
					the_post();
					get_template_part( 'template-parts/news-item' );
				endwhile;
				?>
			</ul>
			<?php sumiyoshi_pagination(); ?>
		<?php else : ?>
			<p class="empty-note">まだ記事がありません。</p>
		<?php endif; ?>
	</div>
</section>

<?php
get_footer();
