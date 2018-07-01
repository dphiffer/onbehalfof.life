var src_list = [];
var canvas, ctx, species;
var ignore_hashchange = false;

$('select[name="species_id"]').change(function() {
	var hash = '#species' + $('select[name="species_id"]').val();
	if (location.hash != hash) {
		window.location = hash;
	}
});

function setup_select(rsp) {
	if ($('select[name="species_id"]').length == 0) {
		return;
	}
	var select = $('select[name="species_id"]')[0];
	var selected = 0;
	select.innerHTML = '';
	for (var i = 0; i < rsp.list.length; i++) {
		var name = rsp.list[i].common + ' (' + rsp.list[i].latin + ')';
		var value = rsp.list[i].id;
		if (value == rsp.species.id) {
			selected = i;
		}
		var option = document.createElement('option');
		option.value = value;
		option.innerHTML = name;
		select.appendChild(option);
	}
	select.selectedIndex = selected;
}

function choose(reload) {
	var url = '/species.php';
	if (reload) {
		url += '?reload=1';
	}
	var species_list = $('body').data('species-list');
	if (species_list) {
		var sep = url.indexOf('?') == -1 ? '?' : '&';
		url += sep + 'species_list=' + species_list;
	}
	$.get(url, function(rsp) {
		species = rsp.species;
		ignore_hashchange = true;
		window.location = '#species' + species.id;
		setup_select(rsp);
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
		var attr = '';
		if ('photo_url' in species && 'photo_attr' in species) {
			attr = '<div class="attr">PHOTO: <a href="' + species.photo_url + '" target="_blank">' + species.photo_attr + '</a></div>';
		} else if ('specialty' in species) {
			attr = '<div class="attr">' + species.specialty + '</div>';
		}
		$('#share-img').html('<figure><img><figcaption><a href="https://www.inaturalist.org/taxa/' + species.id + '" target="_blank"><i>' + species.latin + '</i> on iNaturalist.org</a>.' + attr + '</figcaption></figure>');
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
	var url = '/species.php?id=' + id;
	var species_list = $('body').data('species-list');
	if (species_list) {
		url += '&species_list=' + species_list;
	}
	$.get(url, function(rsp) {
		species = rsp.species;
		setup_select(rsp);
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

	$('#more-comments').click(function(e) {
		e.preventDefault();
		$('#public-comments').addClass('show-more');
		$('#more-comments').css('display', 'none');
	});

	$('#comment').submit(function(e) {
		e.preventDefault();
		var values = $('#comment').serialize();
		$('#comment .button').val('Submitting comment...');
		$('#comment .button').addClass('disabled');
		$('#comment .button').attr('disabled', 'disabled');
		$('#comment input[type="text"], #comment input[type="email"], #comment select, #comment textarea').attr('disabled', 'disabled');
		$.post('/comments.php', values, function(rsp) {
			if (rsp.ok) {
				$('#comment .response').html('Thank you for your public comment. We will email you when it gets submitted to the EPA.');
				$('#comment .button').val('Processing comment...');
				setTimeout(function() {
					$('#comment input[type="text"], #comment input[type="email"], #comment select, #comment textarea').attr('disabled', null);
					$('#comment .button').removeClass('disabled');
					$('#comment .button').attr('disabled', null);
					$('#comment .button').val('Submit public comment');
					$('#comment input[type="text"], #comment input[type="email"], #comment textarea').val('');
				}, 5000);
				setTimeout(function() {
					$('#comment .response').html('');
				}, 10000);
			} else {
				if (rsp.error) {
					$('#comment .response').html(rsp.error);
				} else {
					$('#comment .response').html('Uh oh, something went wrong with your submission.');
				}
				$('#comment .button').val('⚠️ Submission error');
				setTimeout(function() {
					$('#comment input[type="text"], #comment input[type="email"], #comment select, #comment textarea').attr('disabled', null);
					$('#comment .button').removeClass('disabled');
					$('#comment .button').attr('disabled', null);
					$('#comment .button').val('Submit public comment');
				}, 3000);
			}
		});
	});
});
