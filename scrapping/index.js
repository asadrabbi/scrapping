const express = require('express');
const puppeteer = require('puppeteer');

const app = express();
const port = 8686;

app.get('/scrape', async (req, res) => {
  // Perform web scraping using Puppeteer
  const browser = await puppeteer.launch({ headless: true ,  args: ['--no-sandbox']});
  const page = await browser.newPage();
  let title;
  try {
    await page.goto('https://www.proxysite.com/');
    await page.waitForTimeout(7000);
    console.log(await page.title());
    const inputSelector = '.url-form input';
    const searchValue = 'https://rekvizitai.vz.lt/en/company-search/';

    await page.type(inputSelector, searchValue);

    const buttonSelector = '.url-form button';
    await page.click(buttonSelector);

    await page.waitForTimeout(5000);

    /********************************************/
    await page.waitForSelector( '#cookiescript_close')
    await page.click( '#cookiescript_close');

    await page.waitForTimeout(7000);
    const searchInputSelector = '#code';
    const searchCode = '304565690';
    await page.type(searchInputSelector, searchCode);

    await page.waitForTimeout(7000);
    // Select the search button by its ID and click it
    const submitButtonSelector = '#ok'; // Replace with the actual selector
    await page.click(submitButtonSelector);

    await page.waitForSelector('#cookiescript_close');
    await page.click('#cookiescript_close');

    const titleSelector = '.company-info .company-title';
    title = await page.$eval(titleSelector, (element) => element.textContent);
    await page.waitForTimeout(5000);
    // await page.waitForSelector('#cookiescript_close');
    // await page.click('#cookiescript_close');
    // await page.waitForSelector('.details-block__1');
    //
    // // Extract the information from the table
    // const registrationCode = await page.$eval('.details-block__1 .name:contains("Registration code") + .value', (element) => element.textContent.trim());
    // const vat = await page.$eval('.details-block__1 .name:contains("VAT") + .value', (element) => element.textContent.trim());
    // //const address = await page.$eval('.details-block__1 .name:contains("Address") + .value', (element) => element.textContent.trim());
    // //const phone = await page.$eval('.details-block__1 .name:contains("Phone") + .value', (element) => element.textContent.trim());
    //
    // console.log('Company reg:', registrationCode);
    // console.log('Company vat:', vat);


    // Check if a title was found
    if (title) {
      console.log('Company Title:', title.trim());
    } else {
      console.log('No title found.');
    }

  } catch (error) {
    if (error instanceof puppeteer.errors.TimeoutError) {
      console.error('Navigation timed out:', error.message);
    } else {
      console.error('An error occurred:', error.message);
    }
  }
  await browser.close();

  // Send the scraped data as a JSON response
  res.json({ title });
});

// Start the Express.js server
app.listen(port, () => {
  console.log(`API server is running on port ${port}`);
});
