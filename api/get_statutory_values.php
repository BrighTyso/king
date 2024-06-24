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


    $sql = "Select distinct statutory.description,amount,creditor_no,grower_charges.id,selling_point.name,charge_type.description as charge_type from statutory  join grower_charges on grower_charges.statutoryid=statutory.id
     join selling_point on grower_charges.selling_pointid=selling_point.id  join charge_type on charge_type.id=grower_charges.charge_typeid";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // output data of each row
        while($row = $result->fetch_assoc()) {
            // echo "id: " . $row["id"]. " - Name: " . $row["firstname"]. " " . $row["lastname"]. "<br>";
            $temp=array("description"=>$row["description"],"id"=>$row["id"],"creditor_no"=>$row["creditor_no"],"amount"=>$row["amount"],"selling_point"=>$row["name"],"charge_type"=>$row["charge_type"]);
            array_push($response,$temp);

        }
    }

}




echo json_encode($response);