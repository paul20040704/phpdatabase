<?php session_start();
	  ob_start(); ?>

<!DOCTYPE html>
<html>
<head>
<title>PHP - Process Query</title>
<link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
<link type="text/css" rel="stylesheet" href="styles.css" />		
</head>

<body>

<?php 
/*
this page:
-----------------------------------------------------------------------
- determines if user came from form
	- if user came from form, determines if form information is valid
		- if information is INvalid, forward user back to students table page
		- if information is valid, process the query (add, delete or update record)
- if user does not came from form, forward to display students table page
-----------------------------------------------------------------------
*/

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

/*
the part below:
-----------------------------------------------------------------------
- determines if user came from "Add a student" form
	- if user came from form, determine if form has any empty strings
		- if any field has left blank, forward user back to students table page
		- if all fields are completed --> check with our database
			- if there is a duplicate student #, forward user back to students table page
			- if there is NO duplicate student #, check to see if it matches the pattern
				-if pattern matches ->add the fields info into our db 
				-if pattern NOT match -> forward back to students table page
-----------------------------------------------------------------------
*/

//make sure form fields exist
if( isset($_POST["studentnumber"])  && isset($_POST["firstname"] ) && isset($_POST["lastname"]) ){
	//store the form fields securely to prevent SQL Injection
	$safeStudentnumber = $mysqli->real_escape_string(trim($_POST["studentnumber"]));
	$safeFirstname = $mysqli->real_escape_string(trim($_POST["firstname"]));
	$safeLastname = $mysqli->real_escape_string(trim($_POST["lastname"]));

	//form fields exist, check to see if it has empty strings
	if($safeStudentnumber == "" || $safeFirstname == "" || $safeLastname == ""){
		//if any fields is left empty, display an error message and forward them back to the records page
		$_SESSION["error"] = "<h3>Could not add student! You have at least one field left empty!</h3>";
		header("location: display_table.php");
	}else{
		//user didn't left any fields blank

		//prepare an SQL query to check if there are any duplicates of student # in the database, if not --> add the record
		$query = "SELECT id FROM assignment02_table WHERE id='$safeStudentnumber';";
		// run the query, capture results in variable
		$results = $mysqli->query($query);
		// check if there is a student # match with our database
		$numberOfMatch = $results->num_rows;
		// echo "<p>Number of records:".$numberOfMatch ."</p>";

		if( $numberOfMatch != 0){
			// there is a student # match with our database, display error message
			$_SESSION["error"] = "<h3>Could not add student! There is a duplicated student # with the database!</h3>";
			header("location: display_table.php");
		}else{
			// there is no duplicate student #, check to see if it matches the student # pattern
			$regex = "/^a0[0-9]{7}$/i";

			if( preg_match($regex, $safeStudentnumber) == 0 ){
				// student # does not match pattern, display error
				$_SESSION["error"] = "<h3>Could not add student! The student # must match a0XXXXXXX!</h3>";
				header("location: display_table.php");
			}else{
				// the new student # matches the pattern, add all form fields to our database
				$addInfo = "INSERT INTO assignment02_table (id,firstname,lastname) VALUES( '$safeStudentnumber','$safeFirstname','$safeLastname');";
				$mysqli->query( $addInfo );
				// echo "Number of rows inserted: " . $mysqli->affected_rows;
				$_SESSION["success"] = "<h3 class='success'>Record added: $safeStudentnumber $safeFirstname $safeLastname</h3>";
				header("location: display_table.php");
			}
		}
	}
}


/**************************************************
  _____   ______  _       ______  _______  ______ 
 |  __ \ |  ____|| |     |  ____||__   __||  ____|
 | |  | || |__   | |     | |__      | |   | |__   
 | |  | ||  __|  | |     |  __|     | |   |  __|  
 | |__| || |____ | |____ | |____    | |   | |____ 
 |_____/ |______||______||______|   |_|   |______|
                                                  
***************************************************/
/*
the part below:
-----------------------------------------------------------------------
- determines if user came from "Delete" form
	- if user came from form, determine if user selected "yes" to delete
		- if user selected "yes" to delete, delete all the selected record from our database and forward user back to students table page with the updated database
		- if user selected "no", forward the user back to students table page
-----------------------------------------------------------------------
*/

if( isset($_POST["confirm"]) ){
	if($_POST["confirm"] == "yes"){
		$query = "DELETE FROM assignment02_table WHERE id='".$_SESSION["deleteID"]."';";
		$result = $mysqli->query($query);
		$_SESSION["success"] = "<h3 class='success'>" . $_SESSION["deleteID"] . "&nbsp" . $_SESSION["deleteFirstname"] . "&nbsp" . $_SESSION["deleteLastname"] . "&nbsp" . "is deleted!</h3>";
		header("location: display_table.php");
	}else{
		$_SESSION["error"] = "<h3>No record is deleted!</h3>";
		header("location: display_table.php");
	}
}


/*************************************************
  _    _  _____   _____         _______  ______ 
 | |  | ||  __ \ |  __ \    /\ |__   __||  ____|
 | |  | || |__) || |  | |  /  \   | |   | |__   
 | |  | ||  ___/ | |  | | / /\ \  | |   |  __|  
 | |__| || |     | |__| |/ ____ \ | |   | |____ 
  \____/ |_|     |_____//_/    \_\|_|   |______|
                                                
**************************************************/
/*
the part below:
-----------------------------------------------------------------------
- determines if user came from "Update" form
	- if user came from form, determine if there is any empty fields or if the user entered identical information
		- if any field is empty, if all information is identical --> forward user back to students table page
		- if at least one field is different from our database --> update our database
-----------------------------------------------------------------------
*/
if( isset($_POST["updateStudentnumber"]) && isset($_POST["updateFirstname"]) && isset($_POST["updateLastname"]) ){
	//store the form fields securely to prevent SQL Injection
	$newStudentnumber = $mysqli->real_escape_string(trim($_POST["updateStudentnumber"]));
	$newFirstname = $mysqli->real_escape_string(trim($_POST["updateFirstname"]));
	$newLastname = $mysqli->real_escape_string(trim($_POST["updateLastname"]));

	if( $newStudentnumber == "" || 
		$newFirstname == "" || 
		$newLastname == ""){
			// user has at least one empty field
			$_SESSION["error"] = "<h3>No record updated! You have at least one field left empty!</h3>";
			header("location: display_table.php");
	}elseif( $newStudentnumber == $_SESSION["oldID"] && 
			 $newFirstname == $_SESSION["oldFirstname"] &&
			 $newLastname == $_SESSION["oldLastname"] ){
			// user entered identical information that is already in our database
			$_SESSION["error"] = "<h3>No record updated! This record is already in our database!</h3>";
			header("location: display_table.php");
	}else{
		// user entered at least one field that is different from our database, update our db
		$query = "UPDATE assignment02_table SET id='".$newStudentnumber."' ,firstname='".$newFirstname."' ,lastname='".$newLastname."' WHERE id='".$_SESSION["oldID"]."';";
		$result = $mysqli->query($query);
		$_SESSION["success"] = "<h3 class='success'>Record updated: " . $newStudentnumber . "&nbsp" . $newFirstname . "&nbsp" . $newLastname . "!</h3>";
		header("location: display_table.php");
	}
}

ob_end_flush();
// close the database
$mysqli->close();
?>
</body>
</html>