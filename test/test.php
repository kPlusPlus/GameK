<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title></title>
    <script src="../vendor/components/jquery/jquery.min.js" type="text/javascript"></script>
</head>


<p> bla bla </p>
<button id="hide">Hide</button>
<button id="show">Show</button>

<?php
$book = array(
    "title" => "JavaScript: The Definitive Guide",
    "author" => "David Flanagan",
    "edition" => 6
);
?>
<script type="text/javascript">
var book = <?php echo json_encode($book, JSON_PRETTY_PRINT) ?>;
/* var book = {
    "title": "JavaScript: The Definitive Guide",
    "author": "David Flanagan",
    "edition": 6
}; */
alert(book.title);
</script>


</html>