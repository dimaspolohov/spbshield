<?php
/**
 * The template for displaying the homepage.
 *
 * This page template will display any functions hooked into the `homepage` action.
 * By default this includes a variety of product displays and the page content itself. To change the order or toggle these components
 * use the Homepage Control plugin.
 * https://wordpress.org/plugins/homepage-control/
 *
 * Template name: Homepage
 *
 * @package storefront
 */

$add_blocks = get_field("add_blocks") ?: '';

get_header(); ?>

	<? if (get_field("on_slider")) { 
		do_action( 'template_on_slider' );
	} ?>

	<? 
	while ( have_posts() ) :
		the_post();
		do_action( 'storefront_page_before' );
		if (get_field("text_block_before_show")) {
			get_template_part('template-parts/rs-text-block/rs-text-block-before');				
		}
		if ($add_blocks) {
			foreach ($add_blocks as $value) {
				// echo $value['block_name'];
				do_action( 'template_'. $value['block_name'] );
			}					
		}
		if (get_field("text_block_after_show")) {
			get_template_part('template-parts/rs-text-block/rs-text-block-after');
		}
		do_action( 'storefront_page_after' );
	endwhile; // End of the loop.?>

<? get_footer();
