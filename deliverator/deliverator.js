const puppeteer = require('puppeteer');

function timeout(ms) {
	return new Promise(resolve => setTimeout(resolve, ms));
}

(async () => {
	const url = 'https://www.regulations.gov/comment?D=HHS-OS-2018-0008-0001';
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

	await textarea.type('hello this is a test, just testing hello there.', { delay: 30 });


})();
