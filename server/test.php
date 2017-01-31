<?php
$str = "Is your name \x22ee\" O'Reilly?";

// Outputs: Is your name O\'Reilly?
echo addslashes($str);
echo ($str);


?>
