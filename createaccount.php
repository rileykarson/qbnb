<?php
include 'header.php';

if (isset($_POST['email'])) {
	if (isset($_POST['email'])) {
		$user = $_POST['email'];
	} else {
		echo "Missing field.";
	}
	if (isset($_POST['password'])) {
		$password = $_POST['password'];
	} else {
		echo "Missing field.";
	}
	if (isset($_POST['phone_number'])) {
		$phone = $_POST['phone_number'];
	} else {
		echo "Missing field.";
	}
	if (isset($_POST['year'])) {
		$year = $_POST['year'];
	} else {
		echo "Missing field.";
	}
	if (isset($_POST['degree_type'])) {
		$degree = $_POST['degree_type'];
	} else {
		echo "Missing field.";
	}
	if (isset($_POST['faculty'])) {
		$faculty = $_POST['faculty'];
	} else {
		echo "Missing field.";
	}
	if (isset($_POST['name'])) {
		$name = $_POST['name'];
	} else {
		echo "Missing field.";
	}
	try {
		$query = "SELECT email FROM Members WHERE email = '$user'";
		$results = $db->query($query);
		if($results->rowCount() > 0) {
			echo $m->render('message', array('message'=>"Sorry, this username has already been taken! Please try again."));
		}
		else {
			$insert = "INSERT INTO Members (email, phone_number, year, degree_type,faculty,Name,Password,Admin) VALUES ('$user','$phone', '$year', '$degree', '$faculty','$name', '$password', '0')";
			$results = $db->query($insert);
			if($results->rowCount() > 0) {
				echo $m->render('message', array('message'=>"Account successfully created!"));
				session_start();
				$_SESSION['user'] = $user;
			}
		}
	}
	catch (PDOException $e) {
		echo $m->render('message', array('message'=>"Error!: ".$e->getMessage()."<br>"));
		die();
	}
}
$selectorvalues = array();
try {
	$query = "SELECT name, id FROM Faculty;";
	$results = $db->query($query);
	$arr = array();
	foreach ($results as $row) {
		$arr[] = $row;
	}
	$selectorvalues['faculty'] = $arr;
} catch (PDOException $e) {
		echo $m->render('message', array('message'=>"Error!: ".$e->getMessage()."<br>"));
		die();
}
try {
	$query = "SELECT name, id FROM Degree_types;";
	$results = $db->query($query);
	$arr = array();
	foreach ($results as $row) {
		$arr[] = $row;
	}
	$selectorvalues['degree_types'] = $arr;
} catch (PDOException $e) {
		echo $m->render('message', array('message'=>"Error!: ".$e->getMessage()."<br>"));
		die();
}
echo $m->render('createaccount', $selectorvalues);

include 'footer.php';
?>