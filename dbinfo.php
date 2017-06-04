<?php
/*
dbinfo.php: database connectivity information
-------------------------------------------------------------
DB_HOST defines the name of the MySQL server
DB_USER is a username who has connectivity privileges on the MySQL server
DB_PASS is the appropriate MySQL password for the DB_USER
DB_NAME is the name of the database to connect with
*/
const DB_HOST		= "localhost";			
const DB_USER		= "serenasu_phpdb";	
const DB_PASS		= "MsFMW4OzN1U66f5p";	
const DB_NAME		= "serenasu_phpdb";
/* 
-- IMPORTANT --
all PHP scripts in TWD that use databases MUST
use a file named dbinfo.php like this one.
this will allow other developers to 
swap their dbinfo.php for yours,
thus conveniently altering the 
connectivity information

all dbinfo.php files MUST contain the 
four named CONSTANTS declared above,
assign each with your connectivity values

do NOT include any other code in your dbinfo.php
only these four constants should appear here
*/
?>