
//To run test. install packages using terminal command: (Asusming IDE Visual Studio Code)
//npm install selenium-webdriver

//Followed by terminal command below to run the test after starting XAMPP:
//node test.js

const assert = require('assert');
const { Builder, By, until } = require('selenium-webdriver');

async function runTests() {
  
  const sampleCredentials = [
    { username: 'admin_1', password: 'admin_pass1', profileName: 'admin', website: 'AdminLanding.php' },
    { username: 'agent_1', password: 'agent_pass1', profileName: 'agent', website: 'AgentLanding.php' },
    { username: 'buyer_1', password: 'buyer_pass1', profileName: 'buyer', website: 'BuyerLanding.php' },
    { username: 'seller_1', password: 'seller_pass1', profileName: 'seller', website: 'SellerLanding.php' },
  ];

  const invalidCredentials = [
    { username: 'admin_1', password: 'admin_pass2', profileName: 'admin', website: 'AdminLanding.php' },
    { username: 'agent_1', password: 'agent_pass2', profileName: 'agent', website: 'AgentLanding.php' },
    { username: 'buyer_1', password: 'buyer_pass2', profileName: 'buyer', website: 'BuyerLanding.php' },
    { username: 'seller_1', password: 'seller_pass2', profileName: 'seller', website: 'SellerLanding.php' },
  ];


  //Iterate for testLoginSuccess
  for (const profile of sampleCredentials) {
    await testLoginSuccess(profile.username, profile.password, profile.profileName, profile.website);
  }

  //Iterate for testLoginInvalid
  for (const profile of invalidCredentials) {
    await testFailedLogin(profile.username, profile.password, profile.profileName);
  }

  //Iterate for testLogout
  for (const profile of sampleCredentials) {
    await testLogoutSuccess(profile.username, profile.password, profile.profileName, profile.website);
  }

}

// Login test for when a user has VALID credentials
async function testLoginSuccess(username, password, profileName, website) {

  let driver = await new Builder().forBrowser('chrome').build();
  try {

    await driver.get('http://localhost/konoha/');

    await driver.findElement(By.id('username')).sendKeys(username);
    await driver.findElement(By.id('password')).sendKeys(password);
    await driver.findElement(By.id('profile')).sendKeys(profileName);

    // Wait for 'submit' button to continue
    await driver.findElement(By.css('button[type="submit"]')).click();

    await driver.wait(until.urlIs(`http://localhost/konoha/${website}`), 5000);

    //Either
    //1) Test pass: URL contains "AdminLanding.php"
    //2) Test fail: Timer runs out (Using this implementation due to framework constraints)

    const timeout = 5000; 
    await Promise.race([
      driver.wait(until.urlContains(website)),
      new Promise(resolve => setTimeout(resolve, timeout))
    ]);

    // Wait for URL to change to continue
    let currentUrl = await driver.getCurrentUrl();

    //Assertion starting from this block
    assert.ok(currentUrl.includes(website), 'Login test error');
    console.log(`SUCCESSFUL LOGIN UNIT TEST for ${profileName} - Passed`);

  } catch (error){
      console.log(`SUCCESSFUL LOGIN UNIT TEST for ${profileName} - Failed `);
  } finally {
      await driver.quit();
  }
}

//Login test for when a user has INVALID credentials
async function testFailedLogin(username, password, profileName) {''
  let driver = await new Builder().forBrowser('chrome').build();
  try {
    await driver.get('http://localhost/konoha/');

    // Fill out username, password, and profile fields with provided values
    await driver.findElement(By.id('username')).sendKeys(username);
    await driver.findElement(By.id('password')).sendKeys(password);
    await driver.findElement(By.id('profile')).sendKeys(profileName);

    // Click the submit button to trigger the login function
    await driver.findElement(By.css('button[type="submit"]')).click();

    // Wait for the "loginMessage" element to appear
    await driver.wait(until.elementLocated(By.id('loginMessage')), 5000);

    // Wait for the login message to be displayed
    let loginMessageElement = await driver.findElement(By.id('loginMessage'));
    await driver.wait(until.elementIsVisible(loginMessageElement), 5000);

    // Check for keywords('invalid' or 'suspended') in the loginMessage
    let loginMessage = await loginMessageElement.getText();

    assert.ok(loginMessage.includes('Invalid' || "suspended"), "Invalid login test error");
    console.log(`SUCCESSFUL INVALID LOGIN UNIT TEST for ${profileName} - Passed`);
  } catch (error){
    console.log(`SUCCESSFUL INVALID LOGIN UNIT TEST for ${profileName} - Failed`);
  } finally {
    await driver.quit();
  }
}

//Logout test for each user
async function testLogoutSuccess(username, password, profileName, website) {
  let driver = await new Builder().forBrowser('chrome').build();
  try {

    // Navigate to the website's landing page
    await driver.get(`http://localhost/konoha/`);

    //Login first with sample credentials due to security constraints
    await driver.findElement(By.id('username')).sendKeys(username);
    await driver.findElement(By.id('password')).sendKeys(password);
    await driver.findElement(By.id('profile')).sendKeys(profileName);

    // Wait for 'submit' click button to continue
    await driver.findElement(By.css('button[type="submit"]')).click();

    // Go to specific profile dashboard
    await driver.wait(until.urlIs(`http://localhost/konoha/${website}`), 5000);

    //Identify and click the logout button
    await driver.findElement(By.css('a[href="logout.php"]')).click();

    // Wait for the URL to change to the login page or index.php page after logout
    await driver.wait(until.urlContains('index.php'), 5000);

    let currentUrl = await driver.getCurrentUrl();
    if (currentUrl.includes('index.php')) {
      console.log(`SUCCESSFUL LOGOUT UNIT TEST for ${profileName} - Passed`);
    } else {
      console.log(`SUCCESSFUL LOGOUT UNIT TEST for ${profileName} - Failed`);
    }

  } catch (error) {
    console.log('Failed test. An error occured during testing');
    console.error(error);
  } finally {
    await driver.quit();
  }
}

runTests();

