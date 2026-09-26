<?php
require_once __DIR__ . '/core/Autoloader.php';
use App\Core\Autoloader;
$loader = new Autoloader();
$loader->addNamespace('App', __DIR__);
$loader->register();
return $loader;
