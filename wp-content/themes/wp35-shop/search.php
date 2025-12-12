<?php
/**
 * The template for displaying search results pages.
 *
 * @package storefront
 */
$wp_query->set('posts_per_page', 999);
$wp_query->query($wp_query->query_vars);
get_header(); ?>
<? //rs_woocommerce_breadcrumb();?>
<section class="rs-search-content rs-page">
	<div class="rs-search__container">
	<? if ( have_posts() ) : ?>
		<h2 class="section-title"><?php printf( esc_attr__( 'Search Results for: %s', 'storefront' ), '«' . get_search_query() . '»' ); ?></h2>
		<div class="rs-catalog__list _view-1" offset="8">
		
		<?php
		while (have_posts()) : the_post();
			
			wc_get_template_part( 'content', 'product' );

		endwhile; ?>
		</div>
	<?php
	else :
		?>
		<h2 class="section-title"><?php esc_html_e( 'Nothing Found', 'storefront' ); ?></h2>
		<div class="posts-search">
			<div class="entry-content">
				<?php esc_html_e( 'Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'storefront' ); ?>
			</div>
		</div>
		<div class="posts-search">
			<div class="entry-content">
				<form role="search" class="search-form" method="get" action="/">
					<div class="search__field">
						<input type="search" name="s" value="<?=get_search_query()?>" placeholder="Я ищу...">		
						<button id="search-submit" class="search-btn-inner" type="submit"><i class="icon-search"></i></button>	
					</div>
				</form>
			</div>
		</div>
		<?
	endif;
	?>
	</div>
</section>
<?php
get_footer();