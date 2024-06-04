<?php
require_once("conn.php");

$response=array();

if(isset($_POST["username"]) && isset($_POST["password"])){

    $username=$_POST["username"];
    $password=$_POST["password"];

    $sql = "Select distinct * from users where username='$username' and password='$password' and active=1";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // output data of each row
        while ($row = $result->fetch_assoc()) {

            $temp = array("response" => "success","id"=>$row["id"]);
            array_push($response, $temp);
            // print('3');
        }
    }else{
        $temp = array("response" => "failed","id"=>"0");
        array_push($response, $temp);
    }


}


echo json_encode($response);