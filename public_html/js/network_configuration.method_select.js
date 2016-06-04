jQuery(document).on('change', 'select[name=method]', function()
{
	if (jQuery('select[name=method] option:selected').index() == 1)
		jQuery('.hidden-method').show('fast');
	else
		jQuery('.hidden-method').hide('fast');
});