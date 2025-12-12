<?php function storefront_rs_media() {     
	$query = new WP_Query( array (         
		'post_type' => 'custom_block',         
		'meta_query' => array (             
			'relation' => 'OR',             
			array (                 
				'key'     => 'block_id',                 
				'value'   => 63, // идентификатор блока                 
				'compare' => '='             
			)         
		)     
	));     
	
	while ( $query->have_posts() ) {         
		$query->the_post();         
		$post_meta = get_post_meta($query->post->ID); 		
		// echo $query->post->ID;     
	}     
	
	if ($post_meta) {         
		$block_title = get_field("block_title");     
	} 
	?> 
	
	<!-- rs-media --> 	
	<?php 	
	$posts = get_field('rs-media'); 	
	if( $posts ) : 
	?> 	
	<?php $post_type = 'news' ?> 	
	<section class="rs-media"> 		
		<div class="rs-media__container"> 			
			<h2 class="section-title"><a href="<? echo get_post_type_archive_link( $post_type )?>"><?=get_post_type_object( $post_type )->label?></a></h2> 			
			<div class="rs-media__slider swiper"> 				
				<div class="rs-media__swiper swiper-wrapper"> 					
					<?php  					
					global $post; 					
					// Ограничиваем вывод только первыми 3 постами
					$limited_posts = array_slice($posts, 0, 3);
					
					foreach( $limited_posts as $key => $item ) : 						
						$post = $item['item']; 						
						setup_postdata( $post ); 
					?> 						
						<div class="rs-media__slide swiper-slide"> 							
							<a href="<?php the_permalink()?>" class="rs-media__item<?php if($key == 0) : ?> rs-media__item-big<?php endif ?>"> 								
								<div class="rs-media__picture"> 									
									<?php if($key == 0) : ?> 									
									<picture> 										
										<source srcset="<?=get_the_post_thumbnail_url( $post, 'full' )?>.webp" type="image/webp"> 										
										<img src="<?=get_the_post_thumbnail_url( $post, 'full' )?>" alt=""> 									
									</picture> 									
									<?php endif ?> 									
									<picture> 										
										<source srcset="<?=get_the_post_thumbnail_url( $post, 'img-rs-media' )?>.webp" type="image/webp"> 										
										<img src="<?=get_the_post_thumbnail_url( $post, 'img-rs-media' )?>" alt=""> 									
									</picture> 								
								</div> 							
							</a> 							
							<div class="rs-media__description"> 								
								<a href="<?php the_permalink()?>"> 									
									<h4 class="l-regular-title"><? the_title()?></h4> 								
								</a> 								
								<a href="<?php the_permalink()?>" class="rs-btn _border-btn _black-border-btn"><?php _e( 'Read more', 'storefront' )?></a> 							
							</div> 						
						</div> 						
					<?php  					
					endforeach;  					
					wp_reset_postdata(); 					
					?> 				
				</div> 			
			</div> 		
		</div> 	
	</section> 	
	<?php endif; ?> 	
	<!-- /rs-media --> 	
<?php }