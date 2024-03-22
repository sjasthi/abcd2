const { test, expect } = require('@playwright/test')
import { Constants } from '../constants.js'
import { Locators } from '../locators.js'

// Import helper function
const {
    targetURL,
    predefinedAdminLogin,
    predefinedAdminPassword
    } = Constants
const { 
        loginLoc,
        loginEmailFieldLoc,
        nominationLoc,
        nominationPageTitleLoc,
        nominationTypeLoc_Hero,
        nominationTypeLoc_Shero,
        nominationTypeLoc_Other,
        nominationNameFieldLoc,
        nominationDescriptionFieldLoc,
        nominationEmailFieldLoc,
        nominationSubmitButtonLoc,
        loginPasswordFieldLoc,
        loginButtonLoc,
        loginPageTitleLoc,
        manageNominationsTitleLoc,
        manageNominations_NameFilterFieldLoc,
        adminLoc,
} = Locators;
      
test.beforeEach(async ({ page }) => {
    // Navigate to the home page
    await page.goto(targetURL)
    await page.waitForLoadState('networkidle')
     // Login and go to Nominations page
     await page.click(loginLoc)
    await page.waitForLoadState('networkidle')
    await page.fill(loginEmailFieldLoc, predefinedAdminLogin)
    await page.fill(loginPasswordFieldLoc, predefinedAdminPassword)
    await page.click(loginButtonLoc)
    await page.waitForLoadState('networkidle')
  });
  
  test.afterEach(async ({ page }) => {
    // Close the browser
    await page.close();
  });
  
  test('Login Page: Check that all titles, labels, and fields are expected', async ({ page }) => {
  
    await page.click(adminLoc)
    await page.waitForLoadState('networkidle')
    expect(await page.isVisible("//i[@class='fa fa-shirt']")).toBe(true)
    expect(await page.isEnabled("//i[@class='fa fa-shirt']")).toBe(true)
    await page.click("//i[@class='fa fa-shirt']")

  });

