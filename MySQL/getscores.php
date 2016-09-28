<?php
header('Access-Control-Allow-Origin: *');

error_reporting(E_ALL);
ini_set('display_errors', 1);


$host="localhost"; // Host name 
$username="kreso"; // Mysql username 
$password="kreso1004"; // Mysql password 
$db_name="sakila"; // Database name 
$tbl_name="scores"; // Table name

//echo "korak 1";
// Connect to server and select database.
$con=mysqli_connect("$host", "$username", "$password")or die("cannot connect"); 
mysqli_select_db($con,$db_name)or die("cannot select DB");

//echo "korak 2";
// Retrieve data from database 
$sql="SELECT * FROM scores ORDER BY score DESC LIMIT 10";
$result=mysqli_query($con,$sql);
echo "<table>";
echo "<tr><td> name </td><td> score </td></tr>";
// Start looping rows in mysql database.
while($rows=mysqli_fetch_array($result)){
echo "<tr><td>" . $rows['name'] . "</td><td>" . $rows['score'] . "</td><td>" . "</td></tr><br>";

// close while loop 
}
echo "</table>";
// close MySQL connection 
mysqli_close($con);
?>