<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type:application/json");
header("Access-Control-Allow-Origin-Methods:POST");
header("Access-Control-Allow-Headers:Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Origin-Methods,Authorization,X-Requested-With");

require_once("conn.php");


$data = json_decode(file_get_contents("php://input"));

$response=array();
if (isset($data->userid)) {

    $userid = $data->userid;
    $buyerid = $data->buyerid;
    $destination = $data->destination;
    $found=0;

    $sql = "Select distinct * from selling_point_dispatch where buyerid=$buyerid and destination='$destination'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // output data of each row
        while ($row = $result->fetch_assoc()) {

            $found = $row["id"];
        }
    }

    if ($found>0){

        $user_sql = "INSERT INTO selling_point_dispatch(userid,buyerid,destination) VALUES ($userid,$buyerid,'$destination')";
        //$sql = "select * from login";
        if ($conn->query($user_sql)===TRUE) {

            $last_id = $conn->insert_id;
            $temp=array("response"=>"success");
            array_push($response,$temp);
        }else{
            $temp=array("response"=>"Failed");
            array_push($response,$temp);
        }


    } else{
            $temp=array("response"=>"Failed");
            array_push($response,$temp);
        }



}


echo json_encode($response);