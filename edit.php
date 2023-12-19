<?php
require 'vendor/autoload.php';

// Establish MongoDB connection
$client = new MongoDB\Driver\Manager("mongodb://localhost:27017");

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Handle form submission to update MongoDB record
    $updatedData = $_POST;

    // Remove unnecessary fields
    unset($updatedData['id'], $updatedData['submit']);

    // Update MongoDB record with the new data
    $documentId = $_POST['id'];
    $filter = ['id' => $documentId];

    $update = ['$set' => $updatedData];
    $updateResult = $client->executeBulkWrite('ugcall.Contents', [$filter, $update]);

    // Check the update result and redirect to the appropriate page
    if ($updateResult->getModifiedCount() > 0) {
        // Successful update
        header("Location: viewallcontents.php");
        exit();
    } else {
        // Failed update
        echo "Error updating document.";
    }
} else {
    // Display edit form with current values

    $documentId = $_GET['id'];

    $filter = ['id' => $documentId];
    $query = new MongoDB\Driver\Query($filter);
    $cursor = $client->executeQuery("ugcall.Contents", $query);
    $currentDocument = current($cursor->toArray());

    // Include HTML header and common sections
    ?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Edit Content - UGC Admin Dashboard</title>
        <!-- Include your stylesheets, scripts, and other head elements as needed -->
    </head>

    <body>
        <!-- Include your navigation bar and other common sections -->

        <!-- Your existing HTML code goes here -->

        <!-- Form for editing content -->
        <form method="post" action="edit.php">
            <!-- Include form fields with current values -->
            <input type="hidden" name="id" value="<?php echo $currentDocument->id; ?>">

            <!-- Add other form fields based on your document structure -->
            <label>Quotes:</label>
            <input type="text" name="quotes" value="<?php echo $currentDocument->quotes; ?>">

            <label>Reviewer:</label>
            <input type="text" name="reviewer" value="<?php echo $currentDocument->reviewer; ?>">

            <!-- Include other fields as needed -->

            <input type="submit" name="submit" value="Update">
        </form>

        <!-- Rest of your HTML code -->

    </body>

    </html>
    <?php
}
?>
