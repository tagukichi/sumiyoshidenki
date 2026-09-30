<?php
/**
 * 施工実績 詳細
 *
 * @package sumiyoshi-denki
 */

get_header();

while ( have_posts() ) :
	the_post();
	list( $sumiyoshi_name, $sumiyoshi_period ) = sumiyoshi_split_title( get_the_title() );
	$sumiyoshi_gallery                         = sumiyoshi_jisseki_gallery( get_the_ID() );
	sumiyoshi_page_heading( 'Works', $sumiyoshi_name );
	?>

	<section class="section">
		<div class="container">
			<article id="post-<?php the_ID(); ?>" <?php post_class( 'article' ); ?>>
				<?php if ( $sumiyoshi_period ) : ?>
					<div class="article__meta">
						<span class="article__date"><?php echo esc_html( $sumiyoshi_period ); ?></span>
					</div>
				<?php endif; ?>

				<?php if ( has_post_thumbnail() ) : ?>
					<?php the_post_thumbnail( 'large', array( 'class' => 'article__thumb' ) ); ?>
				<?php endif; ?>

				<?php if ( '' !== trim( wp_strip_all_tags( get_the_content() ) ) || class_exists( 'SiteOrigin_Panels' ) ) : ?>
					<div class="article__body entry-content">
						<?php the_content(); ?>
					</div>
				<?php endif; ?>

				<?php if ( $sumiyoshi_gallery ) : ?>
					<div class="work-gallery">
						<?php foreach ( $sumiyoshi_gallery as $sumiyoshi_img ) : ?>
							<img src="<?php echo esc_url( $sumiyoshi_img ); ?>" alt="<?php echo esc_attr( $sumiyoshi_name ); ?> 施工写真" loading="lazy">
						<?php endforeach; ?>
					</div>
				<?php endif; ?>

				<p class="article__back"><a href="<?php echo esc_url( get_post_type_archive_link( 'jisseki' ) ); ?>" class="btn-more">施工実績一覧へ戻る</a></p>
			</article>
		</div>
	</section>

	<?php
endwhile;

get_footer();
