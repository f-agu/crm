$(document).ready(function () {
	$("#btn-modify-top-info").on('click', function(e) {
		toggleToModify(true, 'info');
	});
	$("#btn-cancel-top-info").on('click', function(e) {
		toggleToModify(false, 'info');
	});
	$("#btn-modify-bottom-info").on('click', function(e) {
		toggleToModify(true, 'info');
	});
	$("#btn-cancel-bottom-info").on('click', function(e) {
		toggleToModify(false, 'info');
	});
});

function toggleToModify(enableModify, tab) {
	$("#btn-modify-top-" + tab).toggle(! enableModify);
	$("#btn-modify-bottom-" + tab).toggle(! enableModify);
	$("#btn-cancel-top-" + tab).toggle(enableModify);
	$("#btn-cancel-bottom-" + tab).toggle(enableModify);
	$("#submitUpdate-top-" + tab).toggle(enableModify);
	$("#submitUpdate-bottom-" + tab).toggle(enableModify);
	
	$("#user_form-" + tab + " :input").each(function() {
		if($(this).prop('type') != 'button' ) {
			$(this).prop("readonly", ! enableModify);
			$(this).toggleClass('form-control', enableModify);
			$(this).toggleClass('form-control-plaintext', ! enableModify);
		}
	});
}