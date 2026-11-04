import axios from './redaxios.jss';

// Get something from an API somewhere.  TODO: use an actual API
async function fetchFromAPI() {
  const outputDiv = document.getElementById('testOutput');

  try {
    const response = await axios.get('foo.txt');	// Relative path (to html) is fine
    outputDiv.innerHTML += ("<div>" + `REST Result: ${response.data}` + "</div>");
  } catch (error) {
    outputDiv.innerHTML += ("<div>" + `Error: ${error.message}` + "</div>");
  }
}

function init() {
  const btn = document.getElementById('fetchButton');

  btn.addEventListener('click', fetchFromAPI);
}

// Link everything up
init();
