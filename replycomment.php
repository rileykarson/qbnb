<?php
include 'header.php';

$cid = 0;
if (isset($_GET['id'])) {
	$cid = $_GET['id'];
}

if (isset($_POST['comment_text'])){
	$useremail = $_SESSION['user'];
	$query = "SELECT id FROM Members WHERE email = '$useremail'";
	$results = $db->query($query);
	$memberid = "";
	foreach ($results as $row){
		$memberid = $row['id'];
	}
	$cid = $_POST['id'];
	$commenttext = $_POST['comment_text'];
	$propertyid = 0;
	$res = $db->query("SELECT property_id FROM Comments WHERE id = '$cid'");
	foreach ($res as $row) {
		$propertyid = $row['property_id'];
	}
	$result = $db->query("INSERT INTO Replies (comment_id, comment_text) VALUES ('$cid', '$commenttext');");
	header("Location: http://qbnb.walklyapp.com/booking.php?id=$propertyid");
	die();
}
echo $m->render('replycomment', array("id" => $cid));

include 'footer.php';
?>