<?php
/**
 * 記事ページ（News & Topics・ブログ）
 *
 * 本文は .article__body.entry-content で囲んでいるため、
 * ブロックエディタで書いた見出し・リスト・引用・表・画像がサイトのデザインで表示されます。
 *
 * @package sumiyoshi-denki
 */

get_header();

while ( have_posts() ) :
	the_post();
	$sumiyoshi_post_type = get_post_type();
	sumiyoshi_page_heading( sumiyoshi_post_type_en( $sumiyoshi_post_type ), sumiyoshi_plain_title( get_the_title() ) );
	$sumiyoshi_back_url = 'post' === $sumiyoshi_post_type
		? ( get_option( 'page_for_posts' ) ? get_permalink( get_option( 'page_for_posts' ) ) : home_url( '/' ) )
		: get_post_type_archive_link( $sumiyoshi_post_type );
	?>

	<section class="section">
		<div class="container">
			<article id="post-<?php the_ID(); ?>" <?php post_class( 'article' ); ?>>
				<div class="article__meta">
					<time class="article__date" datetime="<?php echo esc_attr( get_the_date( 'Y-m-d' ) ); ?>"><?php echo esc_html( get_the_date( 'Y/m/d' ) ); ?></time>
					<?php if ( 'post' === $sumiyoshi_post_type ) : ?>
						<?php foreach ( get_the_category() as $sumiyoshi_cat ) : ?>
							<a class="news-list__cat" href="<?php echo esc_url( get_category_link( $sumiyoshi_cat ) ); ?>"><?php echo esc_html( $sumiyoshi_cat->name ); ?></a>
						<?php endforeach; ?>
					<?php endif; ?>
				</div>

				<?php if ( has_post_thumbnail() ) : ?>
					<?php the_post_thumbnail( 'large', array( 'class' => 'article__thumb' ) ); ?>
				<?php endif; ?>

				<div class="article__body entry-content">
					<?php
					the_content();
					wp_link_pages(
						array(
							'before' => '<nav class="page-links">',
							'after'  => '</nav>',
						)
					);
					?>
				</div>

				<?php
				the_post_navigation(
					array(
						'prev_text'          => '<span class="post-nav__label">前の記事</span><span class="post-nav__title">%title</span>',
						'next_text'          => '<span class="post-nav__label">次の記事</span><span class="post-nav__title">%title</span>',
						'screen_reader_text' => '記事の移動',
						'class'              => 'post-nav',
					)
				);
				?>

				<p class="article__back"><a href="<?php echo esc_url( $sumiyoshi_back_url ); ?>" class="btn-more">一覧へ戻る</a></p>
			</article>
		</div>
	</section>

	<?php
endwhile;

get_footer();
