<?php
include 'header.php';
$id = $_GET['id'];

$db->query("DELETE FROM Bookings WHERE property_id = '$id'");
$db->query("DELETE FROM Properties WHERE id = '$id'");



header("Location: http://qbnb.walklyapp.com/account.php");
die();
?>
