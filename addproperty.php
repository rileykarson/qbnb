<?php
include 'header.php';
$featureOne = "";
$featureTwo = "";
$featureThree = "";
if (isset($_POST['address'])) {
	if (isset($_POST['address'])) {
		$address = $_POST['address'];
	} else {
		echo "Missing field.";
	}
	if (isset($_POST['price'])) {
		$price = $_POST['price'];
	} else {
		echo "Missing field.";
	}
	if (isset($_POST['property_type'])) {
		$property_type = $_POST['property_type'];
	} else {
		echo "Missing field.";
	}
	if (isset($_POST['district'])) {
		$district = $_POST['district'];
	} else {
		echo "Missing field.";
	}
	if (isset($_POST['features-1'])) {
		$featureOne = $_POST['features-1'];
	}
	if (isset($_POST['features-2'])) {
		$featureTwo = $_POST['features-2'];
	}
	if (isset($_POST['features-3'])) {
		$featureThree = $_POST['features-3'];
	}

	try {
		$query = "SELECT address FROM Properties WHERE address = '$address'";
		$results = $db->query($query);
		if($results->rowCount() > 0) {
			echo $m->render('message', array('message'=>"Sorry, this property already exists!"));
		}
		else {
			$user_email = $_SESSION['user'];
			$query2 = "SELECT id FROM Members WHERE email='$user_email'";
			$results2 = $db->query($query2);
			$id="";
			foreach ($results2 as $row){
				$id = $row['id'];
			}

			$insert = "INSERT INTO Properties (owner_id, property_type, district_id, address, price) VALUES ('$id','$property_type', '$district', '$address', '$price')";
			$results = $db->query($insert);

			$query2 = "SELECT id FROM Properties WHERE address='$address'";
			$results2 = $db->query($query2);
			$prop_id="";
			foreach ($results2 as $row2){
				$prop_id = $row2['id'];
			}
			if ($featureOne != ""){
				$q = "INSERT INTO Features (property_id, feature_id) VALUES ('$prop_id','$featureOne')";
				$r = $db->query($q);
			}
			if ($featureTwo != ""){
				$q = "INSERT INTO Features (property_id, feature_id) VALUES ('$prop_id','$featureTwo')";
				$r = $db->query($q);
			}
			if ($featureThree != ""){
				$q = "INSERT INTO Features (property_id, feature_id) VALUES ('$prop_id','$featureThree')";
				$r = $db->query($q);
			}

			if($results->rowCount() > 0) {
				echo $m->render('message', array('message'=>"Property successfuly added!"));
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
	$query = "SELECT name, id FROM Property_types;";
	$results = $db->query($query);
	$arr = array();
	foreach ($results as $row) {
		$arr[] = $row;
	}
	$selectorvalues['property_types'] = $arr;
} catch (PDOException $e) {
		echo $m->render('message', array('message'=>"Error!: ".$e->getMessage()."<br>"));
		die();
}
try {
	$query = "SELECT name, id FROM Districts;";
	$results = $db->query($query);
	$arr = array();
	foreach ($results as $row) {
		$arr[] = $row;
	}
	$selectorvalues['district'] = $arr;
} catch (PDOException $e) {
		echo $m->render('message', array('message'=>"Error!: ".$e->getMessage()."<br>"));
		die();
}

try {
	$query = "SELECT name, id FROM Feature_types;";
	$results = $db->query($query);
	$arr = array();
	foreach ($results as $row) {
		$arr[] = $row;
	}
	$selectorvalues['features'] = $arr;
} catch (PDOException $e) {
		echo $m->render('message', array('message'=>"Error!: ".$e->getMessage()."<br>"));
		die();
}



echo $m->render('addproperty', $selectorvalues);

include 'footer.php';
?>