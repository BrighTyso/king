<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type:application/json");
header("Access-Control-Allow-Origin-Methods:POST");
header("Access-Control-Allow-Headers:Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Origin-Methods,Authorization,X-Requested-With");

require_once("conn.php");


$data = json_decode(file_get_contents("php://input"));

$response=array();


if (isset($data->userid)){

    $userid=$data->userid;

    $sql = "Select grower_num,transporter_growers.id,transporter_growers.junused_bales from transporter_growers join growers on growers.id=transporter_growers.growerid where junused_bales=bales and open_close=0";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // output data of each row
        while($row = $result->fetch_assoc()) {
            // echo "id: " . $row["id"]. " - Name: " . $row["firstname"]. " " . $row["lastname"]. "<br>";

            $temp=array("transporter_growersid"=>$row["id"],"grower_num"=>$row["grower_num"],"bales"=>$row["junused_bales"]);

            array_push($response,$temp);


        }
    }



}




echo json_encode($response);