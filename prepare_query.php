<?php session_start(); ?>

<!DOCTYPE html>
<html>
<head>
<title>PHP Assignment 02 - Prepare Query</title>
<link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
<link type="text/css" rel="stylesheet" href="styles.css" />		
</head>

<body>

<?php 

// load external info for connecting to database 
require_once("dbinfo.php");
// attempt a connection to MySQL
$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
// determine if connection was successful 
if( mysqli_connect_errno() != 0 ){
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}


/****************************
            _____   _____  
     /\    |  __ \ |  __ \ 
    /  \   | |  | || |  | |
   / /\ \  | |  | || |  | |
  / ____ \ | |__| || |__| |
 /_/    \_\|_____/ |_____/ 
                           
*****************************/

//if the user click on "Add a Student", the $_GET["add"] is set. Direct the user to the Add a Student form.
if(isset($_GET["add"]) ){
?>

<!-- Add a student form -->
<h1>Add a student:</h1>
<fieldset>
<legend>Add a record</legend>
<form method="post" action="process_query.php">
	<input type="text" name="studentnumber" id="studentnumber" />
	<label for="studentnumber"> - Student #</label><br />
	<input type="text" name="firstname" id="firstname" />
	<label for="firstname"> - Firstname</label><br />
	<input type="text" name="lastname" id="lastname" />
	<label for="lastname"> - Lastname</label><br />
	<input type="submit" value="Submit" />
</form>
</fieldset>
<?php

}
?>


<?php 

/**************************************************
  _____   ______  _       ______  _______  ______ 
 |  __ \ |  ____|| |     |  ____||__   __||  ____|
 | |  | || |__   | |     | |__      | |   | |__   
 | |  | ||  __|  | |     |  __|     | |   |  __|  
 | |__| || |____ | |____ | |____    | |   | |____ 
 |_____/ |______||______||______|   |_|   |______|
                                                  
***************************************************/                                                                              
//if the user click on "delete", the $_GET["delete"] is set. Direct the user to the Confirm deletion page.
if(isset($_GET["delete"]) ){
?>

<!-- Delete confirmation page (HTML) -->
<h1>Delete Confirmation</h1>
<fieldset>
<legend>Delete a record - Are you sure?</legend>
<form method="post" action="process_query.php">
<?php
//display the info that the user wanted to delete at the delete confirmation page
$deleteID = $mysqli->real_escape_string($_GET["delete"]);
$query = "SELECT id, firstname, lastname FROM assignment02_table WHERE id='".$deleteID."';";
$result = $mysqli->query($query);

while( $record = $result->fetch_assoc() ){
	echo "<h4>Deleting: " . $record["id"] . " " . $record["firstname"] . " " . $record["lastname"] . "</h4>";
	// store the particular id, firstname, and lastname that the user clicked
	$_SESSION["deleteID"] = $record["id"];
	$_SESSION["deleteFirstname"] = $record["firstname"];
	$_SESSION["deleteLastname"] = $record["lastname"];
}

?>
	<input 	type="radio" 
			name="confirm" 
			id="yes" 
			value="yes"
			checked="checked" />
	<label for="yes">Yes</label><br />
	<input 	type="radio" 
			name="confirm" 
			id="no" 
			value="no" />
	<label for="no">No</label><br />	
	<input type="submit" value="Submit" />
</form>
</fieldset>
<?php
}
?>


<?php 

/*************************************************
  _    _  _____   _____         _______  ______ 
 | |  | ||  __ \ |  __ \    /\ |__   __||  ____|
 | |  | || |__) || |  | |  /  \   | |   | |__   
 | |  | ||  ___/ | |  | | / /\ \  | |   |  __|  
 | |__| || |     | |__| |/ ____ \ | |   | |____ 
  \____/ |_|     |_____//_/    \_\|_|   |______|
                                                
**************************************************/
//if the user click on "update", the $_GET["update"] is set. Direct the user to the Update page.
if(isset($_GET["update"]) ){
?>

<!-- Update information page (HTML) -->
<h1>Update a student:</h1>
<fieldset>
<legend>Update existing record</legend>
<form method="post" action="process_query.php">

<?php
//display the info that the user wanted to update
$updateID = $mysqli->real_escape_string($_GET["update"]);
$query = "SELECT id, firstname, lastname FROM assignment02_table WHERE id='".$updateID."';";
$result = $mysqli->query($query);

while( $record = $result->fetch_assoc() ){
	echo "<h4>Updating: " . $record["id"] . " " . $record["firstname"] . " " . $record["lastname"] . "</h4>";
	// store the particular id, firstname, and lastname that the user clicked
	$_SESSION["oldID"] = $record["id"];
	$_SESSION["oldFirstname"] = $record["firstname"];
	$_SESSION["oldLastname"] = $record["lastname"];
}
?>

	<input type="text" 
			name="updateStudentnumber" 
			id="studentnumber" 
			value="<?php echo $_SESSION["oldID"];?>" />
	<label for="studentnumber"> - Student #</label><br />
	<input type="text" 
			name="updateFirstname" 
			id="firstname"
			value="<?php echo $_SESSION["oldFirstname"];?>" />
	<label for="firstname"> - Firstname</label><br />
	<input type="text" 
			name="updateLastname" 
			id="lastname"
			value="<?php echo $_SESSION["oldLastname"];?>" />
	<label for="lastname"> - Lastname</label><br />
	<input type="submit" value="Submit" />
</form>
</fieldset>

<?php
}

// close the database
$mysqli->close();

// If user didn't select "add a student", "delete", or "update" -> display feedback
if( !isset($_GET["add"]) && !isset($_GET["delete"]) && !isset($_GET["update"])) {
	echo "<h4>No table administration selection was made.</h4>";
}

// display a link back to the Student db table at all times
echo "<a href='display_table.php' class='back-button'>Back to Table</a>";
?>

</body>
</html>