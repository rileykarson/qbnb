<?php
include 'header.php';

$array = array();
try {
	//Property Information
	$property = array();
	$query = "SELECT Properties.id as id, Members.id as ownerid, Members.name as ownername, Property_types.name as type, Properties.address as address, Properties.price as price, Districts.name as districtname from Members, Property_types, Properties, Districts where Properties.district_id = Districts.id AND Property_types.id= Properties.property_type AND Properties.owner_id = Members.id";
	$result = $db->query($query);
	foreach($result as $row) {
		$property[$row['id']]['id'] = $row['id'];
		$property[$row['id']]['ownerid'] = $row['ownerid'];
		$property[$row['id']]['type'] = $row['type'];
		$property[$row['id']]['address'] = $row['address'];
		$property[$row['id']]['price'] = $row['price'];
		$property[$row['id']]['districtname'] = $row['districtname'];
		$property[$row['id']]['ownername'] = $row['ownername'];
	}

	$points = array();
	$query2 = "SELECT Points_of_interest.name as poi, Properties.id as id FROM Points_of_interest, Districts, Properties where Districts.id = Points_of_interest.district_id AND Districts.id = Properties.district_id";
	$result = $db->query($query2);
	foreach($result as $row){
		$points[$row['id']]['poi'][] = $row['poi'];
		$points[$row['id']]['id'] = $row['id'];
	}
	foreach($points as $row){
		$property[$row['id']]['POI'] = array($row['poi']); 
	}

	$features = array();
	$query3 = "SELECT Feature_types.name as features, Properties.id as id from Features, Feature_types, Properties WHERE Features.feature_id = Feature_types.id AND Features.property_id = Properties.id;";
	$res = $db->query($query3);
	foreach($res as $row){
		$features[$row['id']]['features'][] = $row['features'];
		$features[$row['id']]['id'] = $row['id'];
	}
	foreach($features as $row){
		$property[$row['id']]['Features'] = array($row['features']); 
	}
	$arrr = array();
	foreach($property as $row) {
		$arrr[] = $row;
	}
	$array['Properties'] = array($arrr);
	}
	catch(PDOException $e) {
		print "Error!: ".$e->getMessage()."<br>";
		die();
	 }
$l = array();
$r = $db->query("SELECT name as feature FROM Feature_types;");
foreach($r as $row) {
	$l[] = $row;
}
$g = array();
$r = $db->query("SELECT DISTINCT Name, Members.id FROM Members, Properties WHERE Members.id = Properties.owner_id;");
foreach($r as $row) {
	$g[] = $row;
}
$e = array();
$r = $db->query("SELECT DISTINCT price FROM Properties;");
foreach($r as $row) {
	$e[] = $row;
}
$array["PriceList"] = $e;
$array["MemberList"] = $g;
$array["FeatureList"] = $l;
$array['JSON'] = json_encode($array);
echo $m->render('propertylist', $array);

include 'footer.php';
?>