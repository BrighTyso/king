<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type:application/json");
header("Access-Control-Allow-Origin-Methods:POST");
header("Access-Control-Allow-Headers:Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Origin-Methods,Authorization,X-Requested-With");

require_once("conn.php");


$data = json_decode(file_get_contents("php://input"));

$response=array();

if (isset($data->description) && isset($data->userid)){

    $description=$data->description;
    $buyerid=$data->buyerid;



    $user_sql = "INSERT INTO buyer_grades(description,buyerid) VALUES ('$description',$buyerid)";
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