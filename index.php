<?php
require __DIR__ . "/inc/bootstrap.php";
require PROJECT_ROOT_PATH . "/Controller/Api/PaymentController.php";
require PROJECT_ROOT_PATH . "/Controller/Api/ScheduleController.php";

ini_set('xdebug.var_display_max_depth', -1);
ini_set('xdebug.var_display_max_children', -1);
ini_set('xdebug.var_display_max_data', -1);

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode( 'index.php/', $uri );
$uri = explode( '/', $uri[1] );

if ((isset($uri[0]) && ($uri[0] != 'payment' && $uri[0] != 'schedule')) || !isset($uri[1])) {
    header("HTTP/1.1 404 Not Found");
    exit();
}

switch($uri[0]) {
    case 'payment':
        $objFeedController = new PaymentController();
        break;
    case 'schedule':
        $objFeedController = new ScheduleController();
}

$strMethodName = $uri[1];
$objFeedController->{$strMethodName}(isset($uri[2]) ? $uri[2] : null);
?>