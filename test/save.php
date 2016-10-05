<?php
    $myFile = 'general.json';
    $fh = fopen($myFile,'w') or die('cant open file');
    $tt = 'mali';
    fwrite($fh, $tt);
    fclose($fh);
?>