<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type:application/json");
header("Access-Control-Allow-Origin-Methods:POST");
header("Access-Control-Allow-Headers:Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Origin-Methods,Authorization,X-Requested-With");

require_once("conn.php");


$data = json_decode(file_get_contents("php://input"));

$response=array();


if (isset($data->userid)) {

    $userid=$data->userid;

    $balanced_balesid =0;

    $mass=0;
    $usd_value=0;
    $zim_value=0;
    $laid_bales=0;
    $sold_bales=0;

    $sql = "Select * from balanced_bales where stoporder_processed=0";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        // output data of each row
        while ($row = $result->fetch_assoc()) {
            // echo "id: " . $row["id"]. " - Name: " . $row["firstname"]. " " . $row["lastname"]. "<br>";
            $balanced_balesid = $row["id"];


            $sql1 = "Select * from daily_totals where balanced_balesid=$balanced_balesid";
            $result1 = $conn->query($sql1);
            if ($result1->num_rows > 0) {
                // output data of each row
                while ($row1 = $result1->fetch_assoc()) {
                    //echo "id: " . $row["id"]. " - Name: " . $row["firstname"]. " " . $row["lastname"]. "<br>";
                    //$grower_already_sold += $row["id"];
                    $mass=$row1["mass"];
                    $usd_value=$row1["usd_value"];
                    $zim_value=$row1["zim_value"];
                    $laid_bales=$row1["laid_bales"];
                    $sold_bales=$row1["sold_bales"];

                }

            }

            $charges_amount_usd=0;
            $charges_amount_zwl=0;
            $grower_chargeid=0;
            $sql1 = "Select distinct charge_type.description,grower_charges.id,amount from grower_charges join charge_type on charge_type.id=grower_charges.charge_typeid";
            $result1 = $conn->query($sql1);
            if ($result1->num_rows > 0) {
                // output data of each row
                while ($row1 = $result1->fetch_assoc()) {
                    //echo "id: " . $row["id"]. " - Name: " . $row["firstname"]. " " . $row["lastname"]. "<br>";
                    //$grower_already_sold += $row["id"];
                    $charges_amount_usd=0;
                    $charges_amount_zwl=0;
                    $grower_chargeid=$row1["id"];
                    if($row1["description"]=="Value"){
                        $charges_amount_usd=$usd_value*$row1["amount"];
                    }elseif ($row1["description"]=="Mass"){
                        $charges_amount_usd=$mass*$row1["amount"];
                    }elseif ($row1["description"]=="Mass and Value"){
                        $charges_amount_usd=$mass*$row1["amount"] + $usd_value*$row1["amount"];
                    }elseif ($row1["description"]=="Bales"){
                        $charges_amount_usd=$sold_bales*$row1["amount"];
                    }


                    $user_sql = "INSERT INTO balanced_bales_charges(userid,grower_chargeid,balanced_balesid,usd_value,zim_value) VALUES ($userid,$grower_chargeid,$balanced_balesid,$charges_amount_usd,$charges_amount_zwl)";
                    //$sql = "select * from login";
                    if ($conn->query($user_sql) === TRUE) {

                     }else{
                        $temp = array("response" => $conn->error);
                        array_push($response, $temp);
                    }

                }

            }

            $user_sql = "update balanced_bales set stoporder_processed=1 where id= $balanced_balesid";
            //$sql = "select * from login";
            if ($conn->query($user_sql) === TRUE) {

            }else{

                $temp = array("response" => $conn->error);
                array_push($response, $temp);
            }


        }
    }

}echo json_encode($response);