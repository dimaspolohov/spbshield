<?php
/**
* The template for displaying full width pages.
*
* Template Name: Wishlist
*
* @package storefront
*/
 
if(!empty($_GET['remove_from_wishlist'])) wp_redirect(get_the_permalink());

$wp_query->set('posts_per_page', 999);
$wp_query->query($wp_query->query_vars);
get_header(); ?>
<section class="rs-search-content rs-page">
	<div class="rs-search__container">
		<h2 class="section-title"><?php the_title(); ?></h2>
		<?php the_content(); ?>
	</div>
</section>
<?php
get_footer();
