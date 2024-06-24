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
    $created_at=date("Y-m-d");
    $transporter_growersid=$data->transporter_growersid;
    $bale_junusid=0;
    $barcode="";
    $my_numbers=0;
    $tickets_created=0;
    $already_balanced=0;
    $start_of_day_found=0;
    $todays_date=date("Y-m-d");





    $sql = "Select * from tickets where barcode='$start_barcode' limit 1";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // output data of each row
        while($row = $result->fetch_assoc()) {
            // echo "id: " . $row["id"]. " - Name: " . $row["firstname"]. " " . $row["lastname"]. "<br>";
            $found=$row["id"];

        }
    }


    $sql = "Select * from bale_junus join tickets on tickets.bale_junusid=bale_junus.id where transporter_growersid=$transporter_growersid limit 1";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // output data of each row
        while($row = $result->fetch_assoc()) {
            // echo "id: " . $row["id"]. " - Name: " . $row["firstname"]. " " . $row["lastname"]. "<br>";
            $tickets_created=$row["id"];

        }
    }


    $sql = "Select * from start_of_day where created_at='$todays_date' and active=1";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // output data of each row
        while($row = $result->fetch_assoc()) {
            // echo "id: " . $row["id"]. " - Name: " . $row["firstname"]. " " . $row["lastname"]. "<br>";

            $start_of_day_found=$row["id"];

        }
    }


    $sql = "Select * from balanced_bales where  transporter_growersid=$transporter_growersid";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        // output data of each row
        while($row = $result->fetch_assoc()) {
            // echo "id: " . $row["id"]. " - Name: " . $row["firstname"]. " " . $row["lastname"]. "<br>";
            $already_balanced=$row["id"];

        }
    }



    if ($already_balanced==0){


    if ($found==0 && $start_of_day_found>0){

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

                $add_numbers= (int) substr($my_numbers,0,1) +(int) substr($my_numbers,1,1) +(int) substr($my_numbers,2,1)+(int) substr($my_numbers,3,1)
                    +(int) substr($my_numbers,4,1)+(int) substr($my_numbers,5,1)+(int) substr($my_numbers,6,1)+(int) substr($my_numbers,7,1)+
                    (int) substr($my_numbers,8,1)+(int) substr($my_numbers,9,1);


                //echo (int) substr($my_numbers,9,1);

                $rem=$add_numbers%43;



                $sql1 = "Select characters from ticket_check_letter where value=$rem";
                $result1 = $conn->query($sql1);

                if ($result1->num_rows > 0) {
                    // output data of each row
                    while($row1 = $result1->fetch_assoc()) {
                        // echo "id: " . $row["id"]. " - Name: " . $row["firstname"]. " " . $row["lastname"]. "<br>";
                        $check_letter=$row1["characters"];
                    }
                }

                $barcode=$my_numbers."".$check_letter;



                if ( $tickets_created==0){
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
                }else{

                    $user_sql1 = "update tickets set barcode='$barcode' where bale_junusid=$bale_junusid";
                    //$sql = "select * from login";
                    if ($conn->query($user_sql1)===TRUE) {

                    }
                }


                $my_numbers+=1;

            }

            $user_sql1 = "update transporter_growersid set open_close=1 where id != $transporter_growersid";
            //$sql = "select * from login";
            if ($conn->query($user_sql1)===TRUE) {

            }

        }


    }else{
        $temp=array("response"=>"barcode used");
        array_push($response,$temp);
    }

    }else{
        $temp = array("response" => "Cannot Set Barcode On Balanced Bales");
        array_push($response, $temp);
    }




}




echo json_encode($response);