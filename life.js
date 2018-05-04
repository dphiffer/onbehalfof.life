var src_list = [];
var canvas, ctx, species;
var ignore_hashchange = false;

function choose(reload) {
	var url = '/species.php';
	if (reload) {
		url += '?reload=1';
	}
	$.get(url, function(rsp) {
		species = rsp.species;
		ignore_hashchange = true;
		window.location = '#species' + species.id;
		init();
	});
}

function imgload() {
	var src = src_list.shift();
	console.log('imgload: ' + src);
	var img = document.createElement("img");
	img.onload = function() {
		console.log('onload');
		imghandle(img);
	};
	img.setAttribute('crossOrigin', 'anonymous');
	img.src = src;
	$('#custom').append(img);
}

function imghandle(img) {
	console.log(img);
	var w = 640;
	var h = Math.ceil(640 * $(img).height() / $(img).width());
	if (h < 640) {
		var offset = Math.floor((640 - h) / 2);
		ctx.drawImage(img, 0, offset - h, w, h);
		ctx.drawImage(img, 0, offset, w, h);
		ctx.drawImage(img, 0, 639 - offset, w, h);
	} else {
		ctx.drawImage(img, 0, 0, w, h);
	}
	if (src_list.length > 0) {
		imgload();
	} else {
		var data_uri = canvas.toDataURL();
		$('#share-img').html('<figure><img><figcaption><a href="https://www.inaturalist.org/taxa/' + species.id + '" target="_blank"><i>' + species.latin + '</i> on iNaturalist.org</a>.<div class="attr">PHOTO: <a href="' + species.photo_url + '" target="_blank">' + species.photo_attr + '</a></div></figcaption></figure>');
		$('#share-img img')[0].src = data_uri;
		if ($('#share-img2').length > 0) {
			$('#share-img2')[0].src = data_uri;
		} else {
			$('<img id="share-img2">').insertAfter('#share h2');
			$('#share-img2')[0].src = data_uri;
		}
		$('#reload').removeClass('loading');
	}
}

function load(id) {
	console.log('load ' + id);
	$.get('/species.php?id=' + id, function(rsp) {
		species = rsp.species;
		init();
	});
}

function init() {
	var url = 'https://www.inaturalist.org/taxa/' + species.id;
	var link = '<a href="' + url + '" target="_blank">' + species.common.toUpperCase() + ' (<i>' + species.latin + '</i>)</a>';
	$('#life').html(link);
	$('#another').html(link);
	src_list.push('/species/' + species.id + '.jpg');
	src_list.push('template-inverted.png');
	$('#custom').html('<canvas width="640" height="640"></canvas>');
	canvas = $('#custom canvas')[0];
	ctx = canvas.getContext('2d');
	imgload();
}

$(document).ready(function() {
	var hash = location.hash.match(/^#species(\d+)$/);
	if (hash) {
		var id = hash[1];
		load(id);
	} else {
		choose();
	}

	window.onhashchange = function() {
		if (ignore_hashchange) {
			ignore_hashchange = false;
			return;
		}
		var hash = location.hash.match(/^#species(\d+)$/);
		if (hash) {
			var id = hash[1];
			console.log('load ' + id);
			load(id);
		}
	};

	$('#reload').click(function(e) {
		e.preventDefault();
		if ($('#reload').hasClass('loading')) {
			return;
		}
		$('#reload').addClass('loading');
		console.log(e.shiftKey);
		choose(e.shiftKey);
	});

	$.get('/species.html', function(rsp) {
		$('#all-species').html(rsp);
	});

	$('#more-comments').click(function(e) {
		e.preventDefault();
		$('#public-comments').addClass('show-more');
		$('#more-comments').css('display', 'none');
	});
});
