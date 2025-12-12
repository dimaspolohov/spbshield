<?php
/**
 * Empty cart page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart-empty.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 7.5.0
 */

defined( 'ABSPATH' ) || exit;

?>
<div class="rs-cart">
	<div class="container">
		<div class="rs-cart-title--wrapper">
			<div class="rs-cart-title">
				<h1 class="section-title-inner"><? _e('Cart is empty','storefront')?></h1>
			</div>
			<div class="rs-cart-back">
				<h4 class="caps"><a href="<?=get_post_type_archive_link('product'); ?>"><i class="fa fa-chevron-left"></i><? _e('Back to shopping','storefront')?></a></h4>
			</div>
		</div>
	</div>
</div>