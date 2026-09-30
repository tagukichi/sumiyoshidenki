<?php
/**
 * 施工実績カード（ループ内で使用）
 *
 * @package sumiyoshi-denki
 */

list( $sumiyoshi_name, $sumiyoshi_period ) = sumiyoshi_split_title( get_the_title() );
?>
<a class="work-card fade-in" href="<?php the_permalink(); ?>">
	<?php if ( has_post_thumbnail() ) : ?>
		<?php
		the_post_thumbnail(
			'medium_large',
			array(
				'class'   => 'work-card__img',
				'alt'     => $sumiyoshi_name,
				'loading' => 'lazy',
			)
		);
		?>
	<?php else : ?>
		<span class="work-card__img work-card__img--empty" aria-hidden="true"></span>
	<?php endif; ?>
	<div class="work-card__body">
		<h3 class="work-card__title"><?php echo esc_html( $sumiyoshi_name ); ?></h3>
		<?php if ( $sumiyoshi_period ) : ?>
			<p class="work-card__date"><?php echo esc_html( $sumiyoshi_period ); ?></p>
		<?php endif; ?>
	</div>
</a>
