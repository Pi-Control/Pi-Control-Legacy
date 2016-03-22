var shutdown = false;

function ping()
{
	var jsonData = $.ajax(
	{
		url: 'api/v1/ping.php',
		dataType:"json",
		async: true,
		timeout: 5000
	}).done(function(data)
	{
		if (shutdown == true)
		{
			jQuery('.inner strong').text('Online - Du wirst sofort weitergeleitet');
			setTimeout('window.document.location.href = \'?s=overview\'', 2000);
		}
		
		jQuery('.inner strong').addClass('green').removeClass('red');
		setTimeout('ping()', 5000);
	}).error(function(data)
	{
		jQuery('.inner strong').text('Offline');
		shutdown = true;
		
		jQuery('.inner strong').addClass('red').removeClass('green');
		setTimeout('ping()', 5000);
	});
}

$(document).on('ready', function(e)
{
	ping();
});