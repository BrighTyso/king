<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type:application/json");
header("Access-Control-Allow-Origin-Methods:POST");
header("Access-Control-Allow-Headers:Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Origin-Methods,Authorization,X-Requested-With");

require_once("conn.php");


$data = json_decode(file_get_contents("php://input"));

$response=array();

if (isset($data->name) && isset($data->seasonid) && isset($data->truck_num)){

    $userid=$data->userid;
    $seasonid=$data->seasonid;
    $name=$data->name;
    $id_num=$data->id_num;
    $truck_num=$data->truck_num;
    $location=$data->location;
    $created_at=$data->created_at;


    $user_sql = "INSERT INTO transporter(userid,seasonid,name,id_num,truck_num,location,created_at) VALUES ($userid,$seasonid,'$name','$id_num','$truck_num','$location','$created_at')";
    //$sql = "select * from login";
    if ($conn->query($user_sql)===TRUE) {
        $last_id = $conn->insert_id;
        $temp=array("response"=>"success","id"=>$last_id);
        array_push($response,$temp);
    }else{
        $temp=array("response"=>"Failed");
        array_push($response,$temp);
    }


}




echo json_encode($response);