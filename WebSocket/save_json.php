<?php
/* stackoverflow.com/questions/20948155/simple-save-to-json-file-with-jquery */
  $name = $_REQUEST['name'];
  $myFile = 'general.json';

  $fh = fopen($myFile, 'w') or die('can't open file');
  
  $stringData = 'mali markop';
  fwrite($fh, $stringData);
  fclose($fh);


        echo 'Welcome '. $name;


?>