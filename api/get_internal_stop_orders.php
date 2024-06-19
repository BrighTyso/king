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


    $sql = "Select distinct  internal_stop_order_amount.id,internal_stop_order.description,internal_stop_order.creditor_number,internal_stop_order_amount.amount,internal_stop_order_amount.priority,seasons.description as season_name,selling_point.name from internal_stop_order join internal_stop_order_amount on internal_stop_order_amount.internal_stop_orderid=internal_stop_order.id join seasons on internal_stop_order_amount.seasonid=seasons.id join selling_point on internal_stop_order.selling_pointid=selling_point.id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // output data of each row
        while($row = $result->fetch_assoc()) {
            // echo "id: " . $row["id"]. " - Name: " . $row["firstname"]. " " . $row["lastname"]. "<br>";


            $temp=array("id"=>$row["id"],"description"=>$row["description"],"creditor_number"=>$row["creditor_number"],"amount"=>$row["amount"],"priority"=>$row["priority"],"season"=>$row["season_name"],"selling_point"=>$row["name"]);
            array_push($response,$temp);

        }
    }



}




echo json_encode($response);