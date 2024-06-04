<?php
require_once("conn.php");


if(isset($_POST["seasonid"]) && isset($_POST["name"])  && isset($_POST["id_num"]) && isset($_POST["truck_num"]) && isset($_POST["location"]) && isset($_POST["created_at"])){


    $userid=$_POST["userid"];
    $seasonid=$_POST["seasonid"];
    $name=$_POST["name"];
    $id_num=$_POST["id_num"];
    $truck_num=$_POST["truck_num"];
    $location=$_POST["location"];
    $created_at=$_POST["created_at"];



    $user_sql = "INSERT INTO transporter(userid,seasonid,name,id_num,truck_num,location,created_at) VALUES ($userid,$seasonid,'$name','$id_num','$truck_num','$location','$created_at')";
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