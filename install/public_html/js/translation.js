var jsTranslations = {};

function _t(format)
{
	if (format in jsTranslations)
		format = jsTranslations[format];
	
	for (var i = 1; i < arguments.length; i++)
		format = format.replace(/%s/, arguments[i]);
	
	return format;
}