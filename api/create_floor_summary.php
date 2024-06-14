<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type:application/json");
header("Access-Control-Allow-Origin-Methods:POST");
header("Access-Control-Allow-Headers:Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Origin-Methods,Authorization,X-Requested-With");

require_once("conn.php");


$data = json_decode(file_get_contents("php://input"));

$response=array();

if (isset($data->row) && isset($data->barcode) && isset($data->created_at)){


    $userid=$data->userid;
    $barcode=$data->barcode;
    $row1=$data->row;
    $created_at=$data->created_at;
    $ticketid=0;
    $found=0;


    $sql = "Select * from tickets where barcode='$barcode'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // output data of each row
        while($row = $result->fetch_assoc()) {
            // echo "id: " . $row["id"]. " - Name: " . $row["firstname"]. " " . $row["lastname"]. "<br>";

            $ticketid=$row["id"];

        }
    }





    $sql = "Select * from floor_summary where ticketsid=$ticketid";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // output data of each row
        while($row = $result->fetch_assoc()) {
            // echo "id: " . $row["id"]. " - Name: " . $row["firstname"]. " " . $row["lastname"]. "<br>";

            $found=$row["id"];

        }
    }



//    $sql = "Select * from transporter_growers_total_received where growerid=$growerid and transporterid=$transporterid";
//    $result = $conn->query($sql);
//
//    if ($result->num_rows > 0) {
//        // output data of each row
//        while($row = $result->fetch_assoc()) {
//            // echo "id: " . $row["id"]. " - Name: " . $row["firstname"]. " " . $row["lastname"]. "<br>";
//
//            $found=$row["id"];
//
//        }
//    }

    if ($ticketid>0 && $found==0){

        $user_sql = "INSERT INTO floor_summary(userid,ticketsid,row,created_at) VALUES ($userid,$ticketid,$row1,'$created_at')";
        //$sql = "select * from login";
        if ($conn->query($user_sql)===TRUE) {

            $last_id = $conn->insert_id;
            $temp = array("response" => "success");
            array_push($response, $temp);

        }else{
            $temp=array("response"=>$conn->error);
            array_push($response,$temp);
        }

    }else{
        if ($found==1){
            $temp = array("response" => "Barcode Already Scanned");
            array_push($response, $temp);
        }else{
            $temp = array("response" => "Barcode not found");
            array_push($response, $temp);
        }

    }


}




echo json_encode($response);