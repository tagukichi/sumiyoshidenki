<?php
/**
 * 固定ページ（汎用）
 *
 * 会社概要などデザイン済みのページは page-{スラッグ}.php を使います。
 * それ以外の新しく作った固定ページはこのテンプレートで、エディタの内容をそのまま表示します。
 *
 * @package sumiyoshi-denki
 */

get_header();

while ( have_posts() ) :
	the_post();
	sumiyoshi_page_heading( '', get_the_title() );
	?>

	<section class="section">
		<div class="container">
			<article id="post-<?php the_ID(); ?>" <?php post_class( 'article' ); ?>>
				<div class="article__body entry-content">
					<?php the_content(); ?>
				</div>
			</article>
		</div>
	</section>

	<?php
endwhile;

get_footer();
