<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type:application/json");
header("Access-Control-Allow-Origin-Methods:POST");
header("Access-Control-Allow-Headers:Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Origin-Methods,Authorization,X-Requested-With");

require_once("conn.php");


$data = json_decode(file_get_contents("php://input"));

$response=array();

if (isset($data->userid) && isset($data->internal_stop_order_amountid) && isset($data->quantity)){
    $splitid=$data->splitid;
    $transporter_growersid=$data->transporter_growersid;
    $internal_stop_order_amountid=$data->internal_stop_order_amountid;
    $userid=$data->userid;
    $quantity=$data->quantity;
    $already_balanced=0;


    $sql = "Select * from balanced_bales where splitid=$splitid and transporter_growersid=$transporter_growersid";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        // output data of each row
        while($row = $result->fetch_assoc()) {
            // echo "id: " . $row["id"]. " - Name: " . $row["firstname"]. " " . $row["lastname"]. "<br>";
            $already_balanced=$row["id"];

        }
    }

    if ($already_balanced==0){
        $user_sql = "INSERT INTO split_internal_stop_order(internal_stop_order_amountid,userid,transporter_growersid,splitsid,quantity) VALUES ($internal_stop_order_amountid,$userid,$transporter_growersid,$splitid,$quantity)";
        //$sql = "select * from login";
        if ($conn->query($user_sql)===TRUE) {

            $last_id = $conn->insert_id;
            $temp=array("response"=>"success");
            array_push($response,$temp);
        }else{
            $temp=array("response"=>$conn->error);
            array_push($response,$temp);
        }

    }else{
        $temp = array("response" => "Cannot Add StopOrder On Balanced Bales");
        array_push($response, $temp);
    }




}




echo json_encode($response);