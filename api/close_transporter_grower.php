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

if (isset($data->description) && isset($data->userid) && isset($data->seasonid)){

    $description=$data->description;
    $seasonid=$data->seasonid;
    $userid=$data->userid;


    $user_sql2 = "update transporter_growers set junused_bales=junused_bales+1 where id = $transporter_growersid";
    //$sql = "select * from login";
    if ($conn->query($user_sql2)===TRUE) {

        $last_id = $conn->insert_id;
        $temp=array("response"=>"success");
        array_push($response,$temp);
    }else{
            $temp=array("response"=>"failed");
            array_push($response,$temp);

        }



}else{

    $temp=array("response"=>"Field Empty");
    array_push($response,$temp);
}


echo json_encode($response)



?>





