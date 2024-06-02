<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type:application/json");
header("Access-Control-Allow-Origin-Methods:POST");
header("Access-Control-Allow-Headers:Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Origin-Methods,Authorization,X-Requested-With");

require_once("conn.php");


$data = json_decode(file_get_contents("php://input"));

$response=array();

$found=0;
$already_used=0;

if (isset($data->temp_barcode) && isset($data->grower_num) && isset($data->lot)){

    $temp_bacode=$data->temp_barcode;
    $grower_num=$data->grower_num;
    $bale_group=$data->bale_group;
    $lot=$data->lot;
    $created_at=$data->created_at;


    $growerid=0;
    $userid=$data->userid;
    $transporter_growersid=0;
    $bale_massid=0;
    $transporter_growersid=0;



    $sql = "Select * from bale_mass where temp_bacode='$temp_bacode'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // output data of each row
        while($row = $result->fetch_assoc()) {
            // echo "id: " . $row["id"]. " - Name: " . $row["firstname"]. " " . $row["lastname"]. "<br>";

            $found=$row["id"];
            $bale_massid=$row["id"];

        }
    }


    $sql = "Select * from bale_junus where bale_massid=$bale_massid";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // output data of each row
        while($row = $result->fetch_assoc()) {
            // echo "id: " . $row["id"]. " - Name: " . $row["firstname"]. " " . $row["lastname"]. "<br>";
            $already_used=$row["id"];
        }
    }



    $sql = "Select * from growers where grower_num='$grower_num'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // output data of each row
        while($row = $result->fetch_assoc()) {
            // echo "id: " . $row["id"]. " - Name: " . $row["firstname"]. " " . $row["lastname"]. "<br>";

            $growerid=$row["id"];

        }
    }




    $sql = "Select * from transporter_growers where open_close=0  and bales>junused_bales order by id ";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // output data of each row
        while($row = $result->fetch_assoc()) {
            // echo "id: " . $row["id"]. " - Name: " . $row["firstname"]. " " . $row["lastname"]. "<br>";

            $transporter_growersid=$row["id"];

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




    if ($found>0 && $transporter_growersid>0 && $growerid>0 && $already_used==0){

        $user_sql = "INSERT INTO bale_junus(userid,transporter_growersid,bale_massid,growerid,bale_group,lot,created_at) VALUES ($userid,$transporter_growersid,$bale_massid,$growerid,$bale_group,$lot,'$created_at')";
        //$sql = "select * from login";
        if ($conn->query($user_sql)===TRUE) {

            $last_id = $conn->insert_id;

            $user_sql2 = "update transporter_growers set junused_bales=junused_bales+1 where id = $transporter_growersid";
            //$sql = "select * from login";
            if ($conn->query($user_sql2)===TRUE) {

                $last_id = $conn->insert_id;
                $temp=array("response"=>"success");
                array_push($response,$temp);
            }

        }else{
            $temp=array("response"=>$conn->error);
            array_push($response,$temp);
        }

    }else{

        if($growerid==0){

            $temp=array("response"=>"grower not found");
            array_push($response,$temp);

        }elseif ($already_used>0){

            $temp=array("response"=>"tag already used");
            array_push($response,$temp);

        }elseif ($transporter_growersid==0){

            $temp=array("response"=>"Truck Delivery not found");
            array_push($response,$temp);

        }elseif($found==0){

            $temp=array("response"=>"tag not found");
            array_push($response,$temp);

        }

    }




}




echo json_encode($response);