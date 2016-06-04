var is_loding = false;

function overviewStatusRefreshEffect(element)
{
	element.css({'transition': 'background-color 0.5s', 'background-color': 'rgba(243, 255, 164, 1)'});
	setTimeout(function(){
		element.css({'background-color': 'transparent'});
	}, 800);
}

function showError()
{
	jQuery('.error-msg-refresh-bar').remove();
	jQuery('.flex-box-refresh div:eq(0)').after('<div class="red error-msg-refresh-bar" style="vertical-align: bottom; font-weight: bold;">Fehler!</div>');
	jQuery('.refresh-bar').stop(false, true).css('width', 0);
	jQuery('a[href=#refresh] img').removeClass('rotate-icon');
	
	setTimeout('overviewStatusRefresh()', 3000);
}

function overviewStatusRefresh()
{
	jQuery('.error-msg-refresh-bar').remove();
	jQuery('.refresh-bar').animate({width: '100%'}, reload_timeout, 'linear', function(e)
	{
		var this_ =			jQuery(this);
		var runtime =		jQuery('.flex-container > div:eq(2)');
		var cpuClock =		jQuery('.flex-container > div:eq(3)');
		var cpuLoad =		jQuery('.flex-container > div:eq(4)');
		var cpuTemp =		jQuery('.flex-container > div:eq(5)');
		var ramPercentage =	jQuery('.flex-container > div:eq(6)');
		var memoryUsed =	jQuery('.flex-container > div:eq(7)');
		var memoryFree =	jQuery('.flex-container > div:eq(8)');
		var memoryTotal =	jQuery('.flex-container > div:eq(9)');
		
		jQuery('a[href=#refresh] img').addClass('rotate-icon');
		
		this_.animate({width: '88.8%'}, 300, 'linear');
		jQuery.post('api/v1/overview.php', { data: 'runtime' }, function(data)
		{
			if (runtime.find('span').html() != data.data.runtime)
			{
				overviewStatusRefreshEffect(runtime);
				runtime.find('span').html(data.data.runtime);
			}
			
			this_.animate({width: '77.7%'}, 300, 'linear');
			jQuery.post('api/v1/overview.php', { data: 'cpuClock' }, function(data)
			{
				if (cpuClock.find('span').html() != data.data.cpuClock+' MHz')
				{
					overviewStatusRefreshEffect(cpuClock);
					cpuClock.find('span').html(data.data.cpuClock+' MHz');
				}
				
				this_.animate({width: '66.6%'}, 300, 'linear');
				jQuery.post('api/v1/overview.php', { data: 'cpuLoad' }, function(data)
				{
					if (cpuLoad.find('.progressbar div').html() != data.data.cpuLoad+'%')
					{
						overviewStatusRefreshEffect(cpuLoad);
						cpuLoad.find('.progressbar div').html(data.data.cpuLoad+'%').css('width', data.data.cpuLoad+'%');
					}
					
					this_.animate({width: '55.5%'}, 300, 'linear');
					jQuery.post('api/v1/overview.php', { data: 'cpuTemp' }, function(data)
					{
						if (cpuTemp.find('span').html() != data.data.cpuTemp+' °C')
						{
							overviewStatusRefreshEffect(cpuTemp);
							cpuTemp.find('span').html(data.data.cpuTemp+' &deg;C');
						}
						
						this_.animate({width: '44.4%'}, 300, 'linear');
						jQuery.post('api/v1/overview.php', { data: 'ramPercentage' }, function(data)
						{
							if (ramPercentage.find('.progressbar div').html() != data.data.ramPercentage+'%')
							{
								overviewStatusRefreshEffect(ramPercentage);
								ramPercentage.find('.progressbar div').html(data.data.ramPercentage+'%').css('width', data.data.ramPercentage+'%');
							}
							
							this_.animate({width: '33.3%'}, 300, 'linear');
							jQuery.post('api/v1/overview.php', { data: 'memoryUsed' }, function(data)
							{
								if (memoryUsed.find('span').html() != data.data.memoryUsed)
								{
									overviewStatusRefreshEffect(memoryUsed);
									memoryUsed.find('span').html(data.data.memoryUsed);
								}
								
								this_.animate({width: '22.2%'}, 300, 'linear');
								jQuery.post('api/v1/overview.php', { data: 'memoryFree' }, function(data)
								{
									if (memoryFree.find('span').html() != data.data.memoryFree)
									{
										overviewStatusRefreshEffect(memoryFree);
										memoryFree.find('span').html(data.data.memoryFree);
									}
									
									this_.animate({width: '11.1%'}, 300, 'linear');
									jQuery.post('api/v1/overview.php', { data: 'memoryTotal' }, function(data)
									{
										if (memoryTotal.find('span').html() != data.data.memoryTotal)
										{
											overviewStatusRefreshEffect(memoryTotal);
											memoryTotal.find('span').html(data.data.memoryTotal);
										}
										
										this_.animate({width: '0%'}, 300, 'linear', function(e) {
											is_loding = false;
											jQuery('a[href=#refresh] img').removeClass('rotate-icon');
										});
										
										overviewStatusRefresh();
									}).fail(function(e) { showError(); });
								}).fail(function(e) { showError(); });
							}).fail(function(e) { showError(); });
						}).fail(function(e) { showError(); });
					}).fail(function(e) { showError(); });
				}).fail(function(e) { showError(); });
			}).fail(function(e) { showError(); });
		}).fail(function(e) { showError(); });
	});
}

jQuery(document).on('click', 'a[href=#refresh]', function(e)
{
	if (is_loding == false)
		jQuery('.refresh-bar').stop(false, true);
	
	return false;
});

jQuery(document).ready(function(e)
{
	overviewStatusRefresh();
});