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
    $name = $data->name;
    $creditor_no = $data->creditor_no;
    $creditor_name = $data->creditor_name;
    $creditor_type = $data->creditor_type;
    $business_type= $data->business_type;
    $cell_phone= $data->cell_phone;
    $creditor_found=0;

    $sql = "Select distinct * from grower_creditors where creditor_no=$creditor_no";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // output data of each row
        while($row = $result->fetch_assoc()) {

            $creditor_found=$row["id"];
        }
    }

    if($creditor_found==0){

        $user_sql = "INSERT INTO grower_creditors(userid, name, creditor_no, creditor_name, creditor_type, business_type, cell_phone ) VALUES ($userid, '$name', '$creditor_no', '$creditor_name', '$creditor_type', '$business_type', '$cell_phone')";
        if ($conn->query($user_sql) === TRUE) {

            $last_id = $conn->insert_id;
            $temp = array("response" => "success");
            array_push($response, $temp);

//
        } else {
            $temp = array("response" => $conn->error);
            array_push($response, $temp);
        }
    }else{

        $temp = array("response" => "Creditor Already Created");
        array_push($response, $temp);
    }


}

echo json_encode($response);