<?php
/**
 * The template for displaying full width pages.
 *
 * Template Name: Woocommerce container
 *
 * @package storefront
 */

get_header(); ?>
<!-- rs-woo-container -->
<section class="rs-woocontainer-content _page-container">
	<div class="rs-woocontainer-content--wrapper">
		<?php
		while ( have_posts() ) :
			the_post();

			do_action( 'storefront_page_before' );

			get_template_part( 'content', 'page' );

			/**
			 * Functions hooked in to storefront_page_after action
			 *
			 * @hooked storefront_display_comments - 10
			 */
			do_action( 'storefront_page_after' );

		endwhile; // End of the loop.
		?>
	</div>
</section>
<!-- /rs-full-width -->
<?php get_footer();