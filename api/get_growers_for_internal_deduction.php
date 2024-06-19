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


    $sql = "Select distinct grower_num,growers.name,growers.surname,bale_junus.transporter_growersid,splitid,selling_point.name as selling_point_name,growers.id as growerid,bale_junus.created_at from bale_junus join growers on growers.id=bale_junus.growerid join selling_point on selling_point.id=growers.selling_pointid";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // output data of each row
        while($row = $result->fetch_assoc()) {
            // echo "id: " . $row["id"]. " - Name: " . $row["firstname"]. " " . $row["lastname"]. "<br>";

            $transporter_growersid =$row["transporter_growersid"];
            $splitid=$row["splitid"];
            $already_balanced="NO";
            $created_at=$row["created_at"];

            $sql1 = "Select distinct grower_num,bale_junus.transporter_growersid,splitid,lot,bale_group,barcode,price,buyer_grades.description as buyer_grade , timb_grades.description as timb_grade from bale_junus join growers on growers.id=bale_junus.growerid join tickets on tickets.bale_junusid=bale_junus.id 
            left join sold_bales on sold_bales.ticketsid=tickets.id left join timb_grades on timb_grades.id=sold_bales.timb_gradesid left join buyer_grades on buyer_grades.id=sold_bales.buyer_gradesid where transporter_growersid=$transporter_growersid and splitid=$splitid";
            $result1 = $conn->query($sql1);

            $sql2 = "Select * from balanced_bales where splitid=$splitid and transporter_growersid=$transporter_growersid limit 1";
            $result2 = $conn->query($sql2);
            if ($result2->num_rows > 0) {
                // output data of each row
                while($row2 = $result2->fetch_assoc()) {
                    // echo "id: " . $row["id"]. " - Name: " . $row["firstname"]. " " . $row["lastname"]. "<br>";
                    $already_balanced="YES";

                }
            }


            $temp=array("already_balanced"=>$already_balanced,"transporter_growersid"=>$row["transporter_growersid"],"grower_num"=>$row["grower_num"],"splitid"=>$row["splitid"],"name"=>$row["name"],"surname"=>$row["surname"],"number_of_bales"=>$result1->num_rows,"selling_point_name"=>$row["selling_point_name"],"growerid"=>$row["growerid"],"created_at"=>$row["created_at"]);

            array_push($response,$temp);


        }
    }



}




echo json_encode($response);