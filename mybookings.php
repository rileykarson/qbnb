<?php
include 'header.php';

if(!isset($_SESSION['user'])){
	die();
}
$email = $_SESSION['user'];
$idquery = "SELECT id FROM Members WHERE email = '$email'";
$results = $db->query($idquery);
$memberid = 0;
foreach ($results as $row) {
	$memberid = $row['id'];
}
$bookingquery = "SELECT start_date, address, property_id, status_id, end_date, Bookings.id FROM Bookings, Properties WHERE property_id = Properties.id AND member_id = '$memberid' ORDER BY start_date";
$results = $db->query($bookingquery);
$ay = array();
foreach ($results as $row){
	$arr = array();
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
	$ay[] = $arr;
}
if (!empty($ay)) {
	$array["Bookings"] = array($ay);
}

$ownerbookings = "SELECT start_date, address, property_id, status_id, end_date, Bookings.id FROM Bookings, Properties WHERE property_id = Properties.id AND owner_id = '$memberid' ORDER BY start_date";
$results = $db->query($ownerbookings);
$ayy = array();
foreach ($results as $row){
	$arr = array();
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
	$ayy[] = $arr;
}
if (!empty($ayy)) {
	$array["Owned"] = array($ayy);
}
echo $m->render('mybookings', $array);

include 'footer.php';
?>