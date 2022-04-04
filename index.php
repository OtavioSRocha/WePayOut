<?php
require __DIR__ . "/inc/bootstrap.php";
ini_set('xdebug.var_display_max_depth', -1);
ini_set('xdebug.var_display_max_children', -1);
ini_set('xdebug.var_display_max_data', -1);
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode( 'index.php/', $uri );
$uri = explode( '/', $uri[1] );

if ((isset($uri[0]) && $uri[0] != 'payment') || !isset($uri[1])) {
    header("HTTP/1.1 404 Not Found");
    exit();
}
 
require PROJECT_ROOT_PATH . "/Controller/Api/PaymentController.php";

$objFeedController = new PaymentController();
$strMethodName = $uri[1];
$objFeedController->{$strMethodName}(isset($uri[2]) ? $uri[2] : null);
?>