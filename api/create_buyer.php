<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type:application/json");
header("Access-Control-Allow-Origin-Methods:POST");
header("Access-Control-Allow-Headers:Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Origin-Methods,Authorization,X-Requested-With");

require_once("conn.php");


$data = json_decode(file_get_contents("php://input"));

$response=array();

if (isset($data->name) && isset($data->buyer_code) && isset($data->selling_pointid)){
    $name=$data->name;
    $buyer_code=$data->buyer_code;
    $selling_pointid=$data->selling_pointid;


    $user_sql = "INSERT INTO buyer(selling_pointid,name,buyer_code) VALUES ('$selling_pointid','$name','$buyer_code')";
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