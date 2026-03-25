<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/archive-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 8.6.0
 */

defined( 'ABSPATH' ) || exit;

$frontpage_id = get_option('page_on_front');

$novye_postupleniya = get_field('novye_postupleniya', $frontpage_id);
$skidki = get_field('skidki', $frontpage_id);

$term_id = '0';
if( is_product_category() || is_product_tag() ) :
	$term = get_queried_object();
	$term_id = $term->term_id;
endif;

get_header(); ?>

<?php
/**
 * woocommerce_before_main_content hook.
 *
 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
 * @hooked woocommerce_breadcrumb - 20
 * @hooked WC_Structured_Data::generate_website_data() - 30
 */
do_action( 'rs_woocommerce_before_main_content' );

$structureMenu = new \SpbShield\Inc\StructureMenu(\SpbShield\Inc\ThemeConfig::STRUCTURE_MENU_ID);
$menu = $structureMenu->getItemsStructureMenu();
?>

<!-- rs-catalog -->
<section class="rs-catalog">
	<div class="rs-catalog__container">
		<div class="rs-catalog__wrapper" data-term="<?php echo esc_attr($term_id); ?>">
			<div class="rs-catalog__filters _filters-show" id="filters">
				<div class="rs-catalog__filters_header">
					<button type="button" class="filter-close icon-close"></button>
					<h6 class="small-title"><?php esc_html_e('Filters', 'storefront'); ?></h6>
					<button type="button" class="filter-clear"><?php esc_html_e('Clear', 'storefront'); ?></button>
				</div>
				<form action="#">
					<div class="filters__block">
						<div data-spollers="992" data-one-spoller class="filters__spollers spollers">
							<div class="spollers__item filters__category">
								<button type="button" data-spoller class="spollers__title"><?php esc_html_e('Categories', 'storefront'); ?></button>
								<div class="spollers__body">
									<div class="filters__category-list">

                                            <?php
                                            foreach( $menu as $item ){
                                               $classes = $item->classes;
                                               if($item->object_id == \SpbShield\Inc\ThemeConfig::SHOP_PAGE_ID){ ?>
                                                   <div class="filters__category-item filters__category-all">
                                                       <label>
                                                           <input type="radio" name="category" value="0" data-url="<?php echo esc_url($item->url); ?>" <?php if ($term_id == '0') echo 'checked="checked"'; ?>>
                                                           <span class="category"><?php esc_html_e('Show all', 'storefront'); ?></span>
                                                       </label>
                                                   </div>
                                               <?php } else {
                                                   if($novye_postupleniya == $item->object_id) array_push($classes, "filters__category-new");
                                                   if($skidki == $item->object_id) array_push($classes, "filters__category-sale");
                                                   if(!empty($item->sub_item_menu)) {
                                                       $submenu = $item->sub_item_menu;
                                                       ?>
                                                       <div data-spollers="0,min" class="spollers__item  filters__category-item <?php echo esc_attr(implode(' ', $classes)); ?>">
                                                           <button type="button" data-spoller="" class="spollers__title" tabindex="-1"><?php echo esc_html($item->title); ?></button>
                                                           <div class="spollers__body"  hidden>
                                                             <?php foreach($submenu as $item ){
                                                                 $classes = $item->classes;
                                                                 ?>
                                                                 <div class="filters__category-item <?php echo esc_attr(implode(' ', $classes)); ?>">
                                                                     <label>
                                                                         <input type="radio" name="category" value="<?php echo esc_attr($item->object_id); ?>" data-url="<?php echo esc_url($item->url); ?>" <?php if ($term_id == $item->object_id) echo 'checked="checked"'; ?>>
                                                                         <span class="category"><?php echo esc_html($item->title); ?></span>
                                                                     </label>
                                                                 </div>
                                                               <?php } ?>
                                                           </div>
                                                       </div>
                                                  <?php } else { ?>
                                                <div class="filters__category-item <?php echo esc_attr(implode(' ', $classes)); ?>">
                                                    <label>
                                                        <input type="radio" name="category" value="<?php echo esc_attr($item->object_id); ?>" data-url="<?php echo esc_url($item->url); ?>" <?php if ($term_id == $item->object_id) echo 'checked="checked"'; ?>>
                                                        <span class="category"><?php echo esc_html($item->title); ?></span>
                                                    </label>
                                                </div>
                                            <?php }
                                               }
                                            } ?>
									</div>
								</div>
							</div>
							<div id="toreset">
                                <?php
                                $args = array(
                                    'taxonomy'      => 'pa_size',
                                );
                                $terms = get_terms( $args );
                                if(count($terms)):
                                    ?>
								<div class="spollers__item filters__size">
									<button type="button" data-spoller class="spollers__title"><?php esc_html_e('Size', 'storefront'); ?></button>
									<div class="spollers__body">
										<div class="filters__size-list">
											<?php
                                            $view_sizes = ["XS", "S", "M", "L", "XL", "XXL"];
											$SIZES = [];
											if ( isset( $_GET['size'] ) ) {
												$SIZES = array_map( 'absint', explode( '-', sanitize_text_field( wp_unslash( $_GET['size'] ) ) ) );
											}
											foreach( $terms as $term ){
											    if(in_array($term->name, $view_sizes)):
											    ?>
											<div class="filters__size-item">
												<label>
													<input type="checkbox" name="size" value="<?php echo esc_attr($term->term_id); ?>" <?php if (in_array($term->term_id, $SIZES)) echo 'checked="checked"'; ?>>
													<span class="size"><?php echo esc_html($term->name); ?></span>
												</label>
											</div>
											 <?php endif;
											} ?>
										</div>
									</div>
								</div>
                                    <?php
                                endif;
                                    ?>
							</div>
						</div>
						<div class="rs-catalog__filters_button">
							<button type="submit" class="rs-btn _border-btn _black-btn"><?php esc_html_e('apply', 'storefront'); ?></button>
						</div>
					</div>
				</form>
			</div>
			<div class="rs-catalog__list _view-4" offset="0">
				<div class="loading">
					<div class="loading-more">
						<div class="blob top"></div>
						<div class="blob bottom"></div>
						<div class="blob left"></div>
						<div class="blob move-blob"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
<!-- /rs-catalog -->
		
<?php
/**
 * woocommerce_after_main_content hook.
 *
 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
 */
do_action( 'rs_woocommerce_after_main_content' );
?>

<?php get_footer( 'shop' ); ?>
