<?php
$res = json_decode($_REQUEST['data'], true);
$res["php_message"] = "kRace";
echo json_encode($res);

$myFile = 'player2.json';
$fh = fopen($myFile,'w') or die('cant open file');
$tt = json_encode($res);
fwrite($fh, $tt);
fclose($fh);

?>