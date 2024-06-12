<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type:application/json");
header("Access-Control-Allow-Origin-Methods:POST");
header("Access-Control-Allow-Headers:Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Origin-Methods,Authorization,X-Requested-With");

require_once("conn.php");

$data = json_decode(file_get_contents("php://input"));

$response=array();

if(isset($data->barcode)) {


    $userid = $data->userid;
    $barcode = $data->barcode;

    $ticketsid = 0;


    $sql = "Select bale_group,lot,splitid,tickets.id,mass,grower_num from tickets join bale_junus on bale_junus.id=tickets.bale_junusid join bale_mass on bale_mass.id=bale_junus.bale_massid join transporter_growers on transporter_growers.id=bale_junus.transporter_growersid join growers on growers.id=transporter_growers.growerid where barcode='$barcode'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // output data of each row
        while ($row = $result->fetch_assoc()) {
            // echo "id: " . $row["id"]. " - Name: " . $row["firstname"]. " " . $row["lastname"]. "<br>";
            $ticketsid = $row["id"];
            $temp=array("lot"=>$row["lot"],"group"=>$row["bale_group"],"splitid"=>$row["splitid"],"id"=>$row["id"],"mass"=>$row["mass"],"grower_num"=>$row["grower_num"]);

            array_push($response,$temp);
        }
    }





}



echo json_encode($response);