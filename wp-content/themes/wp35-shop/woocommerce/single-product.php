<?php
/**
* The Template for displaying all single products
*
* This template can be overridden by copying it to yourtheme/woocommerce/single-product.php.
*
* HOWEVER, on occasion WooCommerce will need to update template files and you
* (the theme developer) will need to copy the new files to your theme to
* maintain compatibility. We try to do this as little as possible, but it does
* happen. When this occurs the version of the template file will be bumped and
* the readme will list any important changes.
*
* @see 	    https://docs.woocommerce.com/document/template-structure/
* @author 		WooThemes
* @package 	WooCommerce/Templates
* @version     1.6.4
*/
 
if(!empty($_GET['remove_from_wishlist'])) wp_redirect(get_the_permalink());

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

get_header(  ); ?>
	
	<?php
		/**
		 * woocommerce_before_main_content hook.
		 *
		 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
		 * @hooked woocommerce_breadcrumb - 20
		 */
		do_action( 'rs_woocommerce_before_main_content' );
	?>

		<?php while ( have_posts() ) : the_post(); ?>

			<?php //if( current_user_can('editor') || current_user_can('administrator') ) { ?>
				<?php wc_get_template_part( 'content', 'single-product1' ); ?>
			<?php //} else { ?>
				<?php //wc_get_template_part( 'content', 'single-product' ); ?>
			<?php //} ?>

		<?php endwhile; // end of the loop. ?>
		
	<?php
		/**
		 * woocommerce_after_main_content hook.
		 *
		 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
		 */
		do_action( 'rs_woocommerce_after_main_content' );
	?>
	
<?php get_footer();