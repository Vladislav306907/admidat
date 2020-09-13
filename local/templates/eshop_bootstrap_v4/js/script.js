function generateLink(){
	$.post("/ajax/generate-link.php", {
		UF_LINK: $('#form-generate input[name="UF_LINK"]').val(),
		UF_LINK_LIFETIME: $('#form-generate input[name="UF_LINK_LIFETIME"]').val(),
		REQ_LIFETIME : $('#form-generate input[name="REQ_LIFETIME"]').prop('checked'),
		UF_CONVERSION_STATISTICS: $('#form-generate input[name="UF_CONVERSION_STATISTICS"]').prop('checked')
	}, function(result){
		$('#form-generate input[name="UF_SHORT_LINK"]').val(result.UF_SHORT_LINK);
		$('#form-generate input[name="UF_CONVERSION_STATISTICS_LINK"]').val(result.UF_CONVERSION_STATISTICS);
		console.log(result);
	}, 'json');
} 