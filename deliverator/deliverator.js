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

function get_path(id, ext) {
	if (! ext) {
		ext = '.json';
	}
	let id_padded = id;
	if (id < 100) {
		id_padded = `0${id_padded}`;
	}
	if (id < 10) {
		id_padded = `0${id_padded}`;
	}
	return `${__dirname}/data/comment_${id_padded}${ext}`;
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

	const details_path = get_path(id);
	let details = {
		id: id,
		url: req.body.url,
		comment: req.body.comment,
		name: req.body.name,
		email: req.body.email,
		status: 'pending'
	};

	if (req.body.on_behalf_of) {
		details.on_behalf_of = req.body.on_behalf_of;
	}

	fs.writeFile(details_path, JSON.stringify(details, null, '\t'), (err) => {
		if (err) {
			throw err;
		} else {
			enqueue(id);
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

var running = false;
var queue = [];
function enqueue(id) {
	if (! running) {
		running = true;
		comment(id);
	} else {
		queue.push(id);
	}
}

function timeout(ms) {
	return new Promise(resolve => setTimeout(resolve, ms));
}

async function element(context, query, test) {
	console.log('element: ' + query);
	let tries = 120;
	while (tries > 0) {
		let list = await context.$$(query);
		if (typeof test == 'function') {
			for (item of list) {
				if (await test(item)) {
					return item;
				}
			}
		} else if (list.length > 0) {
			console.log('found element');
			return list[0];
		} else {
			tries--;
			await timeout(500);
		}
	}
	return null;
}

async function comment(id) {

	console.log(`Loading comment ${id}`);

	const path = get_path(id);
	const json = fs.readFileSync(path, 'utf8');
	var details = JSON.parse(json);

	console.log(details);

	const browser = await puppeteer.launch({
		headless: false,
		devtools: true
	});
	const page = await browser.newPage();
	await page.setViewport({
		width: 1024,
		height: 768
	});
	await page.goto(details.url);

	let textarea = await element(page, 'textarea');
	if (! textarea) {
		console.log('Could not find textarea. Bailing out.');
		return false;
	}

	let value = null;
	let tries = 120;
	while (tries > 0) {
		try {
			console.log('Typing: ' + details.comment);
			await textarea.click();
			await textarea.type(details.comment);
			value = await page.evaluate(el => el.value, textarea);
			if (value == details.comment) {
				break;
			}
		} catch (err) {
			console.log('Typing failed, trying again...');
			await timeout(500);
		}
		tries--;
	}

	if (details.on_behalf_of) {

		const checkbox_3rd_party = await element(page, 'input[type="checkbox"]', async el => {
			const label = await page.evaluateHandle(el => el.nextElementSibling, el);
			const text = await page.evaluate(el => el.innerHTML, label);
			if (text == 'I am submitting on behalf of a third party') {
				return true;
			} else {
				return false;
			}
		});

		console.log('clicking');
		await checkbox_3rd_party.click();
		console.log('after click');

		const fieldset = await page.evaluateHandle(el => el.parentNode.parentNode, checkbox_3rd_party);
		const inputs = await fieldset.$$('input[type="text"]');

		if (inputs.length > 1) {
			console.log('typing name');
			await inputs[0].type(details.name);

			console.log('typing on_behalf_of');
			await inputs[1].type(details.on_behalf_of);
		}

		await textarea.click();
	}

	await page.screenshot({
		path: get_path(id, '-step1.jpg'),
		type: 'jpeg',
		quality: 80,
		fullPage: true
	});

	const button_continue = await element(page, 'button', async el => {
		const text = await page.evaluate(el => el.innerText, el);
		if (text == 'Continue') {
			return true;
		} else {
			return false;
		}
	});
	await button_continue.click();

	const checkbox_legal = await element(page, 'input[type="checkbox"]', async el => {
		const label = await page.evaluateHandle(el => el.nextElementSibling, el);
		const text = await page.evaluate(el => el.innerHTML, label);
		if (text == 'I read and understand the statement above.') {
			return true;
		} else {
			return false;
		}
	});
	await checkbox_legal.click();

	await page.screenshot({
		path: get_path(id, '-step2.jpg'),
		type: 'jpeg',
		quality: 80,
		fullPage: true
	});

	const button_submit = await element(page, 'button', async el => {
		const text = await page.evaluate(el => el.innerText, el);
		if (text == 'Submit Comment') {
			return true;
		} else {
			return false;
		}
	});
	await button_submit.click();

	await element(page, 'h1', async el => {
		const text = await page.evaluate(el => el.innerHTML, el);
		if (text == 'Your comment was submitted successfully!') {
			return true;
		} else {
			return false;
		}
	});

	await page.screenshot({
		path: get_path(id, '-step3.jpg'),
		type: 'jpeg',
		quality: 80,
		fullPage: true
	});

	const input_email = await element(page, 'input[type="text"]', async el => {
		const placeholder = await page.evaluate(el => el.getAttribute('placeholder'), el);
		if (placeholder == 'Email Address') {
			return true;
		} else {
			return false;
		}
	});

	await input_email.type(details.email);

	const button_email = await element(page, 'button', async el => {
		const text = await page.evaluate(el => el.innerText, el);
		if (text == 'Email Receipt') {
			return true;
		} else {
			return false;
		}
	});

	await button_email.click();

	//await browser.close();

	details.status = 'delivered';

	fs.writeFile(path, JSON.stringify(details, null, '\t'), (err) => {
		if (err) {
			throw err;
		}
	});

	running = false;
}
