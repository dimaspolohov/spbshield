<section class="rs-17">
	<div class="rs-text-block">
		<div class="container">
			<div class="row">
				<div class="col-xs-12" data-nekoanim="fadeInUp" data-nekodelay="200">
					<?php
					if ( get_field( 'text_block_before_show' ) ) {
						$title   = get_field( 'text_block_before_title' );
						$content = get_field( 'text_block_before' );
						if ( $title ) {
							echo '<h2>' . esc_html( $title ) . '</h2>';
						}
						if ( $content ) {
							echo wp_kses_post( $content );
						}
					}
					?>
				</div>
			</div>
		</div>
	</div>
</section>
