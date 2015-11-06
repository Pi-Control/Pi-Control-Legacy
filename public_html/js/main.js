$(document).on('click', '.error, .success, .info', function(e) {
	$(this).css("position", "relative").css("z-index", "-500").animate({marginTop: -$(this).height()-20, opacity: 0}, 300);
});