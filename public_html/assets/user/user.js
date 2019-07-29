$(document).ready(function () {
	$("#btn-modify").on('click', function(e) {
		toggleToModify(true);
	});
	$("#btn-cancel").on('click', function(e) {
		toggleToModify(false);
	});
});

function toggleToModify(display) {
	$("#btn-modify").toggle(!display);
	$("#btn-cancel").toggle(display);
	$("#submitUpdate").toggle(display);
}