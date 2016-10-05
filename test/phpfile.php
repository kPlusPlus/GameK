<?php
$res = json_decode($_REQUEST['data'], true);
$res["php_message"] = "I am PHP";
echo json_encode($res);

$myFile = 'general.json';
$fh = fopen($myFile,'w') or die('cant open file');
$tt = json_encode($res);
fwrite($fh, $tt);
fclose($fh);

?>