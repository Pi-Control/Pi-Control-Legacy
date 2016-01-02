var is_loding = false;

function overviewStatusRefreshEffect(element)
{
	element.css({'transition': 'background-color 0.5s', 'background-color': 'rgba(243, 255, 164, 1)'});
	setTimeout(function(){
		element.css({'background-color': 'transparent'});
	}, 800);
}

function overviewStatusRefresh()
{
	$('.refresh-bar').animate({width: '100%'}, reload_timeout, 'linear', function(e)
    {
		var this_ =				$(this);
		var run_time =			$('.flex-container > div:eq(2)');
		var cpu_clock =			$('.flex-container > div:eq(3)');
		var cpu_load =			$('.flex-container > div:eq(4)');
		var cpu_temp =			$('.flex-container > div:eq(5)');
		var ram_percentage =	$('.flex-container > div:eq(6)');
		var memory_used =		$('.flex-container > div:eq(7)');
		var memory_free =		$('.flex-container > div:eq(8)');
		var memory_total =		$('.flex-container > div:eq(9)');
        
        $('a[href=#refresh] img').addClass('rotate-icon');
        
		this_.animate({width: '88.8%'}, 300, 'linear');
        $.get(overview_path, {data: 'run_time'}, function(data)
		{
            if (run_time.find('span').html() != data)
            {
		        overviewStatusRefreshEffect(run_time);
				run_time.find('span').html(data);
            }
			
			this_.animate({width: '77.7%'}, 300, 'linear');
            $.get(overview_path, {data: 'cpu_clock'}, function(data)
    		{
                if (cpu_clock.find('span').html() != data+' MHz')
                {
    		        overviewStatusRefreshEffect(cpu_clock);
					cpu_clock.find('span').html(data+' MHz');
                }
				
				this_.animate({width: '66.6%'}, 300, 'linear');
                $.get(overview_path, {data: 'cpu_load'}, function(data)
        		{
                    if (cpu_load.find('.progressbar div').html() != data+'%')
                    {
        		        overviewStatusRefreshEffect(cpu_load);
						cpu_load.find('.progressbar div').html(data+'%').css('width', data+'%');
                    }
					
					this_.animate({width: '55.5%'}, 300, 'linear');
                    $.get(overview_path, {data: 'cpu_temp'}, function(data)
            		{
                        if (cpu_temp.find('span').html() != data+' °C')
                        {
            		        overviewStatusRefreshEffect(cpu_temp);
							cpu_temp.find('span').html(data+' &deg;C');
                        }
						
						this_.animate({width: '44.4%'}, 300, 'linear');
                        $.get(overview_path, {data: 'ram_percentage'}, function(data)
                		{
                            if (ram_percentage.find('.progressbar div').html() != data+'%')
                            {
                		        overviewStatusRefreshEffect(ram_percentage);
								ram_percentage.find('.progressbar div').html(data+'%').css('width', data+'%');
                            }
							
							this_.animate({width: '33.3%'}, 300, 'linear');
                            $.get(overview_path, {data: 'memory_used'}, function(data)
                    		{
                                if (memory_used.find('span').html() != data)
                                {
                    		        overviewStatusRefreshEffect(memory_used);
									memory_used.find('span').html(data);
                                }
								
								this_.animate({width: '22.2%'}, 300, 'linear');
                                $.get(overview_path, {data: 'memory_free'}, function(data)
                        		{
                                    if (memory_free.find('span').html() != data)
                                    {
                        		        overviewStatusRefreshEffect(memory_free);
										memory_free.find('span').html(data);
                                    }
									
									this_.animate({width: '11.1%'}, 300, 'linear');
                                    $.get(overview_path, {data: 'memory_total'}, function(data)
                            		{
                                        if (memory_total.find('span').html() != data)
                                        {
                            		        overviewStatusRefreshEffect(memory_total);
											memory_total.find('span').html(data);
                                        }
										
										this_.animate({width: '0%'}, 300, 'linear', function(e) {
											is_loding = false;
											$('a[href=#refresh] img').removeClass('rotate-icon');
										});
                                        
										overviewStatusRefresh();
									});
								});
							});
						});
					});
				});
			});
		});
	});
}

$(document).on('click', 'a[href=#refresh]', function(e)
{
	if (is_loding == false)
		$('.refresh-bar').stop(false, true);
	
	return false;
});

setTimeout('overviewStatusRefresh()', 1);