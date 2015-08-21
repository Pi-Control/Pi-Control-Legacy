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
	$('.refresh-bar').animate({width: '100%'}, 30000, 'linear', function(e)
    {
		var runtime	=		$('.flex-container > div:eq(2)');
		var cpuFreq =		$('.flex-container > div:eq(3)');
		var cpuLoad =		$('.flex-container > div:eq(4)');
		var cpuTemp =		$('.flex-container > div:eq(5)');
		var ram =			$('.flex-container > div:eq(6)');
		var memoryUsed =	$('.flex-container > div:eq(7)');
		var memoryFree =	$('.flex-container > div:eq(8)');
		var memoryTotal =	$('.flex-container > div:eq(9)');
        
        $('a[href=#refresh] img').addClass('rotate-icon');
        
		$(this).animate({width: '88.8%'}, 300, 'linear');
        $.get(overview_path+'/overview_status_reload.php', {data: 'runtime'}, function(data)
		{
            if (runtime.html() != data)
            {
		        overviewStatusRefreshEffect($('.flex-container > div:eq(2)'));
            }
			
			$(this).animate({width: '77.7%'}, 300, 'linear');
            $.get(overview_path+'/overview_status_reload.php', {data: 'cpuFreq'}, function(data)
    		{
                if (runtime.html() != data)
                {
    		        overviewStatusRefreshEffect($('.flex-container > div:eq(3)'));
                }
				
				$(this).animate({width: '66.6%'}, 300, 'linear');
                $.get(overview_path+'/overview_status_reload.php', {data: 'cpuLoad'}, function(data)
        		{
                    if (runtime.html() != data)
                    {
        		        overviewStatusRefreshEffect($('.flex-container > div:eq(4)'));
                    }
					
					$(this).animate({width: '55.5%'}, 300, 'linear');
                    $.get(overview_path+'/overview_status_reload.php', {data: 'cpuTemp'}, function(data)
            		{
                        if (runtime.html() != data)
                        {
            		        overviewStatusRefreshEffect($('.flex-container > div:eq(5)'));
                        }
						
						$(this).animate({width: '44.4%'}, 300, 'linear');
                        $.get(overview_path+'/overview_status_reload.php', {data: 'ram'}, function(data)
                		{
                            if (runtime.html() != data)
                            {
                		        overviewStatusRefreshEffect($('.flex-container > div:eq(6)'));
                            }
							
							$(this).animate({width: '33.3%'}, 300, 'linear');
                            $.get(overview_path+'/overview_status_reload.php', {data: 'memoryUsed'}, function(data)
                    		{
                                if (runtime.html() != data)
                                {
                    		        overviewStatusRefreshEffect($('.flex-container > div:eq(7)'));
                                }
								
								$(this).animate({width: '22.2%'}, 300, 'linear');
                                $.get(overview_path+'/overview_status_reload.php', {data: 'memoryFree'}, function(data)
                        		{
                                    if (runtime.html() != data)
                                    {
                        		        overviewStatusRefreshEffect($('.flex-container > div:eq(8)'));
                                    }
									
									$(this).animate({width: '11.1%'}, 300, 'linear');
                                    $.get(overview_path+'/overview_status_reload.php', {data: 'memoryTotal'}, function(data)
                            		{
                                        if (runtime.html() != data)
                                        {
                            		        overviewStatusRefreshEffect($('.flex-container > div:eq(9)'));
                                        }
										
										$(this).animate({width: '0%'}, 300, 'linear', function(e) {
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