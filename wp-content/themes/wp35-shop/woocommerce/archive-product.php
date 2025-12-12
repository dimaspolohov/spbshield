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

$novye_postupleniya = get_field('novye_postupleniya',$frontpage_id);
$skidki = get_field('skidki',$frontpage_id);

$term_id = '0'; 
if( is_product_category() || is_product_tag() ) : 
	$term = get_queried_object(); 
	$term_id = $term->term_id;
endif;

// print_r($novye_postupleniya);
// print_r($skidki);

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
//var_dump(is_shop());

$structureMenu=new StructureMenu(493);
$menu = $structureMenu->getItemsStructureMenu();
 //var_dump($menu);
?>

<!-- rs-catalog -->
<section class="rs-catalog">
	<div class="rs-catalog__container">
		<div class="rs-catalog__wrapper" data-term="<?=$term_id?>">
			<div class="rs-catalog__filters _filters-show" id="filters">
				<div class="rs-catalog__filters_header">
					<button type="button" class="filter-close icon-close"></button>
					<h6 class="small-title"><?_e('Filters','storefront')?></h6>
					<button type="button" class="filter-clear"><?_e('Clear','storefront')?></button>
				</div>
				<form action="#">
					<div class="filters__block">
						<div data-spollers="992" data-one-spoller class="filters__spollers spollers">
							<div class="spollers__item filters__category">
								<button type="button" data-spoller class="spollers__title"><?_e('Categories','storefront')?></button>
								<div class="spollers__body">
									<div class="filters__category-list">

                                            <?php
                                            foreach( $menu as $item ){
                                               $classes = $item->classes;
                                               if($item->object_id == 5){ ?>
                                                   <div class="filters__category-item filters__category-all">
                                                       <label>
                                                           <input type="radio" name="category" value="0" data-url="<?=$item->url?>" <? if($term_id=='0') echo 'checked="checked"'?>>
                                                           <span class="category"><?_e('Show all','storefront')?></span>
                                                       </label>
                                                   </div>
                                               <?php } else {
                                                   if($novye_postupleniya ==$item->object_id ) array_push($classes,"filters__category-new");
                                                   if($skidki ==$item->object_id ) array_push($classes,"filters__category-sale");
                                                   if(!empty($item->sub_item_menu)) {
                                                       $submenu = $item->sub_item_menu;
                                                       ?>
                                                       <div data-spollers="0,min" class="spollers__item  filters__category-item <?= implode(" ", $classes)?>">
                                                           <button type="button" data-spoller="" class="spollers__title" tabindex="-1"><?=$item->title?></button>
                                                           <div class="spollers__body"  hidden>
                                                             <?php  foreach($submenu as $item ){
                                                                 $classes = $item->classes;
                                                                 ?>
                                                                 <div class="filters__category-item <?= implode(" ", $classes)?>">
                                                                     <label>
                                                                         <input type="radio" name="category" value="<?=$item->object_id?>" data-url="<?=$item->url?>" <? if($term_id==$item->object_id) echo 'checked="checked"'?>>
                                                                         <span class="category"><?=$item->title?></span>
                                                                     </label>
                                                                 </div>
                                                               <? }?>
                                                           </div>
                                                       </div>
                                                  <? } else {
                                               ?>
                                                <div class="filters__category-item <?= implode(" ", $classes)?>">
                                                    <label>
                                                        <input type="radio" name="category" value="<?=$item->object_id?>" data-url="<?=$item->url?>" <? if($term_id==$item->object_id) echo 'checked="checked"'?>>
                                                        <span class="category"><?=$item->title?></span>
                                                    </label>
                                                </div>
                                            <?php }
                                               }
                                            }?>
									</div>
								</div>
							</div>
							<div id="toreset">
                                <?
                                $args = array(
                                    'taxonomy'      => 'pa_color',
                                );
                                $terms = get_terms( $args );
/* раскомментировать, если нужен фильтр по цвету
                                if(count($terms)):
                               ?>
								<div class="spollers__item filters__color">
									<button type="button" data-spoller class="spollers__title"><?_e('Color','storefront')?></button>
									<div class="spollers__body">
										<div class="filters__color-list">
											<?

											$COLORS = [];
											if(isset($_GET['color'])) $COLORS = explode('-',$_GET['color']);

											foreach( $terms as $term ){ ?>
											<div class="filters__color-item">
												<label>
													<input type="checkbox" name="color" value="<?=$term->term_id?>" <? if(in_array($term->term_id, $COLORS)) echo 'checked="checked"'?>>
													<span class="color" style="background-color:#<?=$term->slug?>"></span>
												</label>
											</div>
											<? } ?>
										</div>
									</div>
								</div>
                                    <?
                                endif;
*/
                                $args = array(
                                    'taxonomy'      => 'pa_size',
                                );
                                $terms = get_terms( $args );
                                if(count($terms)):
                                    ?>
								<div class="spollers__item filters__size">
									<button type="button" data-spoller class="spollers__title"><?_e('Size','storefront')?></button>
									<div class="spollers__body">
										<div class="filters__size-list">
											<?
                                            $view_sizes=["XS", "S", "M", "L", "XL", "XXL"];
											$SIZES = [];
											if(isset($_GET['size'])) $SIZES = explode('-',$_GET['size']);
											foreach( $terms as $term ){
											    if(in_array($term->name, $view_sizes)):
											    ?>
											<div class="filters__size-item">
												<label>
													<input type="checkbox" name="size" value="<?=$term->term_id?>" <? if(in_array($term->term_id, $SIZES)) echo 'checked="checked"'?>>
													<span class="size"><?=$term->name?></span>
												</label>
											</div>
											 <? endif;
											} ?>
										</div>
										<? //size_guide()?>
									</div>
								</div>
                                    <?
                                endif;
                                    ?>
							</div>
						</div>
						<div class="rs-catalog__filters_button">
							<button type="submit" class="rs-btn _border-btn _black-btn"><?_e('apply','storefront')?></button>
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

<? get_footer( 'shop' ); ?>