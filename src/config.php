<?php 

$app['debug'] 				= true;
// 2 hours
$app['memcache.expires'] 	= 7200;	
// Music ration is calculated like this: value * music.ratio
$app['music.ratio'] 		= 0.5;

//configure database connection
$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
    'db.options' => array(
        'driver' => 'pdo_mysql',
        'host' => '127.0.0.1',
        'dbname' => 'app',
        'user' => 'root',
        'password' => '',
        'charset' => 'utf8',
    ),
));
