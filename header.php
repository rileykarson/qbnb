<?php
require __DIR__ . '/vendor/autoload.php';
session_start();
$m = new Mustache_Engine(array(
	'loader' => new Mustache_Loader_FilesystemLoader(dirname(__FILE__) . '/views'),
	));

$db = new PDO('mysql:host=localhost;dbname=QBnB', "root", "shrekislife");
$header = array();
if (isset($_SESSION['user'])) {
	$useremail = $_SESSION['user'];
	$query = "SELECT Name, Admin FROM Members WHERE email = '$useremail'";
	$results = $db->query($query);
	$name = "";
	$admin = false;
	foreach ($results as $row){
		$name = $row['Name'];
		$admin = $row['Admin'];
	}
	$header['email'] = $name;	
	$header['admin'] = $admin;
}
echo $m->render('header', $header);

?>