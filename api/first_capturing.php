<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type:application/json");
header("Access-Control-Allow-Origin-Methods:POST");
header("Access-Control-Allow-Headers:Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Origin-Methods,Authorization,X-Requested-With");

require_once("conn.php");
require "validate.php";




$data = json_decode(file_get_contents("php://input"));

$userid=$data->userid;
$price=$data->price;
$buyerid=$data->buyerid;
$buyer_mark=$data->buyer_mark;
$timb_gradeid=$data->timb_gradeid;
$description=$data->description;

if (isset($userid) && isset($price) && isset($buyerid) && isset($buyer_mark) && isset($timb_gradeid) && isset($description)) {
	// code...
}else{

}

echo json_encode($data);  

?>