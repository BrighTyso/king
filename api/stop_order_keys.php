<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type:application/json");
header("Access-Control-Allow-Origin-Methods:POST");
header("Access-Control-Allow-Headers:Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Origin-Methods,Authorization,X-Requested-With");

require_once("conn.php");


$data = json_decode(file_get_contents("php://input"));

$response=array();

if (isset($data->vector) && isset($data->system_key) && isset($data->selling_pointid)){
    $vector=$data->vector;
    $system_key=$data->system_key;
    $selling_pointid=$data->selling_pointid;
    $found=0;


    $sql2 = "Select * from stop_order_keys where selling_pointid=$selling_pointid limit 1";
    $result2 = $conn->query($sql2);
    if ($result2->num_rows > 0) {
        // output data of each row
        while($row2 = $result2->fetch_assoc()) {
            // echo "id: " . $row["id"]. " - Name: " . $row["firstname"]. " " . $row["lastname"]. "<br>";

            $found=$row2["id"];

        }
    }



    if ($found==0){
        $user_sql = "INSERT INTO stop_order_keys(selling_pointid,system_key,vector) VALUES ($selling_pointid,'$system_key','$vector')";
        //$sql = "select * from login";
        if ($conn->query($user_sql)===TRUE) {

            $last_id = $conn->insert_id;
            $temp=array("response"=>"success");
            array_push($response,$temp);
        }else{
            $temp=array("response"=>"Failed");
            array_push($response,$temp);
        }

    }else{
        $temp=array("response"=>"key already created");
        array_push($response,$temp);
    }



}

echo json_encode($response);