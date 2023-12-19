<?php

session_start();

require_once __DIR__ . '/vendor/autoload.php';  // If you're using Composer's autoloader

$dbname = 'ugcall';
$collection = 'Users';

try {
    // Establish database connection
    $client = new MongoDB\Driver\Manager("mongodb://localhost:27017");
    
    if(isset($_POST['email']) && isset($_POST['password'])) {
        $email = $_POST['email'];
        $password = $_POST['password']; // Make sure to handle passwords securely using hashing
		
		
		//$email = $_SESSION['email'];
		//$document = $collection->findOne(['email' => $email]);

        // Create a query to retrieve documents based on the email
        $filter = ['email' => $email];
        $query = new MongoDB\Driver\Query($filter);
    
        // Execute the query
        $cursor = $client->executeQuery("$dbname.$collection", $query);

        $userData = current($cursor->toArray());

        if($userData) {
            $_SESSION['email'] = $userData->email; // Save the email to the session
			$_SESSION['ftname'] = $userData->ftname;
			$_SESSION['ltname'] = $userData->ltname;
			$_SESSION['tel'] = $userData->tel;
			$_SESSION['role'] = $userData->role;
			//$_SESSION['gender'] = $userData->gender; // Store gender in the session
    // Store any other necessary properties in the session
			

            echo "Email: " . $userData->email . "<br>";
			header('location:home.php'); 
            // Continue fetching and displaying other data as needed
        } else {
             
			echo "No user found with the given email.";
			header('location:index2.php');
        }
    } /*elseif(isset($_SESSION['email'])) {
        $email = $_SESSION['email'];
        echo "Logged in as: " . $email;
		header('location:home.php'); 
    } */
	
	else {
        echo "No POST data and no session data.";
    }
} catch (MongoDB\Driver\Exception\Exception $e) {
    die("Error: " . $e->getMessage());
}
?>