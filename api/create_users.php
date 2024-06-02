<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type:application/json");
header("Access-Control-Allow-Origin-Methods:POST");
header("Access-Control-Allow-Headers:Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Origin-Methods,Authorization,X-Requested-With");

require_once("conn.php");


$data = json_decode(file_get_contents("php://input"));

$response=array();

if (isset($data->userid) && isset($data->password) && isset($data->username)){
    $username=$data->username;
    $password=md5($data->password);
    $userid=$data->userid;

    $all=$data->all;
    $transporter_receiving=$data->transporter_receiving;
    $scale=$data->scale;
    $junus=$data->junus;
    $buyer=$data->buyer;
    $balance=$data->balance;
    $salessheets=$data->salessheets;
    $accounts=$data->accounts;
    $transporter_payments=$data->transporter_payments;
    $created_at=$data->created_at;




    $user_sql = "INSERT INTO users(username,password) VALUES ('$username','$password')";
    //$sql = "select * from login";
    if ($conn->query($user_sql)===TRUE) {

        $last_id = $conn->insert_id;

        $user_sql = "INSERT INTO rights(userid,created_byid,admin,transporter_receiving,scale,junus,buyer,balance,salessheets,accounts,transporter_payments,created_at ) VALUES ($last_id,$userid,$all,$transporter_receiving,$scale,$junus,$buyer,$balance,$salessheets,$accounts,$transporter_payments,''$created_at'')";
        //$sql = "select * from login";
        if ($conn->query($user_sql)===TRUE) {
            $temp = array("response" => "success");
            array_push($response, $temp);
        }

    }else{
        $temp=array("response"=>"Failed");
        array_push($response,$temp);
    }


}




echo json_encode($response);