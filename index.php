<?php

set_include_path("./src");

require_once("model/SerieStorageImpl.php");
require_once("Connexion.php");
require_once("Router.php");


$database = new Connexion();
$db = $database->getConnection();

$r = new Router($db);

$r->main();








