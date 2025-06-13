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
    $sale_batchid=0;


    $sql = "Select distinct grower_num,growers.name,growers.surname,bale_junus.transporter_growersid,splitid,selling_point.name as selling_point_name,growers.id as growerid from bale_junus join growers on growers.id=bale_junus.growerid join selling_point on selling_point.id=growers.selling_pointid";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // output data of each row
        while($row = $result->fetch_assoc()) {
            // echo "id: " . $row["id"]. " - Name: " . $row["firstname"]. " " . $row["lastname"]. "<br>";
            $sale_batchid=0;
            $transporter_growersid =$row["transporter_growersid"];
            $splitid=$row["splitid"];
            $already_balanced="NO";
            $lot="";
            $bale_group="";
            $barcode="";
            $price="";
            $bale_mass="";
            $timb_grade="";
            $buyer_grade="";
            $temp_barcodes="";
            $location="";
            $created_at="";

            $sql1 = "Select distinct location,grower_num,bale_junus.transporter_growersid,splitid,lot,bale_group,barcode,price,buyer_grades.description as buyer_grade,mass,temp_bacode, timb_grades.description as timb_grade,bale_junus.created_at from bale_junus join growers on growers.id=bale_junus.growerid join tickets on tickets.bale_junusid=bale_junus.id 
            left join sold_bales on sold_bales.ticketsid=tickets.id left join timb_grades on timb_grades.id=sold_bales.timb_gradesid left join buyer_grades on buyer_grades.id=sold_bales.buyer_gradesid join bale_mass on bale_mass.id=bale_junus.bale_massid join transporter_growers on transporter_growers.id=bale_junus.transporter_growersid join transporter on transporter.id=transporter_growers.transporterid where transporter_growersid=$transporter_growersid and splitid=$splitid";
            $result1 = $conn->query($sql1);
            if ($result1->num_rows > 0) {
                // output data of each row
                while($row1 = $result1->fetch_assoc()) {
                    // echo "id: " . $row["id"]. " - Name: " . $row["firstname"]. " " . $row["lastname"]. "<br>";
                    $lot=$row1['lot'];
                    $bale_group=$row1['bale_group'];
                    $barcode=$row1['barcode'];
                    $price=$row1['price'];
                    $bale_mass=$row1['mass'];
                    $timb_grade=$row1['timb_grade'];
                    $buyer_grade=$row1['buyer_grade'];
                    $temp_barcodes=$row1['temp_bacode'];
                    $location=$row1['location'];
                    $created_at=$row1['created_at'];

                }
            }


            $sql2 = "Select * from balanced_bales where splitid=$splitid and transporter_growersid=$transporter_growersid limit 1";
            $result2 = $conn->query($sql2);
            if ($result2->num_rows > 0) {
                // output data of each row
                while($row2 = $result2->fetch_assoc()) {
                    // echo "id: " . $row["id"]. " - Name: " . $row["firstname"]. " " . $row["lastname"]. "<br>";
                    $already_balanced="YES";
                    $sale_batchid=$row2["sale_batchid"];

                }
            }




            $temp=array("created_at"=>$created_at,"location"=>$location,"temp_barcodes"=>$temp_barcodes,"buyer_grade"=>$buyer_grade,"timb_grade"=>$timb_grade,"bale_group"=>$bale_group,"price"=>$price,"barcode"=>$barcode,"bale_mass"=>$bale_mass,"lot"=>$lot,"mass"=>$bale_mass,"sale_batchid"=>$sale_batchid,"already_balanced"=>$already_balanced,"transporter_growersid"=>$row["transporter_growersid"],"grower_num"=>$row["grower_num"],"splitid"=>$row["splitid"],"name"=>$row["name"],"surname"=>$row["surname"],"number_of_bales"=>$result1->num_rows,"selling_point_name"=>$row["selling_point_name"],"growerid"=>$row["growerid"]);
            array_push($response,$temp);


        }
    }



}




echo json_encode($response);