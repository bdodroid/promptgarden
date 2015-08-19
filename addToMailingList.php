<?php
// process.php
$servername = "localhost";
$username = "webepira_bdo";
$password = "12not4u2";
$dbname = "webepira_preWebsiteMailingList";

$errors         = array();      // array to hold validation errors
$data           = array();      // array to pass back data

$var_email = $_POST['email'];

// Create connection
			$conn = mysqli_connect($servername, $username, $password, $dbname);
			// Check connection
			if (!$conn) {
				die("Connection failed: " . mysqli_connect_error());
			}

			$checkDatabase = "SELECT * FROM mailinglist WHERE email = '$var_email'";
			$result = mysqli_query($conn, $checkDatabase);
			$numRow = mysqli_num_rows($result);
			
			if (strpos($var_email,'@') !== false){
			if($numRow > 0){

				$data['email'] = 'already have that email on file.';

			}else if($var_email != null){
				
					$sql = "INSERT INTO mailinglist (email)
					VALUES ('$var_email')";

					if (mysqli_query($conn, $sql)) {
						$data['email'] = "You've been added!";
						$data['works'] = true;
					} else {
						$data['email'] = 'Error: ' . $sql . '<br>' . mysqli_error($conn);
					}
				
			}else{
				$data['email'] = 'email cannot be left blank.';
				$data['works'] = false;

			}
			}else{
					$data['email'] = "must be an actual email";
				 	$data['works'] = false;
				}
			

// validate the variables ======================================================
    // if any of these variables don't exist, add an error to our $errors array

    
    if (empty($_POST['email']))
        $errors['email'] = 'Email is required.';


// return a response ===========================================================

    // if there are any errors in our errors array, return a success boolean of false
    if ( ! empty($errors)) {
		
        // if there are items in our errors array, return those errors
        $data['success'] = false;
        $data['errors']  = $errors;
    } else {

        // if there are no errors process our form, then return a message

        // DO ALL YOUR FORM PROCESSING HERE
        // THIS CAN BE WHATEVER YOU WANT TO DO (LOGIN, SAVE, UPDATE, WHATEVER)
		
			
			

        // show a message of success and provide a true success variable
        $data['success'] = true;
        $data['message'] = 'Success!';
    }

    // return all our data to an AJAX call
    echo json_encode($data);