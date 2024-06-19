<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type:application/json");
header("Access-Control-Allow-Origin-Methods:POST");
header("Access-Control-Allow-Headers:Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Origin-Methods,Authorization,X-Requested-With");

require_once("conn.php");


$data = json_decode(file_get_contents("php://input"));

$response=array();

if (isset($data->userid) && isset($data->description) && isset($data->amount)){
    $amount=$data->amount;
    $description=$data->description;
    $userid=$data->userid;
    $seasonid=0;
    $creditor_number=$data->creditor_number;
    $charge_typeid=$data->charge_typeid;
    $priority=$data->priority;
    $selling_pointid=$data->selling_pointid;
    $stop_order_found=0;


    $sql = "Select distinct * from internal_stop_order where description=$creditor_number and selling_pointid=$selling_pointid";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // output data of each row
        while($row = $result->fetch_assoc()) {
            // echo "id: " . $row["id"]. " - Name: " . $row["firstname"]. " " . $row["lastname"]. "<br>";
            $stop_order_found=$row["id"];
        }
    }

    $sql = "Select distinct * from seasons where active=1";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // output data of each row
        while($row = $result->fetch_assoc()) {
            // echo "id: " . $row["id"]. " - Name: " . $row["firstname"]. " " . $row["lastname"]. "<br>";
            $seasonid=$row["id"];
        }
    }

    if ($stop_order_found==0){


    $user_sql = "INSERT INTO internal_stop_order(userid,description,creditor_number,selling_pointid) VALUES ($userid,'$description','$creditor_number',$selling_pointid)";
    //$sql = "select * from login";
    if ($conn->query($user_sql)===TRUE) {

            $last_id = $conn->insert_id;
            $user_sql = "INSERT INTO internal_stop_order_amount(userid,internal_stop_orderid,seasonid,charge_typeid,amount,priority) VALUES ($userid,$last_id,$seasonid,$charge_typeid,'$amount',$priority)";
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
        $temp=array("response"=>$conn->error);
        array_push($response,$temp);
    }

    }else{

        $temp=array("response"=>"Already Created");
        array_push($response,$temp);

    }

}




echo json_encode($response);