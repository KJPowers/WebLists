import axios from './redaxios.js';

// Get something from an API somewhere.  TODO: use an actual API
// The 'getPath' parameter should be relative to index.html
async function fetchFromAPI(getPath) {
  const outputDiv = document.getElementById('testOutput');

  try {
    const response = await axios.get(getPath);	// Relative path (to html) is fine
    outputDiv.innerHTML += ("<div>" + `REST Result: ${response.data}` + "</div>");
  } catch (error) {
    outputDiv.innerHTML += ("<div>" + `Error: ${error.message}` + "</div>");
  }
}

function init() {
	document.getElementById('fetchFooButton')
					.addEventListener('click', () => { fetchFromAPI('foo.txt') });

	document.getElementById('fetchAPIButton')
					.addEventListener('click', () => { fetchFromAPI('api/hello') });
}

// Link everything up
init();
