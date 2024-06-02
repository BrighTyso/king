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
    $dispatch_destinationid = $data->userid;
    $barcode=$data->barcode;
    $sold_baleid = 0;
    $created_at = $data->created_at;
    $bale_dispatched=0;
    $transporter_growersid=0;
    $splitid=0;
    $bale_balanced=0;


    $sql = "Select distinct sold_bales.id,bale_junus.transporter_growersid,bale_junus.splitid from sold_bales join tickets on tickets.id =sold_bales.ticketsid join bale_junus on bale_junus.id=tickets.bale_junusid where id=$sold_baleid";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // output data of each row
        while($row = $result->fetch_assoc()) {


            $sold_baleid=$row["id"];
            $transporter_growersid=$row["transporter_growersid"];
            $splitid=$row["splitid"];
        }
    }


    if ($sold_baleid>0) {


        $sql = "Select distinct * from dispatch where sold_baleid=$sold_baleid";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // output data of each row
            while ($row = $result->fetch_assoc()) {

                $bale_dispatched = $row["id"];
            }
        }


        $sql = "Select distinct * from balanced_bales where transporter_growersid=$transporter_growersid and splitid=$splitid";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // output data of each row
            while ($row = $result->fetch_assoc()) {

                $bale_balanced = $row["id"];
            }
        }


        if($bale_dispatched==0 && $bale_balanced>0) {

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
            $temp = array("response" => "Failed");
            array_push($response, $temp);
        }


    }else{
        $temp = array("response" => "Failed");
        array_push($response, $temp);
    }

}


echo json_encode($response);