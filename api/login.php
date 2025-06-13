<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type:application/json");
header("Access-Control-Allow-Origin-Methods:POST");
header("Access-Control-Allow-Headers:Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Origin-Methods,Authorization,X-Requested-With");

require_once("conn.php");

$data = json_decode(file_get_contents("php://input"));

$username="";
$password="";
$found=0;



$response=array();

if (isset($data->username) && isset($data->password)){


    $username=$data->username;
    $password=$data->password;

    $seasonid=0;
    $description="";


    $sql = "Select distinct id,description from seasons where active=1 limit 1";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // output data of each row
        while($row = $result->fetch_assoc()) {
            // echo "id: " . $row["id"]. " - Name: " . $row["firstname"]. " " . $row["lastname"]. "<br>";

            $seasonid=$row["id"];
            $description=$row["description"];

        }
    }



    $sql = "Select distinct * from users where username='$username' and password='$password' and active=1 limit 1";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // output data of each row
        while ($row = $result->fetch_assoc()) {

            $temp = array("response" => "success","id"=>$row["id"],"seasonid"=>$seasonid,"season"=>$description);
            array_push($response, $temp);
            // print('3');
        }
    }else{
        $temp = array("response" => "failed","id"=>"0");
        array_push($response, $temp);
    }
}


echo json_encode($response);