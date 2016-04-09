<?php
require __DIR__ . '/vendor/autoload.php';

$m = new Mustache_Engine(array(
	'loader' => new Mustache_Loader_FilesystemLoader(dirname(__FILE__) . '/views'),
));
echo $m->render('header', array());
//echo $m->render('index', array());
echo $m->render('footer', array());
?>
