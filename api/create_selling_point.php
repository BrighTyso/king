<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type:application/json");
header("Access-Control-Allow-Origin-Methods:POST");
header("Access-Control-Allow-Headers:Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Origin-Methods,Authorization,X-Requested-With");

require_once("conn.php");


$data = json_decode(file_get_contents("php://input"));

$response=array();

if (isset($data->name) && isset($data->floor_code) && isset($data->floor_id)){
    $name=$data->name;
    $floor_code=$data->floor_code;
    $floor_id=$data->floor_id;


    $user_sql = "INSERT INTO selling_point(name,floor_code,floor_id) VALUES ('$name','$floor_code','$floor_id')";
    //$sql = "select * from login";
    if ($conn->query($user_sql)===TRUE) {

        $last_id = $conn->insert_id;
        $temp=array("response"=>"success");
        array_push($response,$temp);
    }else{
        $temp=array("response"=>"Failed");
        array_push($response,$temp);
    }


}




echo json_encode($response);