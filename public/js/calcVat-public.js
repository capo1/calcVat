(function ($) {
	'use strict';
	function removeEmpty(obj) {
		return Object.entries(obj)
			.filter(([_, v]) => v != null)
			.reduce((acc, [k, v]) => ({ ...acc, [k]: v }), {});
	}

	$(document).ready(function () {

		const vat_form = $('#vat_form'),
			result_div = vat_form.find('#vat_result');

		if (result_div.length > 0) {

			calcVat_settings.localStringCurrency.currency = cv_currency;

			vat_form.on('submit', function (e) {
				e.preventDefault();
				result_div.addClass('active')
				
				let form = $(this)[0],
						acc = removeEmpty(Object.values(form).reduce((obj, field) => { obj[field.name] = field.value; return obj }, {}));
				acc['action'] = calcVat_settings.action;

				$.ajax({
					url: calcVat_settings.ajaxurl,
					method: "post",
					data: acc,
					dataType: "json",
					success: function (response) {
						if(response.success){
							result_div.addClass('active').html(response.success)
						}
					},
					error: function(){
						alert(calcVat_settings.error)
					}
				});
				return false;

			});
		}

	});

})(jQuery);
