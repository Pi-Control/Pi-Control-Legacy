$(document).on('click', '.error, .success, .info', function(e) {
	$(this).css("position", "relative").css("z-index", "-500").animate({marginTop: -$(this).height()-20, opacity: 0}, 300);
});

var start = Date.parse(new Date()) / 1000;
setInterval(function()
{
	newTime = time + ((Date.parse(new Date()) / 1000) - start)
	var hour = parseInt(newTime / 60 / 60 % 24);
	hour = (hour < 10) ? '0'+hour : hour;
	hour = (hour == 24) ? '00' : hour;
	var minute = parseInt(newTime / 60 % 60);
	minute = (minute < 10) ? '0'+minute : minute;
	var second = parseInt(newTime % 60);
	second = (second < 10) ? '0'+second : second;
	document.getElementById('footer-time').innerHTML = '<span>'+hour+':'+minute+':'+second+'</span>';
}, 1000);