define(['jquery', 'TYPO3/CMS/Backend/jquery.clearable'], function ($) {
	var BeuserFastswitch = {};

	BeuserFastswitch.init = function () {
		$('#beuser-fastswitch-search-form').submit(function(e){
			e.preventDefault();
		});
		$('#beuser-fastswitch-search-mask')
			.clearable()
			.on('keyup', BeuserFastswitch.delay(function (e) {
				var search = $('#beuser-fastswitch-search-mask').val(),
					ajaxUrl = TYPO3.settings.ajaxUrls['beuser_fastswitch_backend_userlookup'],
					resultTag = $('#beuser-fastswitch-ajax-result');

				if (search.length >= 1 && e.keyCode !== 13) {
					var ajaxUrlSearch = ajaxUrl + '&search=' + search;

					resultTag.html('');

					$.get(ajaxUrlSearch, {
						format: 'html'
					}).done(function (data) {
						resultTag.html(data);
					});
				}
				if (search.length === 0) {
					$.get(ajaxUrl, {
						format: 'html'
					}).done(function (data) {
						resultTag.html(data);
					});
				}
			}, 250));
	};

	BeuserFastswitch.delay = function (callback, ms) {
		var timer = 0;
		return function() {
			var context = this, args = arguments;
			clearTimeout(timer);
			timer = setTimeout(function () {
				callback.apply(context, args);
			}, ms || 0);
		};
	};

	BeuserFastswitch.init();

	return BeuserFastswitch;
});
