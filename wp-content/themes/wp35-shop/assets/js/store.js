jQuery('#rs_store_form').submit(function(event){
	event.preventDefault();
	var $form = jQuery(this);
	if( !validate_rs_store_form( $form ) ) {
		$form.addClass('loading');
		/**/
		
		$this = jQuery('[name="user-attachment"]');
        file_obj = $this.prop('files');
        form_data = new FormData();
        for(i=0; i<file_obj.length; i++) {
            form_data.append('file[]', file_obj[i]);
        }
        form_data.append('action', 'file_upload');
        jQuery.ajax({
            url:'/wp-admin/admin-ajax.php',
            type: 'POST',
            contentType: false,
            processData: false,
            data: form_data,
            success: function (response) {
				console.log(response);
				var data = {
					action: 'rs_store_form_function',
					name: jQuery('[name="user-name"]',$form).val(),
					email: jQuery('[name="user-email"]',$form).val(),
					category: jQuery('[name="user-category"]',$form).val(),
					message: jQuery('[name="user-message"]',$form).val(),
					attachment: response,
					page_id: $form.attr('data-page'),
				};
				jQuery.ajax( {
					data:data,
					type:'POST',
					url:'/wp-admin/admin-ajax.php',
					success: function(response,status) {
						$form.removeClass('loading');
						$form[0].reset();
						jQuery('.valid',$form).removeClass('valid');
						jQuery('.invalid',$form).removeClass('invalid');
						jQuery('.form-message.success').slideDown();
						jQuery('[name="user-attachment"]').next().html(jQuery('.default',$form).html());
					}
				});
            }
        });
	};
});

jQuery('#rs_store_form').click(function(event){
	jQuery('.form-message.success').slideUp();
});

jQuery('body').on('change','#rs_store_form [name="user-name"],#rs_store_form [name="user-email"],#rs_store_form [name="user-category"]',function(){
	var $form = jQuery('#rs_store_form');
	var $name = jQuery(this).attr('name');
	validate_rs_store_form( $form, $name );
});

jQuery('.input-file input[type=file]').on('change', function(){
	var $container = jQuery(this).closest('.form-field');
	if(this.files.length>0){
		if(this.files.length<2){
			let file = this.files[0];
			jQuery(this).next().html(file.name);
		} else {
			jQuery(this).next().html(this.files.length + jQuery('.multi',$container).html());		
		}
	} else {
		jQuery(this).next().html(jQuery('.default',$container).html());		
	}
});

function validate_rs_store_form( $form, $name = '' ){	
	var $error = false;
	if($name=='' || $name=='user-name') {
		$value = jQuery('[name="user-name"]',$form).val();
		if(!$value || $value=='') {
			jQuery('[name="user-name"]',$form).addClass('invalid');
			$error = true;
		} else {
			jQuery('[name="user-name"]',$form).removeClass('invalid').addClass('valid');
		}
	}
	if($name=='' || $name=='user-email') {
		$value = jQuery('[name="user-email"]',$form).val();
		if(!$value || $value=='' || !isEmail($value)) {
			jQuery('[name="user-email"]',$form).addClass('invalid');
			$error = true;
		} else {
			jQuery('[name="user-email"]',$form).removeClass('invalid').addClass('valid');
		}
	}
	if($name=='' || $name=='user-category') {
		$value = jQuery('[name="user-category"]',$form).val();
		if(!$value || $value=='') {
			jQuery('[name="user-category"]',$form).addClass('invalid');
			$error = true;
		} else {
			jQuery('[name="user-category"]',$form).removeClass('invalid').addClass('valid');
		}
	}
	if($error===true) {
		jQuery('.form-message.error').slideDown();
	} else {
		jQuery('.form-message.error').slideUp();
	}
	return $error;
}

function isEmail(email) {
	var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
	return regex.test(email);
}