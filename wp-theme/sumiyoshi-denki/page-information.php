<?php
/**
 * 会社案内（/information/）
 *
 * @package sumiyoshi-denki
 */

get_header();
sumiyoshi_page_heading( 'Information', get_the_title() );
?>

<section class="section">
	<div class="container">
		<?php get_template_part( 'template-parts/info-links', null, array( 'style' => 'cards' ) ); ?>
	</div>
</section>

<?php
get_footer();
