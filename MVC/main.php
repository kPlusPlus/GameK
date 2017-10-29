<?php
$model = new Model();
$controller = new Controller($model);
$view = new View($controller, $model);
echo $view->output();

?>