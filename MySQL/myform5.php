<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title></title>
	<link rel="stylesheet" href="">
</head>
<body>
	
	<?php
	if (isset($_POST["submit"]))
	{
		if (isset($_POST["firstname"]))
			echo("First name: " . $_POST['firstname'] . "<br />\n");
		if (isset($_POST["lastname"]))
			echo("Last name: " . $_POST['lastname'] . "<br />\n");
	}
	?>

</body>
</html>