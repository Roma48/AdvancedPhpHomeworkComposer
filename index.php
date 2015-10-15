<?php

require_once('route.php');

$route_config = array(
    'home' => 'Home',
    'logout' => 'Logout'
);
$route = new Router($route_config);
$route->run();