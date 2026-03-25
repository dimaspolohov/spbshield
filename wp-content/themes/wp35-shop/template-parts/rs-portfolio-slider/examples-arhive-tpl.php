<?php get_header();

	global $post;
	$query = new WP_Query( array (
		'post_type' => 'custom_block',
		'meta_query' => array (
			'relation' => 'OR',
			array (
				'key'     => 'block_id',
				'value'   => 27, // block ID
				'compare' => '='
			)
		)
	));
	while ( $query->have_posts() ) {
		$query->the_post();
		$post_meta = get_post_meta($query->post->ID);
	}
	if ($post_meta) {
		$examples_name = get_field("examples_name") ?: '';
		$examples_text = get_field("examples_text") ?: '';
	}
	wp_reset_postdata();

?>

<section class="rs-17">
	<div class="rs-portfolio-slider">
		<div class="container">
			<div class="row">
				<div class="col-xs-12">
					<?php if ($examples_name) : ?>
						<h2 class="text-center section-title" data-nekoanim="fadeInUp" data-nekodelay="100"><?php echo esc_html( $examples_name ); ?></h2>
					<?php endif; ?>
					<?php if ($examples_text) : ?>
					<div class="section-descr" data-nekoanim="fadeInUp" data-nekodelay="200">
						<p><?php echo esc_html( $examples_text ); ?></p>
					</div>
					<?php endif; ?>
				</div>
			</div>
			<div class="row">
				<div class="col-xs-12">
					<div id="examples-slider" class="owl-carousel">

						<?php
						$examples_query = new WP_Query( array( 'post_type' => 'examples', 'posts_per_page' => -1 ) );
						if ( $examples_query->have_posts() ) :
							while ( $examples_query->have_posts() ) : $examples_query->the_post();
								$img_example = get_field('image');
						?>
						<div class="example">
							<a href="<?php the_permalink(); ?>"><img class="img-responsive b-lazy" src="<?php echo esc_url( get_stylesheet_directory_uri() . '/assets/img/img0.png' ); ?>" data-src="<?php echo esc_url( $img_example['url'] ); ?>" alt="<?php echo esc_attr( $img_example['alt'] ); ?>"></a>
							<div class="example-info">
								<a href="<?php the_permalink(); ?>"><h3><?php the_title(); ?></h3></a>
								<p><?php echo esc_html( get_field('text_anons') ); ?></p>
							</div>
							<a href="<?php the_permalink(); ?>" class="btn-color">Подробнее</a>
						</div>
						<?php endwhile; ?>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
<?php wp_reset_postdata(); ?>
<?php get_footer(); ?>
