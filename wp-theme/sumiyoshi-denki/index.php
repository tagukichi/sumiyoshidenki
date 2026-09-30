<?php
/**
 * 汎用テンプレート（他のテンプレートに該当しない場合・検索結果）
 *
 * @package sumiyoshi-denki
 */

get_header();
sumiyoshi_page_heading( is_search() ? 'Search' : '', sumiyoshi_archive_title() );
?>

<section class="section">
	<div class="container">
		<?php if ( have_posts() ) : ?>
			<ul class="news-list">
				<?php
				while ( have_posts() ) :
					the_post();
					get_template_part( 'template-parts/news-item' );
				endwhile;
				?>
			</ul>
			<?php sumiyoshi_pagination(); ?>
		<?php else : ?>
			<p class="empty-note">該当する記事が見つかりませんでした。</p>
		<?php endif; ?>
	</div>
</section>

<?php
get_footer();
