<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
	$hostname_mySql = "localhost";
	$database_mySql = "juca";
	$username_mySql = "root";
	$password_mySql = "kreso1004";
	$mySql = mysql_pconnect($hostname_mySql, $username_mySql, $password_mySql) or trigger_error(mysql_error(),E_USER_ERROR); 
	
?>