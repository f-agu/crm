$(document).ready(function () {
	$("#student_form").on("submit", function(e) {
		var postData = $(this).serializeObject();
		var formURL = $(this).attr("action");
		console.log(formURL, postData);
		console.log(JSON.stringify(postData));
		//console.log(e);
		//console.log($("#student_form").serialize())
		//console.log(JSON.stringify( $(this).serializeArray() ));
		$.ajax({
			url: formURL,
			type: "POST",
			data: JSON.stringify(postData),
			dataType : 'json',
			contentType: 'application/json',
			success: function(data2, textStatus, jqXHR) {
				console.log('OK with ', postData);
				// $('#student_dialog .modal-header .modal-title').html("Result");
				// $('#student_dialog .modal-body').html(data);
				// $("#submitForm").remove();
			},
			error: function(jqXHR, status, error) {
				console.log('ERRRRROR', status + ": " + error, jqXHR);
			}
		});
		e.preventDefault();
	});
	$("#submitCreateAndAddNew").on('click', function() {
		$("#student_form").submit();
	});
});

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