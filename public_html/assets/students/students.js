$(document).ready(function () {
	$("#student_form").on("submit", function(e) {
		var postData = $(this).serializeArray();
		var formURL = $(this).attr("action");
		$.ajax({
			url: formURL,
			type: "POST",
			data: postData,
			success: function(data, textStatus, jqXHR) {
				console.log('OK');
				// $('#student_dialog .modal-header .modal-title').html("Result");
				// $('#student_dialog .modal-body').html(data);
				// $("#submitForm").remove();
			},
			error: function(jqXHR, status, error) {
				console.log(status + ": " + error);
			}
		});
		e.preventDefault();
	});
	$("#submitCreateAndAddNew").on('click', function() {
		$("#student_form").submit();
	});
});

