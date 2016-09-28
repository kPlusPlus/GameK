
<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

$db = "sakila";//Your database name
$dbu = "kreso";//Your database username
$dbp = "kreso1004";//Your database users' password
$host = "localhost";//MySQL server - usually localhost

$dblink = mysqli_connect($host,$dbu,$dbp);
$seldb = mysqli_select_db($dblink, $db);


//$name = test_input($_POST["name"]);
//echo $name;
//if(isset($_GET['name']) && isset($_GET['score'])){
if ($_SERVER["REQUEST_METHOD"] == "POST") {


     if(isset($_REQUEST["name"])) 
          $name = $_REQUEST['name'];
     if(isset($_REQUEST["score"]))
          $score = $_REQUEST['score'];

     //Lightly sanitize the GET's to prevent SQL injections and possible XSS attacks
     $name = strip_tags(mysqli_real_escape_string($dblink, $name));
     $score = strip_tags(mysqli_real_escape_string($dblink, $score));


     //$sql = mysqli_query($dblink, "INSERT INTO `$db`.`scores` (`id`,`name`,`score`) VALUES ('','$name','$score');");
     $query = "INSERT INTO `$db`.`scores` (`name`,`score`) VALUES ('" . $name ."','" . $score . "');";
     $sql = mysqli_query($dblink, $query );

     //echo $query;

     if($sql){

          //The query returned true - now do whatever you like here.
          echo 'Your score was saved. Congrats!';
          
     }else{

          //The query returned false - you might want to put some sort of error reporting here. Even logging the error to a text file is fine.
          echo 'There was a problem saving your score. Please try again later.';
          
     }
     
}else{
     echo 'Your name or score wasnt passed in the request. Make sure you add ?name=NAME_HERE&score=1337 to the tags.';
}

mysqli_close($dblink);//Close off the MySQL connection to save resources.


function test_input($data) {
   $data = trim($data);
   $data = stripslashes($data);
   $data = htmlspecialchars($data);
   return $data;
}

?>