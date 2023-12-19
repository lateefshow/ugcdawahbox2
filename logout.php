<?php
session_start();

// Connect to the database
$client = new MongoDB\Driver\Manager("mongodb://localhost:27017");
$dbname = 'ugcall';
$collection = 'Users';

$last_logout = date("Y-m-d H:i:s");
$bulk = new MongoDB\Driver\BulkWrite;
$filter = ['email' => $_SESSION['email']];
$update = ['$set' => ['last_logout' => $last_logout]];
$bulk->update($filter, $update);
$client->executeBulkWrite("$dbname.$collection", $bulk);

// Destroy session and redirect user to login page or wherever you want
session_destroy();
header("Location: index.php");
exit;




//session_start();
//session_destroy();
//header('location:index.php');
?>

