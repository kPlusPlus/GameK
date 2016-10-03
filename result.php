<?php
if( $_REQUEST["name"] ) {

    $name = $_REQUEST['name'];  

    $myfile = fopen("newfile.txt", "w") or die("Unable to open file!");

    fwrite($myfile, $name);
    fclose($myfile);
    
    echo "Welcome ". $name;
}

?>