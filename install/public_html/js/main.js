$(document).on('click', '.error > div span.cancel, .success > div span.cancel, .info > div span.cancel', function(e) {
	$(this).parents('.box').css("position", "relative").css("z-index", "-500").animate({marginTop: -$(this).parents('.box').height()-20, opacity: 0}, 300, function(e)
	{
		$(this).hide();
	});
});