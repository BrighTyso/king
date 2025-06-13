<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type:application/json");
header("Access-Control-Allow-Origin-Methods:POST");
header("Access-Control-Allow-Headers:Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Origin-Methods,Authorization,X-Requested-With");

require_once("conn.php");


$data = json_decode(file_get_contents("php://input"));

$response=array();



$sql = "Select distinct buyer_grades.id,description,selling_point.name,buyer_code  from buyer_grades join buyer on buyer.id=buyer_grades.buyerid join selling_point on selling_point.id=buyer.selling_pointid";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        // echo "id: " . $row["id"]. " - Name: " . $row["firstname"]. " " . $row["lastname"]. "<br>";
        $temp=array("id"=>$row["id"],"description"=>$row["description"],"buyer_code"=>$row["buyer_code"],"name"=>$row["name"]);
        array_push($response,$temp);
    }

}




echo json_encode($response);
