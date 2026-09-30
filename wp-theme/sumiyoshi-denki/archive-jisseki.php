<?php
/**
 * 施工実績 一覧
 *
 * @package sumiyoshi-denki
 */

get_header();
sumiyoshi_page_heading( 'Works', '施工実績' );
?>

<section class="section">
	<div class="container">
		<?php if ( have_posts() ) : ?>
			<div class="works-grid">
				<?php
				while ( have_posts() ) :
					the_post();
					get_template_part( 'template-parts/work-card' );
				endwhile;
				?>
			</div>
			<?php sumiyoshi_pagination(); ?>
		<?php else : ?>
			<p class="empty-note">施工実績はまだありません。</p>
		<?php endif; ?>
	</div>
</section>

<?php
get_footer();
