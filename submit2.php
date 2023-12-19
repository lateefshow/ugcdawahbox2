<?php
require 'vendor/autoload.php'; // Assuming you've installed the MongoDB PHP Library via Composer

// Connect to MongoDB
//$client = new MongoDB\Driver\Manager("mongodb://localhost:27017");
//$collection = $client->demo->users; // "demo" is the database name and "users" is the collection

try {
    // Establish database connection
    $client = new MongoDB\Driver\Manager("mongodb://localhost:27017");
    
    // Create a query to retrieve documents
    $dbname = 'ugcall';
    $collection = 'Users';
    $query = new MongoDB\Driver\Query([]);
    
    // Execute the query
    $cursor = $client->executeQuery("$dbname.$collection", $query);

} catch (MongoDB\Driver\Exception\Exception $e) {
    die("Error: " . $e->getMessage());
}


// Function to get the next sequence
function getNextSequence($name) {
    global $client;

    $command = new MongoDB\Driver\Command([
        'findAndModify' => 'usercounters',
        'query' => ['_id' => $name],
        'update' => ['$inc' => ['seq' => 54]],
        'new' => true,
        'upsert' => true
    ]);

    $result = $client->executeCommand('ugcall', $command);
    $res = current($result->toArray());
    if (isset($res->value->seq)) {
        return $res->value->seq;
    }

    throw new Exception("Failed to get sequence");
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //$_id = $_POST['_id'];
	$id = getNextSequence('packid');
    $email = $_POST['email'];
	$ftname = $_POST['ftname'];
	$ltname = $_POST['ltname'];
	$password = $_POST['password'];
	$tel = $_POST['tel'];
	$role = 'Agent';
	$gender = $_POST['gender'];
	$status = '1';
	$active = '1';
	$leg_count = '0';
	//$createdDate = date("Y-m-d");
	$last_logout = date("Y-m-d H:i:s"); // This will output the current time.
	$login_time = date("Y-m-d H:i:s"); // This will output the current time.

    if (!empty($email) && !empty($ftname)) {
	
	// Create a new bulk write object	
	$bulk = new MongoDB\Driver\BulkWrite;

    // Insert a new document
	$document = [
        'id' => $id,
        'email' => $email,
        'ftname' => $ftname,
        'ltname' => $ltname,
        'password' => $password,
        'tel' => $tel,
        'role' => $role,
        'status' => $status,
        'active' => $active,
        'leg_count' => $leg_count,
        'last_logout' => $last_logout,
		'login_time' => $login_time
    ];
	
	
	
    //$bulk->insert(['name' => 'Alice', 'age' => 25, 'email' => 'alice25@yahoo.com']);
	//$bulk->insert(['email' => $email, 'ftname' => $ftname, 'ltname' => $ltname, 'password' => $password, 'gender' => $gender, 'tel' => $tel, 'role' => $role]);

    // Execute the bulk write
    //$client->executeBulkWrite("$dbname.$collection", $bulk);
	$bulk->insert($document);
    $result = $client->executeBulkWrite('ugcall.Users', $bulk);
		
     /*   $insertOneResult = $collection->insertOne([
            'name' => $name,
            'email' => $email,
        ]); */
        //echo "Inserted with Object ID '{$insertOneResult->getInsertedId()}'";
		echo "New Details Successfully Inputted into Mongo Database";
    } else {
        echo "Both fields are required!";
    }
}
?>
