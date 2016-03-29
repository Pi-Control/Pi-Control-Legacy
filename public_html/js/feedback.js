var ajaxFeedback;
var formFeedback;

function showFeedbackError()
{
	$('.feedback div.box .inner:eq(0)').html('Leider ist ein unerwarteter Fehler aufgetreten. Bitte schließe das Feedback-Fenster und versuche es erneut. Andernfalls, schreibe mir unter <a href="https://willy-tech.de/kontakt/" target="_blank">Kontakt</a>.');
	$('.feedback div.box img').remove();
	$('.feedback div.box .inner:eq(1)').html('<strong class="red">:(</strong>');
	
	return false;
}

$(document).on('mousedown', 'a[href="https://willy-tech.de/kontakt/"]', function(e)
{
	if (e.which == 3)
		return false;
	
	window.scrollTo(0, 0);
	
	if ($('.feedback').length == 0)
	{
		$('body').append('<div class="feedback"><a href="#close">Schließen</a><span class="feedback-inner"><div class="box"><div class="inner-header"><span>Feedback</span></div><div class="inner text-justify">F&uuml;r das Feedback m&uuml;ssen noch einige Daten gesammelt werden. Ist dies erledigt, wirst du automatisch auf eine neue Seite weitergeleitet. Dort kannst du mir anschließend eine Nachricht hinterlassen.</div><div class="inner"><img src="public_html/img/loader.svg" /></div><div class="inner-end"><a href="#close" class="button">Schließen</a></div></div></span></div>');
		
		ajaxFeedback = $.ajax({
			url: 'api/v1/feedback.php',
			method: 'POST',
			data: { url: window.location.href },
			dataType: 'text',
			async: false
		}).done(function(data)
		{
			if (data == '')
				return showFeedbackError();
			
			formFeedback = $('<form action="https://pi-control.de/?service=feedback" method="post" target="_blank"><input type="hidden" name="data" value="'+data+'" /><input type="hidden" name="error-handler" value="'+errorHandler+'" /></form>');
			formFeedback.submit();
			
			$('.feedback div.box .inner:eq(0)').html('Alle erforderlichen Daten wurden gesammelt. Es wurde ein neuer Tab mit einem Formular ge&ouml;ffnet. Wurde der Tab nicht ge&ouml;ffnet, klicke auf den folgenden Button.');
			$('.feedback div.box img').remove();
			$('.feedback div.box .inner:eq(1)').addClass('text-align-center').html('<a href="#open" class="button">Feedback &ouml;ffnen</a>');
		}).fail(function()
		{
			return showFeedbackError();
		});
	}
	
	$('.feedback').fadeIn('fast');

	return false;
});

$(document).on('click', 'a[href="#close"], .feedback', function(e)
{
	if ($(event.target).has('.box').length || $(event.target).is('a[href="#close"]'))
	{
		ajaxFeedback.abort();
		$('.feedback').fadeOut('fast');
    }
});

$(document).on('click', 'a[href="#open"]', function(e)
{
	formFeedback.submit();
});