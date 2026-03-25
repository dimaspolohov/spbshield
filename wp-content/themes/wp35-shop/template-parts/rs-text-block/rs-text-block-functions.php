<?php

// Enqueue text block styles
add_action( 'wp_enqueue_scripts', 'style_rs_text_block_theme' );

function style_rs_text_block_theme() {
	wp_enqueue_style( 'rs-text-block-theme', get_stylesheet_directory_uri() . '/template-parts/rs-text-block/css/rs-text-block.css' );
}

// Rename post type labels for text blocks
function rename_posts_labels_text_block( $labels ) {
	$new = array(
		'name'          => 'Текстовые блоки',
		'singular_name' => 'Текстовый блок',
		'add_new'       => 'Добавить новый',
		'add_new_item'  => 'Добавить новый',
	);
	return (object) array_merge( (array) $labels, $new );
}

// Render text block on the homepage
function storefront_homepage_content_child() {
	?>
	<section class="rs-17">
		<div class="rs-text-block">
			<div class="container">
				<div class="row">
					<div class="col-xs-12" data-nekoanim="fadeInUp" data-nekodelay="200">
						<?php
						while ( have_posts() ) {
							the_post();
							$title   = get_the_title();
							$content = get_the_content();
							?>
							<?php if ( $title || $content ) : ?>
								<?php if ( $title ) : ?>
									<h2><?php echo esc_html( $title ); ?></h2>
								<?php endif; ?>
								<?php if ( $content ) : ?>
									<?php echo wp_kses_post( $content ); ?>
								<?php endif; ?>
							<?php endif;
						}
						?>
					</div>
				</div>
			</div>
		</div>
	</section>
	<?php
}
