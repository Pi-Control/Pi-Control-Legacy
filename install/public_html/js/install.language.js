function htmlEscape(str)
{
    return String(str)
				.replace(/&auml;/g, 'ä')
	            .replace(/&ouml;/g, 'ö')
	            .replace(/&uuml;/g, 'ü')
			    .replace(/&Auml;/g, 'Ä')
			    .replace(/&Ouml;/g, 'Ö')
			    .replace(/&Uuml;/g, 'Ü')
			    .replace(/&amp;/g, '&');
}

jQuery(document).on('change', 'input[name=language]', function(e)
{
	var lang = jQuery(this).attr('value');
	
	jQuery('title').html(languageArray[3][lang] + jQuery('title').html().substr(-13));
	
	jQuery('.container-600 > .box:eq(0) > .inner-header > span').html(languageArray[0][lang]);
	jQuery('.sidebar > .box > .inner-header > span').html(languageArray[1][lang]);
	jQuery('.container-600 > .box:eq(0) > .inner').html(languageArray[2][lang]);
	jQuery('.container-600 > .box:eq(1) > .inner-header > span').html(languageArray[3][lang]);
	jQuery('.container-600 > .box:eq(1) > form > .inner-end > input').val(htmlEscape(languageArray[4][lang]));
	jQuery('.box.error > div > .inner-single').html(languageArray[5][lang]);
	
	jQuery('#footer > #footer-inner > #footer-table tr:eq(0) > th:eq(1)').html(languageArray[6][lang]);
	jQuery('#footer > #footer-inner > #footer-table tr:eq(0) > th:eq(2)').html(languageArray[7][lang]);
	jQuery('#footer > #footer-inner > #footer-table tr:eq(2) > td strong').html(languageArray[8][lang]);
	
	jQuery('#footer > #footer-inner > #footer-table tr:eq(1) > td:eq(0) a:eq(0)').html(languageArray[9][lang]);
	jQuery('#footer > #footer-inner > #footer-table tr:eq(1) > td:eq(0) a:eq(1)').html(languageArray[10][lang]);
	jQuery('#footer > #footer-inner > #footer-table tr:eq(1) > td:eq(0) a:eq(2)').html(languageArray[11][lang]);
	
	jQuery('#footer > #footer-inner > #footer-table tr:eq(1) > td:eq(1) a:eq(0)').html(languageArray[12][lang]);
	jQuery('#footer > #footer-inner > #footer-table tr:eq(1) > td:eq(1) a:eq(3)').html(languageArray[13][lang]);
	
	jQuery('#footer > #footer-inner > #footer-table tr:eq(2) > td span').html(languageArray[14][lang].replace(/%s/, jQuery('#footer > #footer-inner > #footer-table tr:eq(2) > td span a')[0].outerHTML));
	jQuery('#footer > #footer-inner > #footer-copyright').html(languageArray[15][lang].replace(/%s/, jQuery('#footer > #footer-inner > #footer-copyright span')[0].outerHTML).replace(/%s/, jQuery('#footer > #footer-inner > #footer-copyright a')[0].outerHTML) + jQuery('#footer > #footer-inner > #footer-copyright').html().substr(-10));
});