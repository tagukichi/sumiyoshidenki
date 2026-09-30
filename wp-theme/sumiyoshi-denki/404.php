<?php
/**
 * 404
 *
 * @package sumiyoshi-denki
 */

get_header();
sumiyoshi_page_heading( '404 Not Found', 'ページが見つかりません' );
?>

<section class="section">
	<div class="container">
		<div class="article">
			<p>お探しのページは移動または削除された可能性があります。</p>
			<p class="article__back"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn-more">トップページへ戻る</a></p>
		</div>
	</div>
</section>

<?php
get_footer();
