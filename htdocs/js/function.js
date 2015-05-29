$(document).ready(function () {
	$(".fancybox").fancybox({
		prevEffect: 'none',
		nextEffect: 'none',
		width     : 640,
		height    : 480,
		padding   : 5,
		margin    : 10,
		closeBtn  : false,
		helpers   : {
			title: {
				type: 'inside'
			}
		}
	});

	$("a[data-toggle='collapse']").click(function () {
		var bodyId = $(this).attr('aria-controls');
		var bodyObj = $('#' + bodyId + ' .panel-body');
		if (bodyObj.html() == '')
		{
			// Show Preloader
			bodyObj.html('<img src="img/103.GIF" alt="Loading..."/>');

			// Load Cam Pics from day
			$.ajax({
				url : "camFromDay.php",
				data: {date: $(this).attr('cam-date')}
			}).done(function (data) {
				bodyObj.html(data);
			}).fail(function (data) {
				console.log(data);
			});
		}
	});
});
