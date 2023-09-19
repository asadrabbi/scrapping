const puppeteer = require('puppeteer');

(async () => {
  const browser = await puppeteer.launch({ headless: true ,  args: ['--no-sandbox']});
  const page = await browser.newPage();
  try {
    await page.goto('https://www.proxysite.com/'); // Replace with the actual URL

    await page.waitForTimeout(5000);
    // Select the input box by its ID and type a value into it
    const inputSelector = '.url-form input'; // Replace with the actual selector
    const searchValue = 'https://rekvizitai.vz.lt/en/company-search/'; // Replace with the search value

    await page.type(inputSelector, searchValue);

    const buttonSelector = '.url-form button'; // Replace with the actual selector
    await page.click(buttonSelector);

    await page.waitForTimeout(5000);

    /********************************************/

    const closeModalButtonSelector = '#cookiescript_close'; // Replace with the actual selector
    await page.click(closeModalButtonSelector);

    // Set a delay before typing into the input box
    await page.waitForTimeout(3000); // Wait for 1 second

    // Select the input box by its ID and type a value into it
    const searchInputSelector = '#code'; // Replace with the actual selector
    const searchCode = '304565690'; // Replace with the search value
    await page.type(searchInputSelector, searchCode);

    // Set a delay before clicking the search button
    await page.waitForTimeout(3000); // Wait for 1 second

    // Select the search button by its ID and click it
    const submitButtonSelector = '#ok'; // Replace with the actual selector
    await page.click(submitButtonSelector);

    // Set a delay before waiting for the search results to load
    await page.waitForTimeout(5000); // Wait for 3 seconds

    // Extract the title from the company div
    const titleSelector = '.company-title'; // Replace with the actual selector
    const title = await page.$eval(titleSelector, (element) => element.textContent);

    // Check if a title was found
    if (title) {
      console.log('Company Title:', title.trim());
    } else {
      console.log('No title found.');
    }

    await page.screenshot({ path: 'new_page.png' });
  } catch (error) {
    if (error instanceof puppeteer.errors.TimeoutError) {
      console.error('Navigation timed out:', error.message);
      // Handle the timeout error (e.g., retry or skip)
    } else {
      // Handle other types of errors
      console.error('An error occurred:', error.message);
    }
  }


  await browser.close();
})();