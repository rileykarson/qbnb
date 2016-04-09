<?php
include 'header.php';

$comments = array();
$features = array();
$poi = array();
$array = array("Owner"=>"","Type"=>"","Address"=>"", "Points Of Interest"=>$poi,"Features"=>$features,"Comments"=>$comments, "Price"=>"",);
try {
		$db = new PDO('mysql:host=localhost;dbname=QBnB', "root", "shrekislife");
		$id = $_GET['id'];
		$query = "SELECT Members.id as memberid, Property_types.name as type, Properties.address as address, Properties.price as price, Districts.name as districtname from Members, Property_types, Properties, Districts where Properties.district_id = Districts.id AND Property_types.id= Properties.property_type AND Properties.owner_id = Members.id AND Properties.id = '$id'";
		$query2 = "SELECT Points_of_interest.name as poi FROM Points_of_interest, Districts, Properties where Districts.id = Points_of_interest.district_id AND Districts.id = Properties.district_id AND Properties.id = '$id'";
		$query3 = "SELECT DISTINCT Feature_types.name as features from Features, Feature_types, Properties where Features.feature_id = Feature_types.id AND Features.property_id = '$id'";
		$query4 = "SELECT Comments.comment_text as comments, Comments.property_rating as rating, Replies.comment_text AS reply from Comments, Properties, Replies where Properties.id = Comments.property_id AND Properties.id = '$id' AND Comments.id = Replies.comment_id;";
		$query5 = "SELECT Comments.comment_text as comments, Comments.property_rating as rating from Comments, Properties where Properties.id = Comments.property_id AND Properties.id = '$id' AND Comments.id NOT IN (SELECT Comments.id FROM Comments, Replies WHERE Comments.id = Replies.comment_id);";


		$results = $db->query($query);
		$results2 = $db->query($query2);
		$results3 = $db->query($query3);
		$results4 = $db->query($query4);
		$results5 = $db->query($query5);
		$tempcomm = array();
		$tempfeat = array();
		$temppoi = array();

		foreach($results2 as $row) {
			array_push($temppoi, array("poi" => $row['poi']));
		}
		if (!empty($temppoi)){
			array_push($array['Points Of Interest'], $temppoi);
		}

		foreach ($results3 as $row) {
			array_push($tempfeat, array("features" => $row['features']));
		}
		if (!empty($tempfeat)){
			array_push($array["Features"], $tempfeat);	
		}

		foreach($results4 as $row) {
			array_push($tempcomm, array("comments" => $row['comments'], "rating" => $row['rating'], "reply" => $row['reply']));
		}
		foreach($results5 as $row) {
			array_push($tempcomm, array("comments" => $row['comments'], "rating" => $row['rating'], "needsreply" => 'true'));
		}
		if (!empty($tempcomm)){
			array_push($array["Comments"], $tempcomm);	
		}

		foreach($results as $row) {
			$array["Owner"] = $row['memberid'];
			$array["Type"] = $row['type'];
			$array["Address"] = $row['address'];
			$array["Price"] = $row['price'];
			$array["DistrictName"] = $row['districtname'];
		}
	}
	catch(PDOException $e) {
		print "Error!: ".$e->getMessage()."<br>";
		die();
	 }
$id = $_GET['id'];
$ar = array();
$res = $db->query("SELECT start_date, end_date FROM Bookings WHERE property_id = '$id' ORDER BY start_date;");
foreach($res as $row) {
	$a = array();
	$a['start_date'] = $row['start_date'];
	$a['end_date'] = $row['end_date'];
	$ar[] = $a;
}
if (!empty($ar)){
	$array["Bookings"] = array($ar);
}

echo $m->render('property', $array);

include 'footer.php';
?>