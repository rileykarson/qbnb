<?php
include 'header.php';
$return = array("Name"=>"", "Email"=>"", "Phone Number"=>"", "Faculty"=>"", "Degree"=>"", "Title"=>"","Properties"=>"", "Degree_types"=>"", "Faculties"=>"");
$user_id="";
if(isset($_SESSION['user'])){
	$email = $_SESSION['user'];
	try {
		$query = "SELECT Members.name as name, email, phone_number, Faculty.name as faculty, Degree_types.name as degree from Members, Faculty, Degree_types WHERE Degree_types.id = Members.degree_type AND Faculty.id = Members.faculty AND Members.email = '$email'";
		$results = $db->query($query);

		foreach($results as $stuff){
			$return["Name"] = $stuff["name"];
			$return["Email"] = $stuff["email"];
			$return["Phone Number"] = $stuff["phone_number"];
			$return["Faculty"] = $stuff["faculty"];
			$return["Degree"] = $stuff["degree"];
		}

		$query2 = "SELECT id FROM Members WHERE email='$email'";
		$results2 = $db->query($query2);
		$user_id="";
		foreach($results2 as $row){
			$user_id = $row['id'];
		}

		$query3 = "SELECT address, id from Properties where Properties.owner_id = '$user_id'";
		$results1 = $db->query($query3);
		if($results1->rowCount() == 0){
					//do nothing
		}
		else{
			$return["Title"] = "My Properties";
			$properties = array();
			foreach($results1 as $prop){
				$properties[] = $prop;
			}
			$return["Properties"] = array($properties);
		}
	}
	catch (PDOException $e) {
		print "Error!: ".$e->getMessage()."<br>";
		die();
	}
}

try {
	$query = "SELECT name, id FROM Faculty;";
	$results = $db->query($query);
	$arr = array();
	foreach ($results as $row) {
		$arr[] = $row;
	}
	$return['Faculties'] = $arr;
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
	$return['Degree_types'] = $arr;
} catch (PDOException $e) {
		echo $m->render('message', array('message'=>"Error!: ".$e->getMessage()."<br>"));
		die();
}



if (isset($_POST['submit'])) 
{ 

	if (isset($_POST['name'])) {
		$name = $_POST['name'];
		if ($name != ""){
			$query4 = "UPDATE Members SET name='$name' WHERE id='$user_id'";
			$results4 = $db->query($query4);
			if($results4->rowCount() > 0) {
				echo $m->render('message', array('message'=>"Name successfuly edited!"));
			}
			$return["Name"]=$name;

		}
	} 
	if (isset($_POST['email'])) {
		$email = $_POST['email'];
		if ($email != ""){
			$query5 = "UPDATE Members SET email='$email' WHERE id='$user_id'";
			$results5 = $db->query($query5);
			if($results5->rowCount() > 0) {
				echo $m->render('message', array('message'=>"Email successfuly edited!"));
			}
			$return["Email"]=$email;
		}
	} 
	if (isset($_POST['phone'])) {
		$phone = $_POST['phone'];
		if ($phone != ""){
			$query6 = "UPDATE Members SET phone_number='$phone' WHERE id='$user_id'";
			$results6 = $db->query($query6);
			if($results6->rowCount() > 0) {
				echo $m->render('message', array('message'=>"Phone Number successfuly edited!"));
			}
			$return["Phone Number"]=$phone;
		}
	} 
	if (isset($_POST['faculty'])) {
		$faculty = $_POST['faculty'];
		$query7 = "UPDATE Members SET faculty='$faculty' WHERE id='$user_id'";
		$results7 = $db->query($query7);
		if($results7->rowCount() > 0) {
			echo $m->render('message', array('message'=>"Faculty successfuly edited!"));
		}

		$query = "SELECT name from Faculty WHERE Faculty.id = '$faculty'";
		$results = $db->query($query);
		$fac = "";
		foreach ($results as $a){
			$fac = $a['name'];
		}

		$return["Faculty"]=$fac;
	} 
	if (isset($_POST['degree_type'])) {
		$degree = $_POST['degree_type'];
		$query8 = "UPDATE Members SET degree_type='$degree' WHERE id='$user_id'";
		$results8 = $db->query($query8);
		if($results8->rowCount() > 0) {
			echo $m->render('message', array('message'=>"Degree successfuly edited!"));
		}

		$query = "SELECT name from Degree_types WHERE id = '$degree'";
		$results = $db->query($query);
		$deg = "";
		foreach ($results as $b){
			$deg = $b['name'];
		}

		$return["Degree"]=$deg;
	} 

}


echo $m->render('account', $return);
include 'footer.php';
?>