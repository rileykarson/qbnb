<?php
include 'header.php';

if (isset($_GET['id'])) {
	$id = $_GET['id'];
	$db->query("DELETE FROM Bookings WHERE id = '$id'");
}

header("Location: http://qbnb.walklyapp.com/mybookings.php");
die();
?>
