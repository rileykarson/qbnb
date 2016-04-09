<?php
include 'header.php';
$return = array("Name"=>"", "Email"=>"", "Phone Number"=>"", "Faculty"=>"", "Degree"=>"", "Title"=>"","Properties"=>"");
if(isset($_GET['id'])){
	$memberid = $_GET['id'];
	try {
		$query = "SELECT Members.name as name, email, phone_number, Faculty.name as faculty, Degree_types.name as degree from Members, Faculty, Degree_types WHERE Degree_types.id = Members.degree_type AND Faculty.id = Members.faculty AND Members.id = '$memberid'";
		$results = $db->query($query);

		foreach($results as $stuff){
			$return["Name"] = $stuff["name"];
			$return["Email"] = $stuff["email"];
			$return["Phone Number"] = $stuff["phone_number"];
			$return["Faculty"] = $stuff["faculty"];
			$return["Degree"] = $stuff["degree"];
		}

		$query3 = "SELECT address from Properties where Properties.owner_id = '$memberid'";
		$results1 = $db->query($query3);
		if($results1->rowCount() == 0){
					//do nothing
		}
		else{
			$name = $return["Name"];
			$return["Title"] = "$name's Properties";
			foreach($results1 as $prop){
				$properties[] = $prop;
			}
			$return["Properties"] = $properties;
		}
	}
	catch (PDOException $e) {
		print "Error!: ".$e->getMessage()."<br>";
		die();
	}
}
echo $m->render('profile', $return);

include 'footer.php';
?>