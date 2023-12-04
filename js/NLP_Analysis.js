const nlp = require('compromise');
const mysql = require('mysql');

// Create a MySQL connection
const connection = mysql.createConnection({
  host: 'localhost',
  user: 'root',
  password: '',
  database: 'abcd_db',
});

// Connect to the database
connection.connect();

// Query to retrieve the description from the database
const query = 'SELECT ID, description FROM dresses'; // Include the ID in the query
const idToAnalyze = 'your_id'; // Replace 'your_id' with the actual ID of the row you want to analyze

// Execute the query
connection.query(query, [idToAnalyze], (error, results) => {
  if (error) {
    console.error('Error retrieving data from the database:', error);
    connection.end();
    return;
  }

  if (results.length === 0) {
    console.error('No data found for the specified ID.');
    connection.end();
    return;
  }

  // Extract the description text and ID from the result
  const descriptionText = results[0].description;
  const rowId = results[0].ID;

  // Parse the text
  const doc = nlp(descriptionText);

  // Count nouns
  const nounsCount = doc.nouns().out('array').length;

  // Count adjectives
  const adjectivesCount = doc.adjectives().out('array').length;

  // Send the noun and adjective counts to the server
  sendCountsToServer(rowId, nounsCount, adjectivesCount);

  // Close the database connection
  connection.end();
});

function sendCountsToServer(rowId, nounsCount, adjectivesCount) {
  const countsData = {
    rowId: rowId,
    nounsCount: nounsCount,
    adjectivesCount: adjectivesCount,
  };

  const countsRequest = new XMLHttpRequest();
  countsRequest.open('POST', 'your_php_script.php', true);
  countsRequest.setRequestHeader('Content-Type', 'application/json;charset=UTF-8');
  countsRequest.send(JSON.stringify(countsData));
}
