<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type:application/json");
header("Access-Control-Allow-Origin-Methods:POST");
header("Access-Control-Allow-Headers:Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Origin-Methods,Authorization,X-Requested-With");

require_once("conn.php");
require "validate.php";




$data = json_decode(file_get_contents("php://input"));

$userid=$data->userid;
$sale_num=$data->sale_num;
$grower_num=$data->grower_num;
$lot=$data->lot;
$group=$data->group;
$mass=$data->mass;
$barcode=$data->temp_barcode;


if (isset($userid) && isset($sale_num) && isset($grower_num) && isset($lot) && isset($group) && isset($mass) && isset($barcode)) {
	// code...
}else{

	
}


echo json_encode($data); 

?>