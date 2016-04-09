<?php
include 'header.php';

if (isset($_POST['start_date'])){

	$useremail = $_SESSION['user'];
	$query = "SELECT id FROM Members WHERE email = '$useremail'";
	$results = $db->query($query);
	$memberid = "";
	foreach ($results as $row){
		$memberid = $row['id'];
	}
	$propertyid = 1;
	if (isset($_GET['id'])) {
		$propertyid = $_GET['id'];
	}

	if(isset($_POST['start_date'])) {
		$temp= strtotime($_POST['start_date']);
		$start = date('Y-m-d H:i:s', $temp);
	}
	else {
		echo "try again";
	}
	if(isset($_POST['end_date'])) {
		$temp= strtotime($_POST['end_date']);
		$end = date('Y-m-d H:i:s', $temp);
	}
	else {
		echo "try again";
	}
	try {
		$query = "SELECT Bookings.id FROM Bookings WHERE Bookings.property_id = $propertyid AND  '$start' < Bookings.end_date AND '$end' > Bookings.start_date";
		$res = $db->query($query);
		if($res->rowCount() > 0) {
			echo $m->render('message', array('message'=>"Booking failed. The dates you selected overlap with a booking that already exists! Please try again."));
		}
		else {
			$insert = "INSERT INTO Bookings (start_date, member_id, property_id, status_id, end_date) VALUES ('$start', '$memberid', '$propertyid', '1', '$end')";
			$results = $db->query($insert);
			if($results->rowCount() > 0) {
				echo $m->render('message', array('message'=>"Booking added successfully."));
			}
			else {
				echo $m->render('message', array('message'=>"Booking failed. Please try again!"));
			}
		}
		
	}
	catch(PDOException $e) {
		print "Error!: ".$e->getMessage()."<br>";
		die();
	}
}
echo $m->render('createbooking', array());

include 'footer.php';
?>