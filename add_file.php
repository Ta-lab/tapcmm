 <?php
// Check if a file has been uploaded
if(isset($_FILES['uploaded_file'])) {
    // Make sure the file was sent without errors
    if($_FILES['uploaded_file']['error'] == 0) {
        // Connect to the database
        $dbLink = new mysqli('localhost', 'root', 'Tamil', 'mypcm');
		if(mysqli_connect_errno()) {
            die("MySQL connection failed: ". mysqli_connect_error());
        }
		
		$appno=$_GET['appno'];
		
		// Gather all required data
        $name = $dbLink->real_escape_string($_FILES['uploaded_file']['name']);
        $mime = $dbLink->real_escape_string($_FILES['uploaded_file']['type']);
        $data = $dbLink->real_escape_string(file_get_contents($_FILES  ['uploaded_file']['tmp_name']));
        $size = intval($_FILES['uploaded_file']['size']);
 
        // Create the SQL query
        $query = "
            INSERT INTO `file` (
                `name`, `mime`, `size`, `data`, `created`
            )
            VALUES (
                '{$name}', '{$mime}', {$size}, '{$data}', NOW()
            )";
		
		
		//$query1 ="UPDATE npd_invoicing SET cftdocument='{$data}' WHERE appno='$appno'";
		
		$query1 ="UPDATE npd_invoicing SET cftdocument='{$data}',filename='{$name}' WHERE appno='$appno'";
		$result1 = $dbLink->query($query1);
		
        // Execute the query
        $result = $dbLink->query($query);
        // Check if it was successfull
        if($result) {
            echo 'Success! Your file was successfully added!';
			header("location: npd_inv_app.php?msg=4");
        }
        else {
            echo 'Error! Failed to insert the file'
               . "<pre>{$dbLink->error}</pre>";
        }
    }
    else {
        echo 'An error accured while the file was being uploaded. '
           . 'Error code: '. intval($_FILES['uploaded_file']['error']);
		header("location: npd_inv_app.php?msg=3");
    }
 
    // Close the mysql connection
    $dbLink->close();
}
else {
    echo 'Error! A file was not sent!';
}
 
// Echo a link back to the main page
//echo '<p>Click <a href="index.html">here</a> to go back</p>';
?>