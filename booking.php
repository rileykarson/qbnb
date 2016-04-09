<?php
include 'header.php';

if(!isset($_SESSION['user'])){
	die();
}
if(!isset($_GET['id'])){
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
$bookingquery = "SELECT start_date, address, property_id, status_id, end_date, Bookings.id, owner_id, member_id FROM Bookings, Properties WHERE property_id = Properties.id AND Bookings.id = '$id'";
$results = $db->query($bookingquery);
$arr = array();
$rentee = 0;
$property = 0;
foreach ($results as $row){
	$arr['start_date'] = $row['start_date'];
	$arr['address'] = $row['address'];
	$arr['property_id'] = $row['property_id'];
	$property = $row['property_id'];
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
	$rentee = $row['member_id'];
}

if ($memberid == $arr['owner_id']) {
	$arr['owner'] = 1;
} else if ($memberid == $arr['member_id']) {
	$arr['booker'] = 1;
}

$comments = "SELECT Comments.comment_text, property_rating, Replies.comment_text AS reply, Comments.id FROM Comments, Replies WHERE Comments.id = Replies.comment_id AND member_id = '$rentee' AND property_id = '$property'";
$comments2 = "SELECT Comments.comment_text, Comments.property_rating , Comments.id from Comments where member_id = '$rentee' AND property_id = '$property' AND Comments.id NOT IN (SELECT Comments.id FROM Comments, Replies WHERE Comments.id = Replies.comment_id);";
$result = $db->query($comments);
$comment = "";
$rating = -1;
$reply = "";
$commentid = -1;
foreach($result as $row){
	$comment = $row['comment_text'];
	$commentid = $row['id'];
	$rating = $row['property_rating'];
	if (isset($row['reply'])) {
		$reply = $row['reply'];
	}
}
$needsreply = false;
if ($comment == "") {
	$result2 = $db->query($comments2);
	foreach($result2 as $row){
		$comment = $row['comment_text'];
		$commentid = $row['id'];
		$rating = $row['property_rating'];
		$needsreply = true;
	}
}

if ($comment != "") {
	$c = array("comment" => $comment, "rating" => $rating, "comment_id" => $commentid);
	if (reply != "") {
		$c['reply'] = $reply;
	}
	if ($needsreply) {
		$c['needsreply'] = true;
	}
	$arr["Comments"] = array($c);
}
echo $m->render('booking', $arr);

include 'footer.php';
?>