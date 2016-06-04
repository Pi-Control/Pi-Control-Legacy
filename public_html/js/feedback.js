var ajaxFeedback;
var formFeedback;

function showFeedbackError()
{
	jQuery('.feedback div.box .inner:eq(0)').html('Leider ist ein unerwarteter Fehler aufgetreten. Bitte schließe das Feedback-Fenster und versuche es erneut. Andernfalls, schreibe mir unter <a href="https://willy-tech.de/kontakt/" target="_blank">Kontakt</a>.');
	jQuery('.feedback div.box img').remove();
	jQuery('.feedback div.box .inner:eq(1)').html('<strong class="red">:(</strong>');
	
	return false;
}

jQuery(document).on('mousedown', 'a[href="https://willy-tech.de/kontakt/"]', function(e)
{
	if (e.which == 3)
		return false;
	
	window.scrollTo(0, 0);
	
	if (jQuery('.feedback').length == 0)
	{
		jQuery('body').append('<div class="feedback"><a href="#close">Schließen</a><span class="feedback-inner"><div class="box"><div class="inner-header"><span>Feedback</span></div><div class="inner text-justify">F&uuml;r das Feedback m&uuml;ssen noch einige Daten gesammelt werden.</div><div class="inner"><img src="public_html/img/loader.svg" /></div><div class="inner-end"><a href="#close" class="button">Schließen</a></div></div></span></div>');
		
		ajaxFeedback = jQuery.ajax({
			url: 'api/v1/feedback.php',
			method: 'POST',
			data: { url: window.location.href },
			dataType: 'text',
			async: true
		}).done(function(data)
		{
			if (data == '')
				return showFeedbackError();
			
			formFeedback = jQuery('<form action="https://pi-control.de/?service=feedback" method="post" target="_blank"><input type="hidden" name="data" value="'+data+'" /><input type="hidden" name="error-handler" value="'+errorHandler+'" /></form>');
			
			jQuery('.feedback div.box .inner:eq(0)').html('Diagnosedaten wurden gesammelt. Beim Klick auf den folgenden Button wird ein neues Fenster ge&ouml;ffnet.');
			jQuery('.feedback div.box img').remove();
			jQuery('.feedback div.box .inner:eq(1)').addClass('text-align-center').html('<a href="#open" class="button">Feedback &ouml;ffnen</a>');
		}).fail(function()
		{
			return showFeedbackError();
		});
	}
	
	jQuery('.feedback').fadeIn('fast');

	return false;
});

jQuery(document).on('click', 'a[href="#close"], .feedback', function(e)
{
	if (jQuery(e.target).has('.box').length || jQuery(e.target).is('a[href="#close"]'))
	{
		ajaxFeedback.abort();
		jQuery('.feedback').fadeOut('fast');
    }
	
	return false;
});

jQuery(document).on('click', 'a[href="#open"]', function(e)
{
	formFeedback.appendTo('body').submit();
	
	return false;
});