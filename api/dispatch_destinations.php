<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type:application/json");
header("Access-Control-Allow-Origin-Methods:POST");
header("Access-Control-Allow-Headers:Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Origin-Methods,Authorization,X-Requested-With");

require_once("conn.php");


$data = json_decode(file_get_contents("php://input"));

$response=array();
if (isset($data->userid)) {

    $userid= $data->userid;
    $selling_point_dispatchid= $data->selling_point_dispatchid;
    $driver_name= $data->driver_name;
    $driver_surname= $data->driver_surname;
    $driver_id_number= $data->driver_id_number;
    $horse_num= $data->horse_num;
    $trailer_num= $data->trailer_num;
    $created_at= $data->created_at;



    $user_sql = "INSERT INTO dispatch_destination(userid,selling_point_dispatchid,driver_name,driver_surname,driver_id_number,horse_num,trailer_num,created_at) VALUES ($userid,$selling_point_dispatchid,'$driver_name','$driver_surname','$driver_id_number','$horse_num','$trailer_num','$created_at')";
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