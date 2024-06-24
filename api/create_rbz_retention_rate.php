<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type:application/json");
header("Access-Control-Allow-Origin-Methods:POST");
header("Access-Control-Allow-Headers:Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Origin-Methods,Authorization,X-Requested-With");

require_once("conn.php");


$data = json_decode(file_get_contents("php://input"));

$response=array();

if (isset($data->usd_percent) && isset($data->other_currency_percent)){

    $usd_percent=$data->usd_percent;
    $other_currency_percent=$data->other_currency_percent;



    $user_sql = "INSERT INTO rbz_retention_rate(usd_percent,other_currency_percent) VALUES ($usd_percent,$other_currency_percent)";
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