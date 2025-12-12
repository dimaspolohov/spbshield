jQuery( document ).ready(function() {
	load_collections();
});

jQuery( window ).on('scroll',function() {
	if(jQuery('.loading-more').length>0 && jQuery('.rs-collections-archive__container.loading').length==0) {
		var $window = jQuery( window ).height();
		var $top = jQuery('.loading-more').offset().top-$window;
		var $scroll = jQuery(document).scrollTop();
		if($top<=$scroll){
			var $offset = jQuery('.rs-collections-archive__container').attr('offset');
			load_collections( $offset );
		}
	}
});

function load_collections( offset = 0 ) {
	jQuery('.rs-collections-archive__container').addClass('loading');	
	var data = {
		action: 'getCollections',
		offset: offset,
		number: 2,
	};
	jQuery.ajax( {
		data:data,
		type:'POST',
		url:'/wp-admin/admin-ajax.php',
		success: function(response,status) {
			var $response = jQuery.parseJSON(response);
			jQuery('.loading-more').remove();
			if(offset==0) {
				jQuery('.rs-collections-archive__container').html($response[0]);
			} else {
				jQuery('.rs-collections-archive__container').append($response[0]);
			}
			offset = parseInt(offset) + 2;
			jQuery('.rs-collections-archive__container').attr('offset',offset);
			jQuery('.rs-collections-archive__container').removeClass('loading');
		}
	});
}