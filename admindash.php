<?php
include 'header.php';
if(!empty($_POST['adminUpdate'])) {

	$email = $_POST['adminUpdate'];
	$query = "UPDATE Members SET Admin = 1 WHERE email = '$email'";
	$results = $db->query($query);
	if($results->rowCount() > 0) {
		echo $m->render('message', array('message'=>"User $email has been made an admin!"));
	}
	else {
		$checkadmin = "SELECT Name from Members where email = '$email'";
		$check = $db->query($checkadmin);
		foreach ($check as $row) {
			$admin = $row['Admin'];
		}
		if ($check->rowCount() > 0) {
			echo $m->render('message', array('message'=>"This user is already an admin!"));
		}
		if ($check->rowCount() == 0) {
			echo $m->render('message', array('message'=>"This email address does not belong to any user!"));
		}

	}

}
if(!empty($_POST['deleteProp'])) {
	$propertyID = $_POST['deleteProp'];
	$queryName = "SELECT address from Properties where id='$propertyID'";
	$query = "DELETE from Properties where id='$propertyID'";
	$resultsname = $db->query($queryName);
	foreach($resultsname as $row) {
		$name = $row['address'];
	}

	$results = $db->query($query);

	if($results->rowCount() >0) {
		echo $m->render('message', array('message'=>"$name has been deleted!"));
		//echo $name." has been deleted!";
	}
	else {
		echo $m->render('message', array('message'=>"That property does not exist!"));
	}
}


if(!empty($_POST['deleteUser'])) {

	$userID = $_POST['deleteUser'];
	$queryName = "SELECT name from Members where id='$userID'";
	$query1 = "DELETE FROM Properties where owner_id='$userID'";
	$query = "DELETE FROM Members where id = '$userID'";
	$resultsname = $db->query($queryName);
	foreach($resultsname as $row) {
		$name = $row['name'];
	}
	$results1 = $db->query($query1);
	$results = $db->query($query);

	if($results->rowCount() > 0 && $results1->rowCount() > 0) {
		//echo "User ".$name." has been deleted!";
		echo $m->render('message', array('message'=>"User $name has been deleted!"));
	}
	else {
		echo $m->render('message', array('message'=>"This user does not exist!"));
	}

}

if(!empty($_POST['avgProp'])) {
	$propertyID = $_POST['avgProp'];
	$queryName = "SELECT address FROM Properties WHERE id='$propertyID'";
	$query = "SELECT AVG(property_rating) FROM Comments, Properties where Comments.property_id = Properties.id AND Properties.id = '$propertyID'";

	$resultsname = $db->query($queryName);
	foreach ($resultsname as $row) {
		$name = $row['address'];
	}
	$results = $db->query($query);
	if($results->rowCount() > 0) {
		foreach ($results as $row) {
			$average = $row['AVG(property_rating)'];
		}
		if($average > 0) {
			echo $m->render('message', array('message'=>"The average ratings of $name is $average"));
		}
		else if($average==0) {
			$propertyExists = "SELECT address from Properties where Properties.id = '$propertyID'";
			$checkProp = $db->query($propertyExists);
			if($checkProp->rowCount() > 0) {
				foreach ($checkProp as $row) {
					$a = $row['address'];
				}
				echo $m->render('message', array('message'=>"$a has not yet been rated!"));
			}
			else {
				echo $m->render('message', array('message'=>"This property does not exist!"));
			}
		}
		//echo "The average ratings of ".$name." is ".$average;
	}
	else {
		echo $m->render('message', array('message'=>"Something went wrong!"));
	}
}

if(!empty($_POST['avgOwner'])) {
	$id = $_POST['avgOwner'];
	$queryName = "SELECT Name FROM Members, Properties where Members.id = '$id'";

	$query = "SELECT AVG(property_rating) from Comments,Properties where Comments.property_id = Properties.id and Properties.owner_id = '$id'";


	$resultsname = $db->query($queryName);
	foreach ($resultsname as $row) {
		$name = $row['Name'];
	}
	$results = $db->query($query);
	if($results->rowCount() >0) {
		foreach ($results as $row) {
			$average = $row['AVG(property_rating)'];
		}
		if($average > 0) {
			echo $m->render('message', array('message'=>"The average ratings of $name is $average"));
		}
		else if($average == 0) {
			$propertyExists = "SELECT Name from Members where Members.id = '$id'";
			$checkProp = $db->query($propertyExists);
			foreach($checkProp as $row) {
				$member = $row['Name'];
			}
			if($checkProp->rowCount() > 0) {
				echo $m->render('message', array('message'=>"$member has not yet been rated!"));
			}
			else {
				echo $m->render('message', array('message'=>"This user does not exist!"));
			}
		}
	}
	else {
		echo $m->render('message', array('message'=>"Something went wrong!"));
		
	}
}

/// VIEW THE BOOKINGS OF A USER
if(!empty($_POST['userBook'])) {
	$id = $_POST['userBook'];

	$queryName = "SELECT Name from Members where id = '$id'";
	$query = "SELECT Properties.address as address, Bookings.start_date as start, Bookings.end_date as end from Bookings, Properties where Bookings.property_id = Properties.id and Bookings.member_id = '$id'";


	$resultsname = $db->query($queryName);
	foreach($resultsname as $row) {
		$name = $row['Name'];
	}
	$results = $db->query($query);

	foreach ($results as $row) {
		$add = $row['address'];
		$start = $row['start'];
		$end = $row['end'];
		$string .= "Address: ".$add."     Start Date: ".$start."     End Date: ".$end."<br>";
	}
	if($results->rowCount() > 0 ) {	
		echo $m->render('message', array('message'=>"Showing bookings for user $name: <br> $string"));
	}
	else{
		echo $m->render('message', array('message'=>"$name has not booked any properties!"));
	}
	
}

if(!empty($_POST['propBook'])) {
	$id = $_POST['propBook'];

	$query = "SELECT Properties.address as address, Bookings.start_date as start, Bookings.end_date as end FROM Properties, Bookings WHERE Properties.id = Bookings.property_id AND Properties.id = '$id'";


	$results = $db->query($query);
	$string = "";
	foreach ($results as $row) {
		$add = $row['address'];
		$start = $row['start'];
		$end = $row['end'];
		$string .= " Start Date: ".$start."  End Date: ".$end."<br>";
	}
	if($results->rowCount() > 0){
		echo $m->render('message', array('message'=>"Showing bookings for: $add <br> $string"));
	}
	else {
		echo $m->render('message', array('message'=>"There are no bookings associated with this property!"));
	}
}


///VIEW THE BOOKINGS OF AN OWNER
$property = array();
if(!empty($_POST['ownerBook'])) {
	$id = $_POST['ownerBook'];
	$property =array("Add"=>$address, "Strt"=>$start, "EndDate"=>$end);
	$queryName = "SELECT Name from Members where id='$id'";
	$query = "SELECT Properties.address as address, Bookings.start_date as start, Bookings.end_date as end FROM Properties, Bookings WHERE Properties.id = Bookings.property_id AND Properties.owner_id = '$id'";

	$results = $db->query($query);
	$nameresults = $db->query($queryName);
	foreach ($nameresults as $row) {
		$Name = $row['Name'];
	}

	$string = "";
	foreach ($results as $row) {
		$add = $row['address'];
		$strt = $row['start'];
		$end = $row['end'];
		$string .= "Address: ".$add."     Start Date: ".$strt."     End Date: ".$end."<br>";
	}
	if ($results->rowCount() > 0) {
		echo $m->render('message', array('message'=>"$Name's following properties have been booked: <br> $string"));
	}
	else {
		echo $m->render('message', array('message'=>"$Name's properties do not have bookings!"));
	}
}


echo $m->render('admindash', array());
include 'footer.php';
?>