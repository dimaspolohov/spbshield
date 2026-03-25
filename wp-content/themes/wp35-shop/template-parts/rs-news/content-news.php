<div class="col-xs-6 col-sm-6 col-md-3 news-block">
	<div class="news-item" data-nekoanim="fadeInUp" data-nekodelay="400">
		<a class="news-image b-lazy" href="<?php echo esc_url(get_the_permalink()); ?>"
           data-src="<?php echo esc_url(get_the_post_thumbnail_url()); ?>"></a>
		<div class="news-title">
			<h3>
				<a href="<?php echo esc_url(get_the_permalink()); ?>"><?php the_title(); ?></a>
			</h3>
		</div>
			<div class="news-date">
				<?php echo esc_html(get_the_date("j F Y")); ?>
			</div>
		<?php if(get_the_content()) { ?>
		<div class="news-content">
			<?php if (get_the_content()) {?>
				<?php echo \SpbShield\Inc\ServiceFunctions::excerpt(['maxchar' => 80]); ?>
			<?php } ?>
		</div><?php }
	?>
		<div class="news-more">
			<a href="<?php echo esc_url(get_the_permalink()); ?>" class="btn btn-outline">Подробнее</a>
		</div>
	</div>
</div>