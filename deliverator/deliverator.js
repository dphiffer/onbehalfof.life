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

function timeout(ms) {
	return new Promise(resolve => setTimeout(resolve, ms));
}

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
