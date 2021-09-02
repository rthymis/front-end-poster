
window.onload = function() {

// Function that gets the JSON data from the WP rest API
  function myFunction() {
  var ourRequest = new XMLHttpRequest();
    ourRequest.open('GET', WPURLS.siteurl + '/wp-json/naq/v1/posts');
    ourRequest.onload = function() {
      if (ourRequest.status >= 200 && ourRequest.status < 400) {
        var data = JSON.parse(ourRequest.responseText);


       // Function that calls the getInputValue everytime we type something in the title input field
       document.getElementById("title").oninput = function() {getInputValue(data)};
       document.getElementById("contentt").oninput = function() {getInputValue(data)};


      //alert (data[0].slug);
      } else {
        console.log("We connected to the server, but it returned an error.");
      }
     };

     ourRequest.onerror = function() {
       console.log("Connection error");
     };

     ourRequest.send();



  }
  myFunction();




        // Function to get the value of the post title input everytime we type in the input field
        function getInputValue(data){

          // Selecting the input element and get its value
          var inputVal = document.getElementById("title").value;
          var inputVal = inputVal.toLowerCase();
          var contentInputVal = document.getElementById("contentt").value;
          

          if (inputVal.length > 0 && contentInputVal.length > 0) {
            document.getElementById("submit").disabled = false;
          } else {
            document.getElementById("submit").disabled = true;
          }

          // Empty error message by default
          document.getElementById("errormessage").innerHTML = '';

          // check all the posts if slug already exists
          for (let i = 0; i < data.length; i++) {
            if (data[i].slug == inputVal) {
              //alert ("exists");
              document.getElementById("submit").disabled = true;
              document.getElementById("errormessage").innerHTML = 'Title already exists. </br> Please choose another title.';
            }
          }
        }
      }
