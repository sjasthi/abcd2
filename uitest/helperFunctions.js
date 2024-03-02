/**
 * Generates a random string of specified length.
 * @param {number} length - The length of the random string to generate.
 * @returns {string} - The generated random string.
 */
function generateRandomString_LettersOnly(length) {
    const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
    let result = '';
    for (let i = 0; i < length; i++) {
      result += characters.charAt(Math.floor(Math.random() * characters.length));
    }
    return result;
  }
  
  module.exports = {
    generateRandomString_LettersOnly: generateRandomString_LettersOnly
  };