<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// ******************************************

// Key Points Admin

// ******************************************

// Add the meta box

add_action( 'add_meta_boxes', 'key_points_meta_box_add' );

function key_points_meta_box_add(){

  add_meta_box( 'key-points-admin', 'Key Points', 'key_points_meta_box_content', 'product', 'side', 'high');

}

// Set the meta box content

function key_points_meta_box_content(){

  // Styling first

  // Note that most of the classes used are from the product tags meta box

  // So the only stlying we do here is overrides of that

  echo '
  <style>

  .key-point-input-error{

    border:2px solid red !important;

  }

  .key-points-list > li{

    list-style: inside;
    float: none;

  }

  .product-remove-key-point{

    margin-left: 0px !important;

  }

  .key-point-toremove{

    text-decoration: line-through;
    font-weight: bold;

  }

  .key-point-new{

    font-weight: bold;

  }

  .key-point-toremove > button > span:before{

    content: "\f171" !important;

  }

  </style>
  ';

  // Inside div

  echo '<div class="tagsdiv" id="key-points">';

  // Add new key point

  echo '

  <div class="jaxtag">
  		<div class="ajaxtag hide-if-no-js">
  		<label class="screen-reader-text" for="new-key-point">Add new key point</label>
  		<input type="text" id="keypointvalue" name="keypointvalue" class="newkeypoint" autocomplete="off" value="">
  		<input type="button" id="addkeypoint" name="addkeypoint" class="button addkeypoint" onclick="newkeypoint()" value="Add">
  	</div>
  	<p class="current-key-points">Current Key Points:</p>
  </div>

  ';

  // List of current key points with delete button

  echo '<ul id="key-points-list" class="key-points-list tagchecklist">';

  $keypoints = get_post_custom_values('key_point');

  $kpid = 0;

  foreach ($keypoints as $keypoint) {

    echo '<li id="product-key-point-'.$kpid.'">'.$keypoint.'<button type="button" class="ntdelbutton product-remove-key-point" onclick="deletekeypoint(\''.$kpid.'\')"><span id="remove-keypoint-icon" class="remove-keypoint-icon remove-tag-icon" aria-hidden="true"></span></li>';

    ++$kpid;

  }

  echo '</ul>';

  // Close inside div

  echo '</div>';

  // Scripts last

  echo '

  <script>

  // *********************************

  // Click button on input box enter

  // *********************************

  // Get the input field

  var input = document.getElementById("keypointvalue");

  // Execute a function when the user releases a key on the keyboard

  input.addEventListener("keypress", function(event) {

    // Number 13 is the "Enter" key on the keyboard

    if (event.keyCode === 13) {

      // Cancel the default action, if needed

      event.preventDefault();

      event.stopImmediatePropagation();

      // Trigger the button element with a click

      document.getElementById("addkeypoint").click();

    }

  });

  // *********************************

  // Add new key point function

  // *********************************

  // Define a variable to store the current ID of the new key point

  var newkpid = 0;

  function newkeypoint(){

    // Set the input text box element as a variable

    var newkpinput = document.getElementById("keypointvalue");

    // Set the content of the input text box as a variable

    var newkpval = newkpinput.value;

    // Make sure the text input had some value

    if(newkpval === ""){

      // If it does not and is blank we make the input box have a custom CSS class that gives it a red border

      newkpinput.className = newkpinput.className + " key-point-input-error";  // this adds the error class

      // And we set the placeholder to tell the user to enter a value

      newkpinput.placeholder = "Enter a value!";

      // We also return false so this function wont run

      return false;

    }
    else{

      // If a value is present then we reset the text input to default CSS classes

      newkpinput.className = newkpinput.className.replace(" key-point-input-error", ""); // this removes the error class

      // And make sure the placeholder is empty

      newkpinput.placeholder = "";

    }

    // Create HTML content

    // Open an <li>

    var newkp = "<li id=\"product-key-point-n" + newkpid + "\" class=\"key-point-new\">";

    // Add the input text box value

    newkp = newkp + newkpval;

    // Add HTML button to delete new key point

    newkp = newkp + "<button type=\"button\" class=\"ntdelbutton product-remove-key-point\" onclick=\"deletekeypoint(\'n" + newkpid  + "\')\"><span id=\"remove-keypoint-icon\" class=\"remove-keypoint-icon remove-tag-icon\" aria-hidden=\"true\"></span></button>";

    // Add HTML hidden input for adding new key point

    newkp = newkp + "<input type=\"hidden\" id=\"keypoint-new-" + newkpid + "\" name=\"keypoint-new[" + newkpid + "]\" value=\"" + newkpval + "\">";

    // Close the <li>

    newkp = newkp + "</li>";

    // Add to HTML list

    var ul = document.getElementById("key-points-list");

    ul.insertAdjacentHTML(\'beforeend\', newkp);

    // Clear the input text box

    document.getElementsByName(\'keypointvalue\')[0].value = "";

    // Iterate the new key point ID

    newkpid++;

  }

  // *********************************

  // Delete key point function

  // *********************************

  function deletekeypoint(kpid){

    // Set the element

    var element = document.getElementById("product-key-point-"+kpid);

    // Check if the element is freshly added

    if(kpid.startsWith("n")){

      // For freshly added elements we delete them completely

      element.remove();

      // Return false to break the function

      return false;

    }

    // Check if the element has already been marked for removal

    if(element.classList.contains("key-point-toremove")){

      // If it has then clicking the button again we will take to mean undo

      // So we will un mark it for removal

      element.classList.remove("key-point-toremove");

      // We also remove the hidden input that would have deleted it on POST

      document.getElementById("keypoint-to-remove-" + kpid).remove();

    }
    else{

      // If it has not yet been marked for removal then we mark it for removal

      element.classList.add("key-point-toremove");

      // Get the value of the key point

      var keypointvalue = element.innerText || element.textContent;

      // Create a hidden input that will be POSTed containing all the key points to remove

      element.innerHTML = element.innerHTML + "<input type=\"hidden\" id=\"keypoint-to-remove-" + kpid + "\" name=\"keypoint-to-remove[" + kpid + "]\" value=\"" + keypointvalue + "\">";

    }

  }

  </script>

  ';

}

// Process the key points meta box results

function key_points_meta_box_process($post_id){

  // ---------------------

  // ADD NEW KEY POINTS

  // ---------------------

  // Make sure at least one key point to add has been POSTed

  if ( array_key_exists( 'keypoint-new', $_POST ) ) {

    // If one has then we loop through all the keypoints to add

    foreach ($_POST['keypoint-new'] as $value) {

      // And we add each one

      add_metadata( 'post', $post_id, 'key_point', $value);

    }

  }

  // ---------------------

  // DELETE KEY POINTS

  // ---------------------

  // Make sure at least one key point to remove have been POSTed

  if ( array_key_exists( 'keypoint-to-remove', $_POST ) ) {

    // If one has then we loop through all the keypoints to remove

    foreach ($_POST['keypoint-to-remove'] as $value) {

      // And we remove each one

      delete_post_meta($post_id, 'key_point', $value);

    }

  }

}

add_action( 'save_post', 'key_points_meta_box_process' );

?>
