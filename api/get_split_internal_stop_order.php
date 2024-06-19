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
    $splitid=$data->splitid;
    $transporter_growersid=$data->transporter_growersid;


    $sql = "Select distinct grower_num,internal_stop_order_amountid,transporter_growersid,splitsid,transporter_growersid,quantity,split_internal_stop_order.datetime,internal_stop_order.description,internal_stop_order_amount.amount,split_internal_stop_order.splitsid from split_internal_stop_order join internal_stop_order_amount on internal_stop_order_amount.id=split_internal_stop_order.internal_stop_order_amountid join internal_stop_order on internal_stop_order.id=internal_stop_order_amount.internal_stop_orderid join transporter_growers on transporter_growers.id=split_internal_stop_order.transporter_growersid join growers on growers.id=transporter_growers.growerid where split_internal_stop_order.splitsid=$splitid and split_internal_stop_order.transporter_growersid=$transporter_growersid";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // output data of each row
        while($row = $result->fetch_assoc()) {
            // echo "id: " . $row["id"]. " - Name: " . $row["firstname"]. " " . $row["lastname"]. "<br>";


            $temp=array("transporter_growersid"=>$row["transporter_growersid"],"grower_num"=>$row["grower_num"],"splitid"=>$row["splitsid"],"created_at"=>$row["datetime"],"description"=>$row["description"],"amount"=>$row["amount"],"quantity"=>$row["quantity"]);
            array_push($response,$temp);

        }
    }



}




echo json_encode($response);