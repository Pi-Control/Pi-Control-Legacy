$(document).on('change', 'select[name=method]', function()
{
	if ($('select[name=method] option:selected').index() == 1)
		$('.hidden-method').show('fast');
	else
		$('.hidden-method').hide('fast');
});