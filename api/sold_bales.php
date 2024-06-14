<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type:application/json");
header("Access-Control-Allow-Origin-Methods:POST");
header("Access-Control-Allow-Headers:Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Origin-Methods,Authorization,X-Requested-With");

require_once("conn.php");


$data = json_decode(file_get_contents("php://input"));

$response=array();

if (isset($data->sale_code) && isset($data->created_at)){


    $userid=$data->userid;
    $created_at=$data->created_at;
    $ticketsid=$data->ticketid;
    $found=0;
    $soldid=0;

    $sales_rep=$data->sales_rep;
    $sale_code=$data->sale_code;
    $timb_grades=$data->timb_grades;
    $buyer_grades=$data->buyer_grades;
    $price=$data->price;
    $timb_gradesid=0;
    $buyer_gradesid=0;



    $sql = "Select * from floor_summary where ticketsid=$ticketsid";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // output data of each row
        while($row = $result->fetch_assoc()) {
            // echo "id: " . $row["id"]. " - Name: " . $row["firstname"]. " " . $row["lastname"]. "<br>";

            $found=$row["id"];

        }
    }



    $sql = "Select * from buyer_grades where description='$buyer_grades'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // output data of each row
        while($row = $result->fetch_assoc()) {
            // echo "id: " . $row["id"]. " - Name: " . $row["firstname"]. " " . $row["lastname"]. "<br>";

            $buyer_gradesid=$row["id"];

        }
    }




    $sql = "Select * from timb_grades where description='$timb_grades'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // output data of each row
        while($row = $result->fetch_assoc()) {
            // echo "id: " . $row["id"]. " - Name: " . $row["firstname"]. " " . $row["lastname"]. "<br>";

            $timb_gradesid=$row["id"];

        }
    }



    $sql = "Select * from sold_bales where ticketsid=$ticketsid";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // output data of each row
        while($row = $result->fetch_assoc()) {
            // echo "id: " . $row["id"]. " - Name: " . $row["firstname"]. " " . $row["lastname"]. "<br>";

            $soldid=$row["id"];

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

    if ($ticketsid>0   && $timb_gradesid>0 && $buyer_gradesid>0 && $soldid==0){

        $user_sql = "INSERT INTO sold_bales(userid,ticketsid,sales_rep,sale_code,timb_gradesid,buyer_gradesid,price,created_at) VALUES ($userid,$ticketsid,'$sales_rep','$sale_code',$timb_gradesid,$buyer_gradesid,'$price','$created_at')";
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
        if ($timb_gradesid==0){
            $temp = array("response" => "Invalid Timb Grade");
            array_push($response, $temp);
        }else if($buyer_gradesid==0){
            $temp = array("response" => "Invalid Buyer Grade");
            array_push($response, $temp);
        }else if($soldid>0){
            $temp = array("response" => "Barcode Already Captured");
            array_push($response, $temp);
        }else{
            $temp = array("response" => "Bale Not Found");
            array_push($response, $temp);
        }

    }


}




echo json_encode($response);