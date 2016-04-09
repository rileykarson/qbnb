<?php
include 'header.php';

$return = array("address"=>"", "price"=>"", "property_type"=>"", "district"=>"", "current"=>"", "features"=>"", "district_list"=>"", "types_list"=>"");
$user_id="";
$id = $_GET['id'];
if(isset($_SESSION['user'])){
	$email = $_SESSION['user'];
	try {
	
		$query = "SELECT address, price, Property_types.name as prop, Districts.name as dis FROM Properties, Property_types, Districts WHERE Properties.id = '$id' AND Properties.property_type = Property_types.id AND Properties.district_id = Districts.id";
		$results = $db->query($query);

		foreach($results as $stuff){
			$return["address"] = $stuff["address"];
			$return["price"] = $stuff["price"];
			$return["property_type"] = $stuff["prop"];
			$return["district"] = $stuff["dis"];
		}

		$query3 = "SELECT DISTINCT name, id from Feature_types, Features WHERE Features.property_id = '$id' AND Features.feature_id = Feature_types.id";
		$results3 = $db->query($query3);
		$feature_list = array();
		foreach($results3 as $row3){
			$feature_list[] = $row3;
		}
		$return["current"] = $feature_list;


		$query2 = "SELECT id FROM Members WHERE email='$email'";
		$results2 = $db->query($query2);
		$user_id="";
		foreach($results2 as $row){
			$user_id = $row['id'];
		}
	}catch (PDOException $e){
		//
	}
}

if (isset($_POST['submit'])) 
{ 

	if (isset($_POST['address'])) {
		$address = $_POST['address'];
		if ($address != ""){
			$query4 = "UPDATE Properties SET address='$address' WHERE id='$id'";
			$results4 = $db->query($query4);
			if($results4->rowCount() > 0) {
				echo $m->render('message', array('message'=>"Address successfuly edited!"));
			}
			$return["address"]=$address;

		}
	} 
	if (isset($_POST['price'])) {
		$price = $_POST['price'];
		if ($price != ""){
			$query5 = "UPDATE Properties SET price='$price' WHERE id='$id'";
			$results5 = $db->query($query5);
			if($results5->rowCount() > 0) {
				echo $m->render('message', array('message'=>"Price successfuly edited!"));
			}
			$return["price"]=$price;
		}
	} 
	if (isset($_POST['property_type'])) {
		$property_type = $_POST['property_type'];
		if ($property_type != ""){
			$query6 = "UPDATE Properties SET property_type='$property_type' WHERE id='$id'";
			$results6 = $db->query($query6);
			if($results6->rowCount() > 0) {
				echo $m->render('message', array('message'=>"Property Type successfuly edited!"));
			}
			$query = "SELECT name from Property_types WHERE id = '$property_type'";
			$results = $db->query($query);
			$prop = "";
			foreach ($results as $a){
				$prop = $a['name'];
			}

			$return["property_type"]=$prop;
		}
	} 
	if (isset($_POST['district'])) {
		$district = $_POST['district'];
		$query7 = "UPDATE Properties SET district_id='$district' WHERE id='$id'";
		$results7 = $db->query($query7);
		if($results7->rowCount() > 0) {
			echo $m->render('message', array('message'=>"District successfuly edited!"));
		}

		$query = "SELECT name from Districts WHERE id = '$district'";
		$results = $db->query($query);
		$dis = "";
		foreach ($results as $a){
			$dis = $a['name'];
		}

		$return["district"]=$dis;
	} 
	if (isset($_POST['add'])) {
		$lis = $_POST['add'];
		foreach ($lis as $l){
			$query8 = "INSERT INTO Features(property_id, feature_id) VALUES ('$id', '$l')";
			$results8 = $db->query($query8);
		}

		if($results8->rowCount() > 0) {
			echo $m->render('message', array('message'=>"Features added successfuly!"));
		}

		$query3 = "SELECT DISTINCT name, id from Feature_types, Features WHERE Features.property_id = '$id' AND Features.feature_id = Feature_types.id";
		$results3 = $db->query($query3);
		$feature_list = array();
		foreach($results3 as $row3){
			$feature_list[] = $row3;
		}
		$return["current"] = $feature_list;
	} 
	if (isset($_POST['remove'])) {
		$lis = $_POST['remove'];
		foreach ($lis as $l){
			$query8 = "DELETE FROM Features WHERE property_id = '$id' AND feature_id='$l'";
			$results8 = $db->query($query8);
		}

		if($results8->rowCount() > 0) {
			echo $m->render('message', array('message'=>"Features deleted successfuly!"));
		}

		$query3 = "SELECT DISTINCT name, id from Feature_types, Features WHERE Features.property_id = '$id' AND Features.feature_id = Feature_types.id";
		$results3 = $db->query($query3);
		$feature_list = array();
		foreach($results3 as $row3){
			$feature_list[] = $row3;
		}
		$return["current"] = $feature_list;
	} 

}

try {
	$query = "SELECT name, id FROM Property_types;";
	$results = $db->query($query);
	$arr = array();
	foreach ($results as $row) {
		$arr[] = $row;
	}
	$return['types_list'] = $arr;
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
	$return['district_list'] = $arr;
} catch (PDOException $e) {
		echo $m->render('message', array('message'=>"Error!: ".$e->getMessage()."<br>"));
		die();
}

try {
	$query = "SELECT DISTINCT name, id FROM Feature_types;";
	$results = $db->query($query);
	$arr = array();
	foreach ($results as $row) {
		$arr[] = $row;
	}
	$return['features'] = $arr;
} catch (PDOException $e) {
		echo $m->render('message', array('message'=>"Error!: ".$e->getMessage()."<br>"));
		die();
}

$return["pid"] = $id;

echo $m->render('editproperty', $return);

include 'footer.php';
?>