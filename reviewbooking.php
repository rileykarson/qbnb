<?php
include 'header.php';

if (!isset($_GET['id'])) {
	die();
}
$id = $_GET['id'];

$email = $_SESSION['user'];
$idquery = "SELECT id FROM Members WHERE email = '$email'";
$results = $db->query($idquery);
$memberid = 0;
foreach ($results as $row) {
	$memberid = $row['id'];
}

if (isset($_POST['value'])) {
	$value = $_POST['value'];
	if ($value == 0) {
		$db->query("DELETE FROM Bookings WHERE id = '$id'");
	} else {
			$db->query("UPDATE Bookings SET status_id = 2 WHERE id = '$id'");
	}
	header("Location: http://qbnb.walklyapp.com/account.php");
	die();
}

$bookingquery = "SELECT start_date, address, property_id, status_id, end_date, Bookings.id, owner_id, member_id FROM Bookings, Properties WHERE property_id = Properties.id AND Bookings.id = '$id'";
$results = $db->query($bookingquery);
$arr = array();
foreach ($results as $row){
	$arr['start_date'] = $row['start_date'];
	$arr['address'] = $row['address'];
	$arr['property_id'] = $row['property_id'];
	$status = "Error";
	if ($row['status_id'] == 1) {
		$status = "Unconfirmed";
	} else {
		$status = "Confirmed";
	}
	$arr['status'] = $status;
	$arr['end_date'] = $row['end_date'];
	$arr['id'] = $row['id'];
	$arr['owner_id'] = $row['owner_id'];
	$arr['member_id'] = $row['member_id'];
}


echo $m->render('review', $arr);

include 'footer.php';
?>
