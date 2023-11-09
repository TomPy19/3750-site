// Initialize variables
var pageCounter = 1;
var animalContainer = document.getElementById("animal-info");
var btn = document.getElementById("btn");

// Add a click event listener to the button.
btn.addEventListener("click", function() {
  // Create a new XMLHttpRequest object to make an HTTP request.
  var ourRequest = new XMLHttpRequest();

  // Construct the URL for the JSON data based on the pageCounter.
  ourRequest.open('GET', 'https://learnwebcode.github.io/json-example/animals-' + pageCounter + '.json');

  // Define what to do when the request completes successfully.
  ourRequest.onload = function() {
    // Check if the HTTP status is in the success range (200-399).
    if (ourRequest.status >= 200 && ourRequest.status < 400) {
      // Parse the received JSON data into an object.
      var ourData = JSON.parse(ourRequest.responseText);
      // Call the renderHTML function to display the data.
      renderHTML(ourData);
    } else {
      // Log an error message if the server returns an error.
      console.log("We connected to the server, but it returned an error.");
    }
  };

  // Define what to do in case of a connection error.
  ourRequest.onerror = function() {
    console.log("Connection error");
  };

  // Send the HTTP request to the server.
  ourRequest.send();

  // Increment the pageCounter to load the next page of data next time.
  pageCounter++;

  // Hide the button after loading three pages of data.
  if (pageCounter > 3) {
    btn.classList.add("hide-me");
  }
});

// Define a function to render the JSON data as HTML.
function renderHTML(data) {
  // Get the Handlebars template from the script tag
  var templateSource = document.getElementById("animal-template").innerHTML;

  // Compile the Handlebars template
  var template = Handlebars.compile(templateSource);

  // Render the data using the template
  var renderedHtml = template(data);

  // Insert the rendered HTML into the animalContainer
  animalContainer.insertAdjacentHTML('beforeend', renderedHtml);
}
