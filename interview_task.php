<?php
if(isset($_POST["submit"]))
{
	$url='localhost';
	$username='root';
	$password='Tamil';
	$conn=mysqli_connect($url,$username,$password,"icloudems");
	
	if(!$conn){
		die('Could not Connect My Sql:' .mysqli_error());
	}
	  
	$file_extension = pathinfo($_FILES["file"]["name"], PATHINFO_EXTENSION);
	
	if ($file_extension != "csv") {
        $response = array(
			"type" => "error",
			"message" => "Invalid CSV: File must have .csv extension."
		);
	}
	
	else{
	
		$file = $_FILES['file']['tmp_name'];
		$handle = fopen($file, "r");
		$c = 0;
		while(($filesop = fgetcsv($handle, 1000, ",")) !== false)
		{
			$fname = $filesop[0];
			$lname = $filesop[1];
			$sql = "insert into excel(fname,lname) values ('$fname','$lname')";
			$stmt = mysqli_prepare($conn,$sql);
			mysqli_stmt_execute($stmt);

			$c = $c + 1;
		}

		if($sql){
			echo "sucess";
		} 
		else
		{
			echo "Sorry! Unable to impo.";
		}
	}	

}
?>
<!DOCTYPE html>
<html>
<body>
<form enctype="multipart/form-data" method="post" role="form">
    <div class="form-group">
        <label for="exampleInputFile">File Upload</label>
        <!--<input type="file" name="file" id="file" size="150">-->
		<input type="file" name="file" id="file" size="150" accept=".csv" />
        <p class="help-block">Only Excel/CSV File Import.</p>
    </div>
    <button type="submit" class="btn btn-default" name="submit" value="submit">Upload</button>
</form>

<?php if(!empty($response)) { ?>
<div class="response <?php echo $response["type"]; ?>
    ">
    <?php echo $response["message"]; ?>
</div>
<?php }?>


</body>
</html>