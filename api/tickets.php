<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type:application/json");
header("Access-Control-Allow-Origin-Methods:POST");
header("Access-Control-Allow-Headers:Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Origin-Methods,Authorization,X-Requested-With");

require_once("conn.php");


$data = json_decode(file_get_contents("php://input"));

$response=array();

$found=0;

if (isset($data->transporter_growersid) && isset($data->start_barcode) && isset($data->userid)){

    $userid=$data->userid;
    $start_barcode=$data->start_barcode;
    $created_at=$data->created_at;
    $transporter_growersid=$data->transporter_growersid;
    $bale_junusid=0;
    $barcode="";
    $my_numbers=0;





    $sql = "Select * from tickets where barcode='$start_barcode' limit 1";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // output data of each row
        while($row = $result->fetch_assoc()) {
            // echo "id: " . $row["id"]. " - Name: " . $row["firstname"]. " " . $row["lastname"]. "<br>";
            $found=$row["id"];

        }
    }



    if ($found==0){

        $my_numbers=(int) substr($start_barcode,0,9);

        $check_letter=substr($start_barcode,-1);

        $bale_junusid=0;

        $sql = "Select * from bale_junus where transporter_growersid=$transporter_growersid";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // output data of each row
            while($row = $result->fetch_assoc()) {
                // echo "id: " . $row["id"]. " - Name: " . $row["firstname"]. " " . $row["lastname"]. "<br>";

                $bale_junusid=$row["id"];

                $barcode=$my_numbers."".$check_letter;

                $user_sql = "INSERT INTO tickets(userid, bale_junusid, barcode,created_at) VALUES ($userid,$bale_junusid,'$barcode','$created_at')";
                //$sql = "select * from login";
                if ($conn->query($user_sql)===TRUE) {

                    $last_id = $conn->insert_id;
                    #$temp=array("response"=>"success");
                    #array_push($response,$temp);
                }else{
                    $temp=array("response"=>"failed");
                    array_push($response,$temp);
                }

                $my_numbers+=1;

            }

            $user_sql1 = "update transporter_growersid set open_close=1 where id != $transporter_growersid";
            //$sql = "select * from login";
            if ($conn->query($user_sql1)===TRUE) {

            }

        }


    }




}




echo json_encode($response);