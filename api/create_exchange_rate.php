<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type:application/json");
header("Access-Control-Allow-Origin-Methods:POST");
header("Access-Control-Allow-Headers:Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Origin-Methods,Authorization,X-Requested-With");

require_once("conn.php");


$data = json_decode(file_get_contents("php://input"));

$response=array();

if (isset($data->userid) && isset($data->start_of_dayid) && isset($data->amount)){
    $amount=$data->amount;
    $start_of_dayid=$data->start_of_dayid;
    $userid=$data->userid;
    $seasonid=$data->seasonid;


    $user_sql = "INSERT INTO exchange_rate(userid,start_of_dayid,seasonid,amount) VALUES ($userid,$start_of_dayid,$seasonid,'$amount')";
    //$sql = "select * from login";
    if ($conn->query($user_sql)===TRUE) {

        $last_id = $conn->insert_id;
        $temp=array("response"=>"success");
        array_push($response,$temp);
    }else{
        $temp=array("response"=>$conn->error);
        array_push($response,$temp);
    }


}




echo json_encode($response);