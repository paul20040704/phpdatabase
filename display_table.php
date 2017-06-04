<?php session_start(); ?>

<!DOCTYPE html>
<html>
<head>
<title>PHP Record</title>
<link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
<link type="text/css" rel="stylesheet" href="styles.css" />		
</head>
<!-- http://bcitcomp.ca/twd/assignment02_sample/ -->

<body>
		
<h1>Student Database</h1>
<div class="dbtable">
<?php 
//display any error messages here...

if( isset($_SESSION["error"]) ){
	//if there is an error message, display it
	echo $_SESSION["error"];
	//clear the error message now that we've displayed them
	unset($_SESSION["error"]);
}

//display any success messages here...
if( isset($_SESSION["success"]) ){
	//if there is an success message, display it
	echo $_SESSION["success"];
	//clear the success message now that we've displayed them
	unset($_SESSION["success"]);
}

?>
	<h2>Students:</h2>

	<p><a href='prepare_query.php?add'>Add a Student</a></p>

<?php 
// The following PHP script connects to "assignment02_table.sql" database and display the students table as HTML page

// load external info for connecting to database 
require_once("dbinfo.php");
// attempt a connection to MySQL
$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
// determine if connection was successful 
if( mysqli_connect_errno() != 0 ){
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

// prepare an SQL query	
$query = "SELECT * FROM assignment02_table;";
/* run the query, capture results in variable */
$results = $mysqli->query($query);

/* process query results and display as HTML table */
echo "<table>";
echo "<tr><th>Student #</th>";
echo "<th>Firstname</th>";
echo "<th>Lastname</th>";
echo "<th>Delete Record</th>";
echo "<th>Update Record</th></tr>";
while( $record = $results->fetch_assoc() ){
	echo "<tr><td>" . $record["id"] . "</td>";
	echo "<td>" . $record["firstname"] . "</td>";
	echo "<td>" . $record["lastname"] . "</td>";
	echo "<td><a href='prepare_query.php?delete=".$record["id"]."'>delete</a></td>";
	echo "<td><a href='prepare_query.php?update=".$record["id"]."'>update</a></td></tr>";
}
echo "</table>";

// close the database
$mysqli->close();
?>

</div>
</body>
</html>