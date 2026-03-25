<?php
function bottom_data_widget() {

	register_widget( 'bottom_widget' );
}

add_action( 'widgets_init', 'bottom_data_widget' );

class bottom_widget extends WP_Widget {

	function __construct() {
		parent::__construct(
		// widget ID
		'bottom_widget',
		// widget name
		'Копирайт и соцсети',
		// widget description
		array( 'description' => 'Виджет для размещения копирайтов и ссылок на соцсети в подвале', )
		);
	}
	public function widget( $args, $instance ) {
		$copyright_text = $instance['copyright_text'];
		$vk_link = $instance['vk_link'];
		$fb_link = $instance['fb_link'];
		$inst_link = $instance['inst_link'];
		$ok_link = $instance['ok_link'];
		$tw_link = $instance['tw_link'];
		?>
			<div class="pull-left copyright">
				<p>&copy; <?php echo esc_html( $copyright_text ); ?></p>
			</div>
			<div class="pull-right right-block">
				<ul class="social-list">
					<?php if ($vk_link) { ?>
						<li><a href="<?php echo esc_url( $vk_link ); ?>"><i class="fab fa-vk"></i></a></li>
					<?php } ?>
					<?php if ($fb_link) { ?>
						<li><a href="<?php echo esc_url( $fb_link ); ?>"><i class="fab fa-facebook-f"></i></a></li>
					<?php } ?>
					<?php if ($inst_link) { ?>
						<li><a href="<?php echo esc_url( $inst_link ); ?>"><i class="fab fa-instagram"></i></a></li>
					<?php } ?>
					<?php if ($ok_link) { ?>
						<li><a href="<?php echo esc_url( $ok_link ); ?>"><i class="fab fa-odnoklassniki"></i></a></li>
					<?php } ?>
					<?php if ($tw_link) { ?>
						<li><a href="<?php echo esc_url( $tw_link ); ?>"><i class="fab fa-twitter"></i></a></li>
					<?php } ?>
				</ul>
				<a target="_blank" href="https://rosait.ru/" class="dev">Разработано в <img src="<?php echo esc_url( get_stylesheet_directory_uri() ); ?>/assets/img/rossait_white.svg" alt="Россайт">
				</a>
			</div>

		<?php
		echo $args['after_widget'];
	}
	public function form( $instance ) {
		if ( isset( $instance[ 'copyright_text' ] ) ) $copyright_text = $instance[ 'copyright_text' ]; else $copyright_text = 'Все права защищены';
		if ( isset( $instance[ 'vk_link' ] ) ) $vk_link = $instance[ 'vk_link' ]; else $vk_link = '';
		if ( isset( $instance[ 'fb_link' ] ) ) $fb_link = $instance[ 'fb_link' ]; else $fb_link = '';
		if ( isset( $instance[ 'tw_link' ] ) ) $tw_link = $instance[ 'tw_link' ]; else $tw_link = '';
		if ( isset( $instance[ 'ok_link' ] ) ) $ok_link = $instance[ 'ok_link' ]; else $ok_link = '';
		if ( isset( $instance[ 'inst_link' ] ) ) $inst_link = $instance[ 'inst_link' ]; else $inst_link = '';
										
		?>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'copyright_text' ) ); ?>"><?php _e( 'Copyright text:' ); ?></label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'copyright_text' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'copyright_text' ) ); ?>" type="text" value="<?php echo esc_attr( $copyright_text ); ?>" />
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'vk_link' ) ); ?>"><?php _e( 'VK URL:' ); ?></label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'vk_link' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'vk_link' ) ); ?>" type="text" value="<?php echo esc_attr( $vk_link ); ?>" />
			</p>			
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'fb_link' ) ); ?>"><?php _e( 'Facebook URL:' ); ?></label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'fb_link' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'fb_link' ) ); ?>" type="text" value="<?php echo esc_attr( $fb_link ); ?>" />
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'tw_link' ) ); ?>"><?php _e( 'Twitter URL:' ); ?></label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'tw_link' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'tw_link' ) ); ?>" type="text" value="<?php echo esc_attr( $tw_link ); ?>" />
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'ok_link' ) ); ?>"><?php _e( 'Odnoklassniki URL:' ); ?></label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'ok_link' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'ok_link' ) ); ?>" type="text" value="<?php echo esc_attr( $ok_link ); ?>" />
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'inst_link' ) ); ?>"><?php _e( 'Instagram URL:' ); ?></label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'inst_link' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'inst_link' ) ); ?>" type="text" value="<?php echo esc_attr( $inst_link ); ?>" />
			</p>																							
		<?php 
	}
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['copyright_text'] = ( ! empty( $new_instance['copyright_text'] ) ) ? strip_tags( $new_instance['copyright_text'] ) : ''; 
		$instance['vk_link'] = ( ! empty( $new_instance['vk_link'] ) ) ? esc_url_raw( $new_instance['vk_link'] ) : ''; 
		$instance['fb_link'] = ( ! empty( $new_instance['fb_link'] ) ) ? esc_url_raw( $new_instance['fb_link'] ) : ''; 
		$instance['tw_link'] = ( ! empty( $new_instance['tw_link'] ) ) ? esc_url_raw( $new_instance['tw_link'] ) : ''; 
		$instance['ok_link'] = ( ! empty( $new_instance['ok_link'] ) ) ? esc_url_raw( $new_instance['ok_link'] ) : ''; 
		$instance['inst_link'] = ( ! empty( $new_instance['inst_link'] ) ) ? esc_url_raw( $new_instance['inst_link'] ) : ''; 		
		
		return $instance;
	}

}
