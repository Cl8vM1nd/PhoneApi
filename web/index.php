<?php

$classLoader = require_once __DIR__.'/../vendor/autoload.php';

$app = new Base\Application($classLoader, $_SERVER['HTTP_HOST']);

$app->setup();

$app->run();
