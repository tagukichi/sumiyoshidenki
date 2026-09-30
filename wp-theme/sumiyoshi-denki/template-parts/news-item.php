<?php
/**
 * お知らせ一覧の1行（ループ内で使用）
 *
 * @package sumiyoshi-denki
 */

$sumiyoshi_cat = ( 'post' === get_post_type() ) ? get_the_category() : array();
?>
<li class="news-list__item">
	<a href="<?php the_permalink(); ?>" class="news-list__link">
		<time class="news-list__date" datetime="<?php echo esc_attr( get_the_date( 'Y-m-d' ) ); ?>"><?php echo esc_html( get_the_date( 'Y/m/d' ) ); ?></time>
		<?php if ( $sumiyoshi_cat ) : ?>
			<span class="news-list__cat"><?php echo esc_html( $sumiyoshi_cat[0]->name ); ?></span>
		<?php endif; ?>
		<span class="news-list__title"><?php echo esc_html( sumiyoshi_plain_title( get_the_title() ) ); ?></span>
	</a>
</li>
