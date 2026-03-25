<?php
/**
 * Collection page header/about section.
 *
 * Displays the store-about block with collection title and description
 * from ACF fields 'zagolovok_v_kataloge' and 'opisanie_v_kataloge'.
 *
 * @package dazzling
 */

defined('ABSPATH') || exit;
?>
<div class="store-content__about">
	<div class="store-about">
		<h2 class="store-about__title"><b><?php echo esc_html(get_field('zagolovok_v_kataloge')); ?></b></h2>
		<p class="store-about__text"><?php echo wp_kses_post(get_field('opisanie_v_kataloge')); ?></p>
	</div>
</div>
