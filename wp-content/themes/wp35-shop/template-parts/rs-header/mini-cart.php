<li id="header-cart-content">
    <a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="icon-bag svg-icon">
		<?php $count = WC()->cart->get_cart_contents_count(); ?>
		<svg width="17" height="19" xmlns="http://www.w3.org/2000/svg" xml:space="preserve" style="enable-background:new 0 0 17 19" viewBox="0 0 17 19"><path d="M5.5 4.2c0-1.7 1.4-3 3-3s3 1.3 3 3v.6h1.2v-.6c0-2.3-1.9-4.2-4.2-4.2-2.3 0-4.2 1.9-4.2 4.2v.6h1.2v-.6z"/><path d="M.7 5v11.1c0 1.2 1 2.2 2.2 2.2H14c1.2 0 2.2-1 2.2-2.2V5H.7z" style="fill:<?php if($count>0) { echo '#000'; } else echo 'none'; ?>;stroke:#000;stroke-width:1.15"/></svg>
		<?php if($count>0) { ?><span><?=$count?></span><? } ?>
    </a>
</li>