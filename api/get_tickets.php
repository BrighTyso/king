<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type:application/json");
header("Access-Control-Allow-Origin-Methods:POST");
header("Access-Control-Allow-Headers:Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Origin-Methods,Authorization,X-Requested-With");

require_once("conn.php");


$data = json_decode(file_get_contents("php://input"));

$response=array();


if (isset($data->userid)){

    $transporter_growersid=$data->transporter_growersid;

    $sql = "Select grower_num,transporter_growers.id,transporter_growers.junused_bales,lot,bale_group,mass,tickets.barcode,bale_mass.temp_bacode as temp_barcode from transporter_growers join growers on growers.id=transporter_growers.growerid join bale_junus on bale_junus.transporter_growersid=transporter_growers.id left join tickets on tickets.bale_junusid=bale_junus.id join bale_mass on bale_mass.id=bale_junus.bale_massid where junused_bales=transporter_growers.bales   and open_close=0";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // output data of each row
        while($row = $result->fetch_assoc()) {
            // echo "id: " . $row["id"]. " - Name: " . $row["firstname"]. " " . $row["lastname"]. "<br>";

            $temp=array("transporter_growersid"=>$row["id"],"grower_num"=>$row["grower_num"],"lot"=>$row["lot"],"bale_group"=>$row["bale_group"],"mass"=>$row["mass"],"barcode"=>$row["barcode"],"temp_barcode"=>$row["temp_barcode"]);

            array_push($response,$temp);


        }
    }

}




echo json_encode($response);