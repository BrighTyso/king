<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type:application/json");
header("Access-Control-Allow-Origin-Methods:POST");
header("Access-Control-Allow-Headers:Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Origin-Methods,Authorization,X-Requested-With");

require_once("conn.php");


$data = json_decode(file_get_contents("php://input"));

$response=array();

$found=0;

if (isset($data->temp_bacode) && isset($data->mass) && isset($data->created_at)){

    $userid=$data->userid;
    $seasonid=$data->seasonid;
    $temp_bacode=$data->temp_bacode;
    $mass=$data->mass;
    $created_at=$data->created_at;



    $sql = "Select * from bale_mass where temp_bacode='$temp_bacode'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // output data of each row
        while($row = $result->fetch_assoc()) {
            // echo "id: " . $row["id"]. " - Name: " . $row["firstname"]. " " . $row["lastname"]. "<br>";

            $found=$row["id"];


        }
    }



    if ($found==0){

        $user_sql = "INSERT INTO bale_mass(userid,seasonid,temp_bacode,mass,created_at) VALUES ($userid,$seasonid,'$temp_bacode',$mass,'$created_at')";
        //$sql = "select * from login";
        if ($conn->query($user_sql)===TRUE) {

            $last_id = $conn->insert_id;
            $temp=array("response"=>"success");
            array_push($response,$temp);
        }else{
            $temp=array("response"=>"failed");
            array_push($response,$temp);
        }

    }else{

        $temp=array("response"=>"already used");
        array_push($response,$temp);

    }





}




echo json_encode($response);