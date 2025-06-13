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


    $sql = "Select distinct stoporders.userid,stoporders.selling_pointid,growerid,grower_num,sale_id,record_type,grower_type,creditor_no,priority,creditor_ref,account_no,amount_1,amount_2,amount_3,stoporders.percent,sos_date,stoporders.type,serial_no,formatted_sos_date from stoporders join growers on growers.id=stoporders.growerid";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // output data of each row
        while($row = $result->fetch_assoc()) {
            // echo "id: " . $row["id"]. " - Name: " . $row["firstname"]. " " . $row["lastname"]. "<br>";

            $temp=array("grower_num"=>$row["grower_num"],"userid"=>$row["userid"],"grower_num"=>$row["grower_num"],"sale_id"=>$row["sale_id"],"record_type"=>$row["record_type"],"creditor_no"=>$row["creditor_no"],"priority"=>$row["priority"],"creditor_ref"=>$row["creditor_ref"],"account_no"=>$row["account_no"],"amount_1"=>$row["amount_1"],"amount_2"=>$row["amount_2"],"amount_3"=>$row["amount_3"]);
            array_push($response,$temp);


        }
    }

}




echo json_encode($response);