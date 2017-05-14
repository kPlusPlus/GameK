<!DOCTYPE html>
<html>
<head>
	<title>Osvojili ste</title>
	<script type="text/javascript" src="../Scripts/jquery-3.1.1.js"></script>
      <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
      <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
      <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet"> 

<style type="text/css">
body, html {
    height: 100%;
}
.bg {
    /* The image used */
    background-image: url("background3.jpg");

    /* Full height */
    height: 100%;

    /* Center and scale the image nicely */
    background-position: center;
    background-repeat: no-repeat;
    background-size: cover;
}

.pozadina {
	font-family: Verdana, Geneva, sans-serif;
	background-image: url(background3.jpg);
	background-position: center center;
	background-repeat: no-repeat;	
	width:1000px;
	height:1000px;
}

.contest {
	font-family: 'Montserrat', sans-serif;
	background-color: #CC0;
	position: absolute;
	margin: auto;
	top: 0;
	right: 0;
	bottom: 0;
	left: 0;
	width: 350px;
	height: 300px;
	opacity: 0.8;
	filter: alpha(opacity=30);
}

tr:hover {background-color: #f5f5f5}
input:hover {background-color: #f5f5f5}

</style>
  <script>
  $( function() {
    $( "#termin" ).datepicker();
  } );
  </script>

</head>
<body>
<div class="bg"></div>
<div class="contest" id="contest">
<?php 
	//echo($_POST["ime"]); 
/*
$servername = "localhost";
$username = "root";
$password = "kreso1004";
$dbname = "juca";
*/

$servername = "mysql.hostinger.hr";
$username = "u771416193_admin";
$password = "kreso1004";
$dbname = "u771416193_juca";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "INSERT INTO juca.korisnik(
   id
  ,ime
  ,prezime
  ,telefon
  ,adresa
  ,FBID
  ,popust
  ,termin
) VALUES (
   NULL -- id - IN int(11)
  ,'".$_POST["ime"]."'  -- ime - IN varchar(60)
  ,'".$_POST["prezime"]."'  -- prezime - IN varchar(60)
  ,'".$_POST["telefon"]."'  -- telefon - IN varchar(60)
  ,'".$_POST["adresa"]."'  -- adresa - IN varchar(45)
  ,".$_POST["fbid"] ."  -- FBID - IN varchar(45)
  ,".$_POST["popust"]." -- popust - IN int(11)
  ,'".$_POST["termin"]."'
)";

if ($conn->query($sql) === TRUE) {
    echo "New record created successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>

<table>
	<td><h1>Rezervacija</h1></td>
	<tr>
		<td><label>Ime:</label></td> <td><input type="text" name="ime" id="ime" value="<?php echo $_POST["ime"]; ?>"></td>
	</tr>
	<tr>
		<td><label>Prezime:</label></td> <td><input type="text" name="prezime" id="prezime" value="<?php echo $_POST["prezime"]; ?>"></td>
	</tr>
    <tr>
		<td><label>Telefon:</label></td> <td><input type="text" name="telefon" id="telefon" value="<?php echo $_POST["telefon"]; ?>"></td>
    </tr>
    <tr>
		<td><label>Termin:</label></td> <td><input type="text" name="termin" id="termin" value="<?php echo $_POST["termin"]; ?>"></td>
    </tr>
        <tr>
		<td><label>Adresa:</label></td> <td><input type="text" name="termin" id="adresa" value="<?php echo $_POST["adresa"]; ?>"></td>
    </tr>
</table>
<input type="button" value="Pošalji" id="btnsend" name="btnsend">
</div>

</body>
</html>