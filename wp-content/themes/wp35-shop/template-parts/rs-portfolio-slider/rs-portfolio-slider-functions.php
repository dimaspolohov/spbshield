<?php

// Enqueue block styles
add_action('wp_enqueue_scripts', 'style_rs_portfolio_slider_theme', 11);
function style_rs_portfolio_slider_theme() {
	wp_enqueue_style('rs-portfolio-slider', get_stylesheet_directory_uri() . '/template-parts/rs-portfolio-slider/css/rs-portfolio-slider.css');
}

// Post type registration
\SpbShield\Inc\TemplatePostTypes::register('examples', 'Наши проекты', [
	'supports'   => ['title', 'editor', 'thumbnail'],
	'taxonomies' => ['post_tag'],
	'menu_icon' => 'dashicons-admin-page'
]);

add_filter('post_type_labels_examples', 'rename_posts_labels_examples');
function rename_posts_labels_examples($labels) {

	$new = array(
		'name'                  => 'Проекты',
		'singular_name'         => 'Проект',
		'add_new'               => 'Добавить проект',
		'add_new_item'          => 'Добавить проект',
		'edit_item'             => 'Редактировать проект',
		'new_item'              => 'Новый проект',
		'view_item'             => 'Просмотреть проект',
		'search_items'          => 'Поиск проектов',
		'not_found'             => 'Проектов не найдено.',
		'not_found_in_trash'    => 'Проектов в корзине не найдено.',
		'parent_item_colon'     => '',
		'all_items'             => 'Все проекты',
		'archives'              => 'Архивы проектов',
		'insert_into_item'      => 'Вставить в проект',
		'uploaded_to_this_item' => 'Загруженные для этого проекта ',
		'featured_image'        => 'Миниатюра проекта',
		'filter_items_list'     => 'Фильтровать список проектов',
		'items_list_navigation' => 'Навигация по списку проектов',
		'items_list'            => 'Список проектов',
		'menu_name'             => 'Наши проекты',
		'name_admin_bar'        => 'Наши проекты',
	);

	return (object) array_merge((array) $labels, $new);
}

add_filter('template_include', 'my_template_examples');
function my_template_examples($template) {

	// Archive template for "examples" post type
	global $posts;
	if (is_post_type_archive('examples')) {
		return get_stylesheet_directory() . '/template-parts/rs-portfolio-slider/examples-arhive-tpl.php';
	}

	// Single template for "examples" post type
	global $post;
	if (isset($post->post_type) && $post->post_type == 'examples') {
		return get_stylesheet_directory() . '/template-parts/rs-portfolio-slider/examples-tpl.php';
	}

	return $template;

}

// Render the examples block
function storefront_examples_child() {
	$query = new WP_Query(array(
		'post_type' => 'custom_block',
		'meta_query' => array(
			'relation' => 'OR',
			array(
				'key'     => 'block_id',
				'value'   => 27, // block identifier
				'compare' => '='
			)
		)
	));

	$post_meta = null;
	$examples_name = '';
	$examples_text = '';

	while ($query->have_posts()) {
		$query->the_post();
		$post_meta = get_post_meta($query->post->ID);
	}
	if ($post_meta) {
		$examples_name = get_field("examples_name") ?: '';
		$examples_text = get_field("examples_text") ?: '';
	}
	wp_reset_postdata();

	$examples_query = new WP_Query(array(
		'post_type'      => 'examples',
		'posts_per_page' => -1,
	));

?>

<section class="rs-17">
	<div class="rs-portfolio-slider">
		<div class="container">
			<div class="row">
				<div class="col-xs-12">
					<?php if ($examples_name) : ?>
						<h2 class="text-center section-title" data-nekoanim="fadeInUp" data-nekodelay="100"><?php echo esc_html($examples_name); ?></h2>
					<?php endif; ?>
					<?php if ($examples_text) : ?>
					<div class="section-descr" data-nekoanim="fadeInUp" data-nekodelay="200">
						<p><?php echo esc_html($examples_text); ?></p>
					</div>
					<?php endif; ?>
				</div>
			</div>
			<div class="row">
				<div class="col-xs-12">
					<div id="examples-slider" class="owl-carousel">
						
						<?php if ($examples_query->have_posts()) : ?>
						<?php while ($examples_query->have_posts()) : $examples_query->the_post(); ?>
						<?php $img_example = get_field('image'); ?>
						<div class="example">
							<a href="<?php echo esc_url(get_the_permalink()); ?>">
								<?php if (is_array($img_example)) : ?>
								<img class="img-responsive b-lazy" data-src="<?php echo esc_url($img_example['url']); ?>" src="<?php echo esc_url(get_stylesheet_directory_uri() . '/assets/img/img0.png'); ?>" alt="<?php echo esc_attr($img_example['alt']); ?>">
								<?php endif; ?>
							</a>
							<div class="example-info">
								<a href="<?php echo esc_url(get_the_permalink()); ?>"><h3><?php echo esc_html(get_the_title()); ?></h3></a>
								<p><?php echo esc_html(get_field('text_anons')); ?></p>
							</div>
							<a href="<?php echo esc_url(get_the_permalink()); ?>" class="btn-color">Подробнее</a>
						</div>
							<?php endwhile; ?>
						<?php endif; ?>

					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<?php
	wp_reset_postdata();
}
