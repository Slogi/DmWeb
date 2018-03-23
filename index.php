<?php

set_include_path("./src");


require_once("Router.php");

$router = new Router();
$router->main();