<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type:application/json");
header("Access-Control-Allow-Origin-Methods:POST");
header("Access-Control-Allow-Headers:Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Origin-Methods,Authorization,X-Requested-With");

require_once("conn.php");


$data = json_decode(file_get_contents("php://input"));

$response=array();
if (isset($data->userid)) {

    $userid = $data->userid;
    $dispatch_destinationid = $data->dispatch_destinationid;
    $created_at = $data->created_at;
    $barcode=$data->barcode;
    $selling_point_destinationid = 0;
    $selling_point_dispatchid=0;

    $sold_baleid = 0;
    $bale_dispatched=0;
    $transporter_growersid=0;
    $splitid=0;
    $bale_balanced=0;
    $bale_sellingpoint=0;
    $dispatch_destination_buyerid=0;
    $bale_buyerid=0;
    $dispatch_destination_open = 1;



    $sql = "Select distinct sold_bales.id,bale_junus.transporter_growersid,bale_junus.splitid,growers.selling_pointid,sold_bales.buyer_gradesid,buyer.id as buyerid from sold_bales join tickets on tickets.id =sold_bales.ticketsid join buyer_grades on buyer_grades.id=sold_bales.buyer_gradesid 
    join buyer on buyer.id=buyer_grades.buyerid join bale_junus on bale_junus.id=tickets.bale_junusid join transporter_growers on transporter_growers.id=bale_junus.transporter_growersid join growers on growers.id=transporter_growers.growerid where barcode='$barcode'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // output data of each row
        while($row = $result->fetch_assoc()) {
            $sold_baleid=$row["id"];
            $transporter_growersid=$row["transporter_growersid"];
            $splitid=$row["splitid"];
            $bale_sellingpoint=$row["selling_pointid"];
            $bale_buyerid=$row["buyerid"];

            //print('1');

        }
    }


    if ($sold_baleid>0) {


        $sql = "Select distinct * from dispatch where sold_baleid=$sold_baleid";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // output data of each row
            while ($row = $result->fetch_assoc()) {

                $bale_dispatched = $row["id"];
                //print('2');
            }
        }


        $sql = "Select distinct * from balanced_bales where transporter_growersid=$transporter_growersid and splitid=$splitid";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // output data of each row
            while ($row = $result->fetch_assoc()) {

                $bale_balanced = $row["id"];
               // print('3');
            }
        }


        $sql = "Select distinct dispatch_destination.open_close,buyer.id as buyerid,dispatch_destination.selling_point_dispatchid from dispatch_destination 
       join selling_point_dispatch on selling_point_dispatch.id=dispatch_destination.selling_point_dispatchid 
       join buyer on buyer.id=selling_point_dispatch.buyerid where dispatch_destination.id=$dispatch_destinationid ";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // output data of each row
            while ($row = $result->fetch_assoc()) {

                $dispatch_destination_open = $row["open_close"];
                $dispatch_destination_buyerid = $row["buyerid"];
                $selling_point_dispatchid== $row["selling_point_dispatchid"];
                //print('4');
            }
        }


        if($bale_dispatched==0 && $bale_balanced>0 && $dispatch_destination_buyerid==$bale_buyerid && $dispatch_destination_open==1) {

            $user_sql = "INSERT INTO dispatch(userid, dispatch_destinationid, sold_baleid, created_at) VALUES ($userid, $dispatch_destinationid, $sold_baleid, '$created_at')";
            //$sql = "select * from login";
            if ($conn->query($user_sql) === TRUE) {

                $last_id = $conn->insert_id;
                $temp = array("response" => "success");
                array_push($response, $temp);
            } else {
                $temp = array("response" => "Failed");
                array_push($response, $temp);
            }
        }else{

            if($bale_dispatched>0) {
                $temp = array("response" => "Already despatched");
                array_push($response, $temp);
            }elseif ($bale_balanced==0 ){
                $temp = array("response" => "Bale Not Yet Sold");
                array_push($response, $temp);
            }elseif ($dispatch_destination_buyerid!=$bale_buyerid ){
                $temp = array("response" => "Bale Can Not Be Dispatched Into This Truck");
                array_push($response, $temp);
            }elseif ($dispatch_destination_open==0){
                $temp = array("response" => "Dispatch Truck Closed");
                array_push($response, $temp);
            }

        }


    }else{
        $temp = array("response" => "Barcode not found");
        array_push($response, $temp);
    }

}


echo json_encode($response);