<?php
session_start();
$db = new PDO('mysql:host=localhost;dbname=QBnB', "root", "shrekislife");
if (isset($_POST['email'])) {
	if (isset($_POST['email'])) {
		$user = $_POST['email'];
	} else {
		echo "Missing field.";
	}
	if (isset($_POST['Password'])) {
		$password = $_POST['Password'];
	} else {
		echo "Missing field.";
	}
	try {
		$query = "SELECT email FROM Members WHERE email='$user'";
		$results = $db->query($query);
		if($results->rowCount() == 0) {
			echo $m->render('message', array('message'=>"No user with that email!"));
		} else {
			$query2 = "SELECT Password FROM Members WHERE email='$user'";
			$results2 = $db->query($query2);
			foreach ($results2 as $row) {
				if ($password == $row["Password"]) {
					$_SESSION['user'] = $user;
				}
				else {
					echo $m->render('message', array('message'=>"Incorrect Password!"));
				}
			}
		}
	}
	catch (PDOException $e) {
		print "Error!: ".$e->getMessage()."<br>";
		die();
	}
}

include 'header.php';

$arr = array('loggedin' => false);
if (isset($_SESSION['user'])) {
	$arr['loggedin'] = true;
}

echo $m->render('index', $arr);

include 'footer.php';
?>
