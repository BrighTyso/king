<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type:application/json");
header("Access-Control-Allow-Origin-Methods:POST");
header("Access-Control-Allow-Headers:Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Origin-Methods,Authorization,X-Requested-With");

require_once("conn.php");


$data = json_decode(file_get_contents("php://input"));

$response=array();



if (isset($data->userid)){
    $growers=0;
    $bales=0;
    $id=0;

   // $sql = "Select grower_num,transporter_growers.id,transporter_growers.junused_bales,lot,bale_group,mass,tickets.barcode,bale_mass.temp_bacode as temp_barcode from transporter_growers join growers on growers.id=transporter_growers.growerid join bale_junus on bale_junus.transporter_growersid=transporter_growers.id left join tickets on tickets.bale_junusid=bale_junus.id join bale_mass on bale_mass.id=bale_junus.bale_massid where junused_bales=transporter_growers.bales   and open_close=0 and transporter_growersid=$transporter_growersid";
    $sql="select transporter.id,transporter.name,bales,junused_bales,transporter.created_at,id_num, truck_num, location from transporter join transporter_growers on transporter_growers.transporterid=transporter.id  order by transporter.id desc ";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // output data of each row
        while($row = $result->fetch_assoc()) {
            // echo "id: " . $row["id"]. " - Name: " . $row["firstname"]. " " . $row["lastname"]. "<br>";
            $growers=0;
            $bales=0;
            $id=$row["id"];

            $sql1 = "Select distinct growerid from transporter_growers  where transporterid=$id";
            $result1 = $conn->query($sql1);
            $growers=$result1->num_rows;

            $sql1 = "Select  bales from transporter_growers  where transporterid=$id";
            $result1 = $conn->query($sql1);
            if ($result1->num_rows > 0) {
                // output data of each row
                while($row1 = $result1->fetch_assoc()) {
                    // echo "id: " . $row["id"]. " - Name: " . $row["firstname"]. " " . $row["lastname"]. "<br>";
                    $bales+=$row1["bales"];

                }
            }



            $temp=array("id"=>$row["id"],"name"=>$row["name"],"junused_bales"=>$row["junused_bales"],"created_at"=>$row["created_at"],"id_num"=>$row["id_num"],"truck_num"=>$row["truck_num"],"location"=>$row["location"],"growers"=>$growers,"bales"=>$bales);

            array_push($response,$temp);


        }
    }

}




echo json_encode($response);