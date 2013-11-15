jQuery(document).ready(function (){
	jQuery('#inicio').datepicker({
		inline: true,
		dateFormat: 'yy-mm-dd'
	});
	jQuery('#fin').datepicker({
		inline: true,
		dateFormat: 'yy-mm-dd'
	});
	jQuery('#inicioC').datepicker({
		inline: true,
		dateFormat: 'yy-mm-dd'
	});
	jQuery('#finC').datepicker({
		inline: true,
		dateFormat: 'yy-mm-dd'
	});
	jQuery('#frm_limpiar').click(function(){
		jQuery("#inicio").val('');
		jQuery("#fin").val('');
		location.href = jQuery("#redir").val();
	});
	/*
	jQuery('#contacto_frm_exp').submit(function (){
		jQuery('#ajax-loading-contacto').show();
		jQuery('#pContacto').hide();
		var inicio = jQuery("#inicio").val();
		var fin = jQuery("#fin").val();
		var data = {
			action: 'contacto_exporta',
			inicio: inicio,
			fin: fin
		};
		jQuery.post(ajaxurl, data, function(response) {
			jQuery('#pContacto').show();
			jQuery('#descargaContacto').attr("href", response);
			jQuery('#ajax-loading-contacto').hide();
		});
		return false;
	});
	*/
});