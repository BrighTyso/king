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
    $transporter_growersid=$data->transporter_growersid;
    $splitid=$data->splitid;

    $sql = "Select distinct sold_bales.id,sale_code,grower_num,bale_junus.transporter_growersid,splitid,lot,bale_group,barcode,price,buyer_grades.description as buyer_grade,mass , timb_grades.description as timb_grade from bale_junus join growers on growers.id=bale_junus.growerid join bale_mass on bale_mass.id=bale_junus.bale_massid join tickets on tickets.bale_junusid=bale_junus.id 
    left join sold_bales on sold_bales.ticketsid=tickets.id left join timb_grades on timb_grades.id=sold_bales.timb_gradesid left join buyer_grades on buyer_grades.id=sold_bales.buyer_gradesid where transporter_growersid=$transporter_growersid and splitid=$splitid";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // output data of each row
        while($row = $result->fetch_assoc()) {
            // echo "id: " . $row["id"]. " - Name: " . $row["firstname"]. " " . $row["lastname"]. "<br>";

            $temp=array("sale_code"=>$row["sale_code"],"soldbaleid"=>$row["id"],"transporter_growersid"=>$row["transporter_growersid"],"grower_num"=>$row["grower_num"],"mass"=>$row["mass"],"splitid"=>$row["splitid"],"lot"=>$row["lot"],"bale_group"=>$row["bale_group"],"buyer_grade"=>$row["buyer_grade"],"timb_grade"=>$row["timb_grade"],"barcode"=>$row["barcode"],"price"=>$row["price"]);

            array_push($response,$temp);

        }
    }

}




echo json_encode($response);