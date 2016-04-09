<?php
include 'header.php';

if (isset($_POST['comment_text'])){
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
	$commenttext = $_POST['comment_text'];
	$rating = $_POST['rating'];
	$result = $db->query("INSERT INTO Comments (member_id, property_id, property_rating, comment_text, owner_rating) VALUES ('$memberid', '$propertyid', '$rating', '$commenttext', 5);");
	header("Location: http://qbnb.walklyapp.com/booking.php?id=$propertyid");
	die();
}
echo $m->render('addcomment', array());

include 'footer.php';
?>