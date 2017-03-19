<?php


// get url
echo $_SERVER['REQUEST_URI'];
echo "<br>";


if ($_SERVER["HTTPS"] == "off")
{
    $url='https:\/\/libri.hr';
    echo '<META HTTP-EQUIV=REFRESH CONTENT="1; '.$url.'">';
}
?>