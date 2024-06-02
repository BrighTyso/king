<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type:application/json");
header("Access-Control-Allow-Origin-Methods:POST");
header("Access-Control-Allow-Headers:Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Origin-Methods,Authorization,X-Requested-With");

require_once("conn.php");
//require "validate.php";


$data = json_decode(file_get_contents("php://input"));

$userid=$data->userid;
$description=$data->description;

if (isset($userid) && $description=="") {
	// code...

}else if (isset($userid) && $description!="") {
	// code...

}


echo json_encode($data); 



?>