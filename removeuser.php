<?php
include 'header.php';

$useremail = $_SESSION['user'];
$query = "SELECT id FROM Members WHERE email = '$useremail'";
$results = $db->query($query);
$memberid = "";
foreach ($results as $row){
	$memberid = $row['id'];
}

$db->query("DELETE FROM Bookings WHERE member_id = '$memberid'");
$db->query("DELETE FROM Properties WHERE owner_id = '$memberid'");
$db->query("DELETE FROM Members WHERE id = '$memberid'");

header("Location: http://qbnb.walklyapp.com/logout.php");
die();
?>
