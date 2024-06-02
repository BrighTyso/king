<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type:application/json");
header("Access-Control-Allow-Origin-Methods:POST");
header("Access-Control-Allow-Headers:Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Origin-Methods,Authorization,X-Requested-With");

require_once("conn.php");

$data = json_decode(file_get_contents("php://input"));

$name="";
$created_at="";
$found=0;

$response=array();

if (isset($data->transporter_growersid) && isset($data->start_lot) && isset($data->end_lot)){

    $transporter_growersid=$data->transporter_growersid;
    $start_lot=$data->start_lot;
    $end_lot=$data->end_lot;
    $splitid=$data->splitid;
    $userid=$data->userid;


    $user_sql1 = "update bale_junus set splitid=$splitid where (lot between '$start_lot' and '$end_lot') and transporter_growersid=$transporter_growersid";
    //$sql = "select * from login";
    if ($conn->query($user_sql1)===TRUE) {

        $last_id = $conn->insert_id;
        $temp = array("response" => "success");
        array_push($response, $temp);


    }else{
        $temp=array("response"=>$conn->error);
        array_push($response,$temp);

    }



}else{

    $temp=array("response"=>"Field Empty");
    array_push($response,$temp);
}


echo json_encode($response)



?>





