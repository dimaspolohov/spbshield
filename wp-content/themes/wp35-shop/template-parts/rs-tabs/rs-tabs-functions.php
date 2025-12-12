<?php
function style_rs_tabs_theme() {
    wp_enqueue_style( 'rs-tabs-theme', get_stylesheet_directory_uri().'/template-parts/rs-tabs/css/rs-tabs.css');
}

function storefront_rs_tabs() {
	
	
		$tabs = get_field("tabs") ?: '';
		


        add_action( 'wp_print_scripts', 'style_rs_tabs_theme', 11);

	
	
	?>
	<?php if ( is_array($tabs) ) { 
		foreach($tabs as $key0 => $item0) {
			$title = $item0['title'];
			$description = $item0['description'];
			$tab_content = $item0['tab_content'];
			?>
		<section class="rs-17">
			<div class="rs-tabs" >
				<div class="container">
					<div class="row">
						<div class="col-xs-12">
							<?php if($item0['title']){?>	
								<h2 class="text-center section-title" data-nekoanim="fadeInUp" data-nekodelay="100"><?=$title; ?></h2>
							<?}?>	
							<?php if($item0['description']){?>	
								<div class="section-descr" data-nekoanim="fadeInUp" data-nekodelay="200">
									<?=$description; ?>
								</div>
							<?}?>
						</div>
					</div>
					<div class="tabs-row row">
						<div class="col-xs-12 col-sm-4 col-md-3" data-nekoanim="fadeInLeft" data-nekodelay="300">
							<ul class="nav nav-pills nav-stacked" role="tablist">
							<?php
								$i = 0;
								foreach($tab_content as $key => $item) {
									?> 
									<li role="presentation" class="<?php if ( $i++ == 0 ) echo 'active' ?>">
										<a href="#tab-text<?=$key0 ?><?=$i ?>" role="tab" data-toggle="tab"><?=$item['name']; ?></a></li>
									<?php
								}
							?>
							</ul>
						</div>
						<div class="col-xs-12 col-sm-8 col-md-9" data-nekoanim="fadeInRight" data-nekodelay="600">
							<div class="tab-content">
							<?php
								$i = 0;
								foreach($tab_content as $item) {
									?> 
									<div role="tabpanel" class="tab-pane <?php if ( $i++ == 0 ) echo 'active' ?> fade in" id="tab-text<?=$key0 ?><?=$i; ?>">
										<?=$item['data']; ?>
									</div>
									<?php
								}
							?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
		<?php
		}
	}
}