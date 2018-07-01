(function($) {

	$(document).ready(function() {
		if ($('#public-comments').length == 0) {
			return;
		}
		var url = $('#public-comments').data('url');
		$.get(url, function(rsp) {
			if (! 'ok' in rsp || ! rsp.ok) {
				console.error('Could not load comments');
				return;
			}
			if (! 'comments' in rsp) {
				console.error('No comments returned from deliverator');
				return;
			}
			var html = '', id;
			var comments = rsp.comments.reverse();
			for (var i = 0; i < comments.length; i++) {
				id = comments[i];
				html += '<img src="' + url + '/' + id + '.jpg" alt="Public comment submission">';
			}
			$('#public-comments').html(html);
		});
	});

})(jQuery);
