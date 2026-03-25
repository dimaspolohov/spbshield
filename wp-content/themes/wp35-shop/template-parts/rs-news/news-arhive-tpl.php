<?php get_header();
	global $post;
	$query = new WP_Query( array (
		'post_type' => 'custom_block',
		'meta_query' => array (
			'relation' => 'OR',
			array (
				'key'     => 'block_id',
				'value'   => 5, // block ID
				'compare' => '='
			)
		)
	));
	while ( $query->have_posts() ) {
		$query->the_post();
		$post_meta = get_post_meta($query->post->ID);
	}
	if ($post_meta) {
		$title = get_field("title");
		$description = get_field("description");
	}
	wp_reset_postdata();
?>
<section class="rs-17">
	<div class="rs-news">
		<div class="container rs-news-inner">
			<div class="row">
				<div class="col-xs-12">
					<h1 class="text-center section-title" data-nekoanim="fadeInUp" data-nekodelay="100"><span
							class="section-title--text"><?php echo esc_html( $title ); ?></span></h1>
					<div class="section-descr" data-nekoanim="fadeInUp" data-nekodelay="200">
						<?php echo wp_kses_post( $description ); ?>
					</div>
				</div>
			</div>
			<div class="row news-footer">
				<div class="col-xs-12">
					<?php
						the_posts_pagination( array(
								'show_all'     => false,
								'end_size' => 2,
								'mid_size' => 2,
								'prev_text'    => __('«'),
								'next_text'    => __('»')
							) );
							?>
				</div>
			</div>
			<div class="row">
				<?php
				$news_query = new WP_Query( array(
					'post_type' => 'news',
					'order'     => 'DESC',
					'orderby'   => 'date'
				) );
				if ( $news_query->have_posts() ) :
					while ( $news_query->have_posts() ) : $news_query->the_post();
						get_template_part( 'template-parts/rs-news/content', get_post_type() );
					endwhile;
				else :
					get_template_part( 'content', 'none' );
				endif;
				?>
			</div>
		</div>
	</div>
</section>
<?php wp_reset_postdata(); ?>
<?php get_footer(); ?>
