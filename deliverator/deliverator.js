const fs = require('fs');
if (! fs.existsSync(__dirname + '/config.js')) {
	console.log('Please set up config.js');
	process.exit(1);
}

const config = require('./config.js');
const express = require('express');
const app = express();
const body_parser = require('body-parser');
const puppeteer = require('puppeteer');

var server;
if ('ssl' in config) {
	let https_options = {};
	for (let key in config.ssl) {
		https_options[key] = fs.readFileSync(`${__dirname}/${config.ssl[key]}`);
	}
	server = require('https').createServer(https_options, app);
} else {
	server = require('http').createServer(app);
}

app.use(express.static('public'));
app.use(body_parser.urlencoded({ extended: false }));

const port = config.port || 5000;
server.listen(port, () => {
	console.log(`listening on *:${port}`);
});

var comment_num = 0;
const dotfile = `${__dirname}/.comment_num`;

if (fs.existsSync(dotfile)) {
	fs.readFile(dotfile, (err, data) => {
		if (err) {
			console.log(err);
			return;
		}
		comment_num = parseInt(data);
	});
}

function timeout(ms) {
	return new Promise(resolve => setTimeout(resolve, ms));
}

app.post('/comment', (req, rsp) => {

	if (! req.body.api_key ||
	    config.api_keys.indexOf(req.body.api_key) === -1) {
		rsp.send({
			ok: false,
			error: "Invalid 'api_key' arg."
		});
		return;
	}

	if (! req.body.url ||
	    ! req.body.comment ||
	    ! req.body.name ||
	    ! req.body.email) {
		rsp.send({
			ok: false,
			error: "Required arguments: 'url', 'comment', 'name', 'email.'"
		});
		return;
	}

	comment_num++;
	let id = comment_num;
	fs.writeFile(dotfile, comment_num.toString(), (err) => {
		if (err) {
			throw err;
		}
	});

	let id_padded = id;
	if (id < 100) {
		id_padded = `0${id_padded}`;
	}
	if (id < 10) {
		id_padded = `0${id_padded}`;
	}
	const details_path = `${__dirname}/data/comment_${id_padded}.json`;
	let details = {
		id: id,
		url: req.body.url,
		comment: req.body.comment,
		name: req.body.name,
		email: req.body.email
	};

	if (req.body.on_behalf_of) {
		details.on_behalf_of = req.body.on_behalf_of;
	}

	fs.writeFile(details_path, JSON.stringify(details, null, '\t'), (err) => {
		if (err) {
			throw err;
		}
	});

	rsp.send({
		ok: true,
		id: id
	});
});

app.get('/', (req, rsp) => {
	rsp.send({
		ok: false,
		error: 'You probably want to do a POST request to /comment'
	});
});

/*async comment(url, comment) => {
	const browser = await puppeteer.launch({
		headless: false
	});
	const page = await browser.newPage();
	await page.goto(url);

	let textarea = null;
	while (! textarea) {
		await timeout(500);
		textarea = await page.$('textarea');
	}

	await textarea.type(comment);
}*/
