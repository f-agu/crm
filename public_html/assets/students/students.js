$(document).ready(function () {
	$("#student_form").on("submit", function(e) {
		var postData = $(this).serializeObject();
		var formURL = $(this).attr("action");
		$.ajax({
			url: formURL,
			type: "POST",
			data: JSON.stringify(postData),
			dataType : 'json',
			contentType: 'application/json',
			success: function(data2, textStatus, jqXHR) {
				// TODO
				console.log('OK with ', postData);
				// $('#student_dialog .modal-header .modal-title').html("Result");
				// $('#student_dialog .modal-body').html(data);
				// $("#submitForm").remove();
			},
			error: function(jqXHR, status, error) {
				var errorJson = jqXHR.responseJSON;
				handleError(e, errorJson);
				//console.log('ERRRRROR', status + ": " + error, jqXHR.responseJSON);
			}
		});
		e.preventDefault();
	});
	$("#submitCreateAndAddNew").on('click', function() {
		$("#student_form").submit();
	});
});

function handleError(formElement, errorJson) {
	var form = document.getElementById("student_form");
	var data = errorJson.extra;
	$("#student_form .form-control").removeClass("is-valid");
	$("#student_form .form-control").removeClass("is-invalid");
	Object.keys(data).forEach(function(k) {
		var input = $("#student_form [name='" + k + "']");
		if(input) {
			input.addClass("is-invalid");
			console.log(k + ' - ' + data[k], input);
			var textElement = input.parent().find(".invalid-feedback");
			if(textElement) {
				textElement.text(data[k]);
				input.prop('invalid', true);
				valid = false;
			}
		}
	});
	
	console.log(form, form.checkValidity());
	event.preventDefault();
	event.stopPropagation();
}

(function() {
	'use strict';
	window.addEventListener('load', function() {
		var forms = document.getElementsByClassName('needs-validation');
		var validation = Array.prototype.filter.call(forms, function(form) {
			
			form.addEventListener('submit', function(event) {
				if (form.checkValidity() === false) {
					event.preventDefault();
					event.stopPropagation();
				}
				form.classList.add('was-validated');
			}, false);
		});
	}, false);
})();
