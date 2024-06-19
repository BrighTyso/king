<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type:application/json");
header("Access-Control-Allow-Origin-Methods:POST");
header("Access-Control-Allow-Headers:Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Origin-Methods,Authorization,X-Requested-With");

require_once("conn.php");


$data = json_decode(file_get_contents("php://input"));

$response=array();

if (isset($data->userid) && isset($data->start_of_dayid) && isset($data->amount)){
    $balanced_balesid=$data->balanced_balesid;
    $internal_stop_order_amountid=$data->internal_stop_order_amountid;
    $userid=$data->userid;
    $quantity=$data->quantity;


    $user_sql = "INSERT INTO internal_stop_order_to_balanced_bales(internal_stop_order_amountid,userid,balanced_balesid,quantity) VALUES ($internal_stop_order_amountid,$userid,$balanced_balesid,$quantity)";
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