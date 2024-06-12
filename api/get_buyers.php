<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type:application/json");
header("Access-Control-Allow-Origin-Methods:POST");
header("Access-Control-Allow-Headers:Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Origin-Methods,Authorization,X-Requested-With");

require_once("conn.php");


$data = json_decode(file_get_contents("php://input"));

$response=array();



$sql = "Select distinct buyer.id,selling_pointid,buyer.name,buyer_code,selling_point.name as selling_point_name   from buyer left join selling_point on buyer.selling_pointid=selling_point.id  ";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        // echo "id: " . $row["id"]. " - Name: " . $row["firstname"]. " " . $row["lastname"]. "<br>";
        $temp=array("id"=>$row["id"],"name"=>$row["name"],"selling_pointid"=>$row["selling_pointid"],"buyer_code"=>$row["buyer_code"],"selling_point_name"=>$row["selling_point_name"]);
        array_push($response,$temp);
    }


}




echo json_encode($response);