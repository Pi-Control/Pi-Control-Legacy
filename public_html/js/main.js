$(document).on('click', '.error > div > span, .success > div > span, .info > div > span', function(e) {
	$(this).parent().parent().css("position", "relative").css("z-index", "-500").animate({marginTop: -$(this).parent().parent().height()-20, opacity: 0}, 300, function(e)
	{
		$(this).hide();
	});
});