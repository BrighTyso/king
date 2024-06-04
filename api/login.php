<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type:application/json");
header("Access-Control-Allow-Origin-Methods:POST");
header("Access-Control-Allow-Headers:Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Origin-Methods,Authorization,X-Requested-With");

require_once("conn.php");

$data = json_decode(file_get_contents("php://input"));

$username="";
$password="";
$found=0;



$response=array();

if (isset($data->username) && isset($data->password)){


    $temp = array("response" => "Failed");
    array_push($response, $temp);
}


echo json_encode("{response:3}");