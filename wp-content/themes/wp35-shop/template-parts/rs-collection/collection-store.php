<?php
/**
 * Collection store product grid.
 *
 * Renders a product slider for collection pages, supporting multiple data sources.
 *
 * Accepted parameters via $args:
 *   - products_source : 'tovary' | 'relationship' | 'category' (default: 'tovary')
 *   - button_text     : CTA button text (default: '')
 *   - button_link     : CTA button URL (default: '')
 *
 * @package dazzling
 */

defined('ABSPATH') || exit;

$products_source = $args['products_source'] ?? 'tovary';
$button_text     = $args['button_text'] ?? '';
$button_link     = $args['button_link'] ?? '';

$cards = [];

switch ($products_source) {
	case 'tovary':
		if (have_rows('tovary')) {
			while (have_rows('tovary')) {
				the_row();
				$image_data = get_sub_field('kartinka');
				$image_id   = is_array($image_data) ? ($image_data['ID'] ?? 0) : 0;
				$product_id = get_sub_field('tovar');
				$product    = wc_get_product($product_id);

				if (!$product) {
					continue;
				}

				$cards[] = [
					'image_html' => wp_get_attachment_image($image_id, 'full'),
					'title'      => get_sub_field('nazvanie'),
					'product'    => $product,
					'product_id' => $product_id,
					'link_text'  => get_sub_field('kategoriya'),
				];
			}
		}
		break;

	case 'relationship':
		$featured_posts = get_field('rs-collection');
		if ($featured_posts) {
			foreach ($featured_posts as $rel_post) {
				$product_id  = $rel_post->ID;
				$product     = wc_get_product($product_id);

				if (!$product) {
					continue;
				}

				$image_url = get_the_post_thumbnail_url($product_id, 'medium');
				$cards[] = [
					'image_html' => '<img src="' . esc_url($image_url) . '" alt="' . esc_attr(get_the_title($product_id)) . '">',
					'title'      => get_the_title($product_id),
					'product'    => $product,
					'product_id' => $product_id,
					'link_text'  => '',
				];
			}
		}
		break;

	case 'category':
		$collection_category = get_field('kategoriya_kollekczii');
		$category_term_id    = is_object($collection_category) ? $collection_category->term_id : (int) $collection_category;
		$category_name       = '';

		if (is_object($collection_category)) {
			$category_name = $collection_category->name;
		} elseif ($category_term_id) {
			$term = get_term($category_term_id);
			$category_name = $term ? $term->name : '';
		}

		if ($category_term_id) {
			$products_query = new WP_Query([
				'post_type'      => 'product',
				'posts_per_page' => -1,
				'orderby'        => 'date',
				'order'          => 'ASC',
				'tax_query'      => [[
					'taxonomy' => 'product_cat',
					'field'    => 'term_id',
					'terms'    => $category_term_id,
				]],
			]);

			if ($products_query->have_posts()) {
				while ($products_query->have_posts()) {
					$products_query->the_post();
					$product_id = get_the_ID();
					$product    = wc_get_product($product_id);

					if (!$product) {
						continue;
					}

					$image_id = get_post_thumbnail_id($product_id);
					$cards[] = [
						'image_html' => wp_get_attachment_image($image_id, 'full'),
						'title'      => $product->get_name(),
						'product'    => $product,
						'product_id' => $product_id,
						'link_text'  => esc_html($category_name),
					];
				}
				wp_reset_postdata();
			}
		}
		break;
}

if (!empty($cards)) : ?>
<div class="store-content__goods">
	<div class="store-goods">
		<br/><br/><br/><br/>
		<div class="store-goods__slider">
			<div class="store-goods__swiper">
				<?php foreach ($cards as $card) :
					$product       = $card['product'];
					$regular_price = (float) ($product->is_type('variable')
						? $product->get_variation_regular_price('min')
						: $product->get_regular_price());
					$sale_price    = (float) ($product->is_type('variable')
						? $product->get_variation_sale_price('min')
						: $product->get_sale_price());
				?>
				<div class="store-goods__slide">
					<article class="goods-card">
						<div class="goods-card__wrapper">
							<div class="goods-card__photo"><?php echo $card['image_html']; ?></div>
							<h5 class="goods-card__title"><?php echo esc_html($card['title']); ?></h5>
							<div class="goods-card__price">
								<?php if ($sale_price && $sale_price !== $regular_price) : ?>
									<del><?php echo number_format($regular_price, 0, ',', '&nbsp;') . '&nbsp;&#8381;'; ?></del>
								<?php endif; ?>
								<span><?php echo number_format($sale_price ?: $regular_price, 0, ',', '&nbsp;') . '&nbsp;&#8381;'; ?></span>
							</div>
						</div>
						<a href="<?php echo esc_url(get_permalink($card['product_id'])); ?>" target="_blank"><?php echo esc_html($card['link_text']); ?></a>
					</article>
				</div>
				<?php endforeach; ?>
			</div>
		</div>
		<div class="store-goods__nav">
			<div class="slider-arrows">
				<button type="button" class="slider-arrow slider-arrow_prev">
					<svg viewBox="0 0 14 26" fill="none" xmlns="http://www.w3.org/2000/svg">
						<path d="M13 1L1 13L13 25" stroke="black" />
					</svg>
				</button>
				<button type="button" class="slider-arrow slider-arrow_next">
					<svg viewBox="0 0 14 26" fill="none" xmlns="http://www.w3.org/2000/svg">
						<path d="M1 1L13 13L1 25" stroke="black" />
					</svg>
				</button>
			</div>
		</div>
	</div>
</div>
<?php endif; ?>

<br/><br/>
<?php if ($button_link && $button_text) : ?>
<a href="<?php echo esc_url($button_link); ?>" class="rs-btn _black-border-btn"><?php echo esc_html($button_text); ?></a>
<?php endif; ?>
