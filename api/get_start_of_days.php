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

if (isset($data->userid)){


    $sql = "Select start_of_day.id,start_of_day.description,amount,start_of_day.active from start_of_day join exchange_rate on start_of_day.id=exchange_rate.start_of_dayid order by start_of_day.id desc";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // output data of each row
        while($row = $result->fetch_assoc()) {
            // echo "id: " . $row["id"]. " - Name: " . $row["firstname"]. " " . $row["lastname"]. "<br>";

            $temp=array("id"=>$row["id"],"description"=>$row["description"],"amount"=>$row["amount"],"active"=>$row["active"]);
            array_push($response,$temp);

        }
    }



}


echo json_encode($response)



?>





