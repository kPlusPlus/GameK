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
  margin: 0;
  font: 400 15px/1.8 "Lato", sans-serif;
  color: #777;
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

tr:hover {background-color: #f5f5f5;}
input:hover {background-color: #f5f5f5;}
input:focus {background-color: ##d1ea8a;}

</style>
  <script type="text/javascript">
  $( function() {
    $( "#termin" ).datepicker();
  } );

$( function() {
  $("#btnsend").click(function() {
      // verifikacija obaveznih polja

      forma.submit();
  	});
} ); 

 
  
  

 </script>

</head>
<body>
<div class="bg"></div>
<div class="contest" id="contest">
<form action="post.php" method="POST" id="forma">
<table>
	<td><h1>Rezervacija</h1></td>
	<tr>
		<td><label>Ime:</label></td> <td><input type="text" name="ime" id="ime"></td>
	</tr>
	<tr>
		<td><label>Prezime:</label></td> <td><input type="text" name="prezime" id="prezime"></td>
	</tr>
    <tr>
		<td><label>Telefon:</label></td> <td><input type="text" name="telefon" id="telefon"></td>
    </tr>
    <tr>
		<td><label>Termin:</label></td> <td><input type="text" name="termin" id="termin"></td>
    </tr>
    <tr>
		<td><label>Adresa:</label></td> <td><input type="text" name="adresa" id="adresa"></td>
    </tr>
    <tr>
        <td><label>FBID:</label></td><td><input type="text" name="fbid" id="fbid" /></td>
    </tr>
</table>
<input type="button" value="Pošalji" id="btnsend" name="btnsend">
</form>
</div>






</body>
</html>