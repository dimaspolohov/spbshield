<?php get_header(); ?>

<?php while ( have_posts() ) : the_post(); ?>
	<div class="rs-17">
		<div class="rs-page">
			<div class="container rs-page-inner">
				<div class="row">
					<div class="col-xs-12">
						<h1 class="text-center section-title" data-nekoanim="fadeInUp" data-nekodelay="50"><?php the_title(); ?></h1>

						<div class="news-date text-center"><?php echo esc_html( get_the_date("j F Y") ); ?></div>
						<?php if ( get_field('desc') ) { ?>
							<div class="section-descr">
								<p><?php echo esc_html( get_field('desc') ); ?></p>
							</div>
						<?php } ?>
					</div>
					<div class="col-xs-12 clearfix about-main">
						<?php the_content(); ?>
					</div>
					<div class="col-xs-12">
					<a href="<?php echo esc_url( get_post_type_archive_link('news') ); ?>" class="btn btn-link btn-back"><i class="fa fa-long-arrow-left"></i>Назад к списку</a>
					</div>
				</div>

			</div>
		</div>
	</div>
<?php endwhile; ?>

<?php get_footer(); ?>
