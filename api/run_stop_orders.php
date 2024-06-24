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
    $selling_pointid=$data->selling_pointid;

    $balanced_balesid =0;
    $batch_number=0;


    $mass=0;
    $usd_value=0;
    $zim_value=0;
    $laid_bales=0;
    $sold_bales=0;
    $rbz_off_set=0;
    $fetched_records=0;
    $balanced_balesid=0;
    $splitid=0;
    $exchange_rate=1;
    $transporter_growersid=0;
    $created_at=date("Y-m-d");


    $user_sql = "INSERT INTO sales_batching(userid) VALUES ($userid)";
    //$sql = "select * from login";
    if ($conn->query($user_sql) === TRUE) {
        $batch_number = $conn->insert_id;
    }else{
        $batch_number=0;
    }


    $sql1 = "Select * from rbz_off_set where selling_pointid=$selling_pointid limit 1";
    $result1 = $conn->query($sql1);
    if ($result1->num_rows > 0) {
        // output data of each row
        while ($row1 = $result1->fetch_assoc()) {

            $rbz_off_set=$row1["id"];

        }
    }


    if ($batch_number>0){


        if ($rbz_off_set==0){


            $sql = "Select balanced_bales.transporter_growersid,balanced_bales.splitid,balanced_bales.id from balanced_bales join transporter_growers on transporter_growers.id=balanced_bales.transporter_growersid join growers on growers.id=transporter_growers.growerid where stoporder_processed=0 and growers.selling_pointid=$selling_pointid";
            $result = $conn->query($sql);
            $fetched_records=$result->num_rows;
            if ($result->num_rows > 0) {
                // output data of each rowi
                while ($row = $result->fetch_assoc()) {
                    // echo "id: " . $row["id"]. " - Name: " . $row["firstname"]. " " . $row["lastname"]. "<br>";
                    $balanced_balesid = $row["id"];
                    $splitid=$row["splitid"];
                    $transporter_growersid=$row["transporter_growersid"];


                    $sql1 = "Select * from daily_totals join exchange_rate on exchange_rate.id=daily_totals.exchange_rateid where balanced_balesid=$balanced_balesid";
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
                            $exchange_rate=$row1["amount"];

                        }

                    }


                    $sql1 = "Select * from rbz_retention_rate limit 1";
                    $result1 = $conn->query($sql1);
                    if ($result1->num_rows > 0) {
                        // output data of each row
                        while ($row1 = $result1->fetch_assoc()) {
                            $current_usd_amount=$usd_value*$row1["usd_percent"];
                            $current_zig_amount=($usd_value-$current_usd_amount)*$exchange_rate;
                        }
                    }


                    $user_sql = "INSERT INTO daily_totals_split_amounts(userid,balanced_balesid,usd_split_value,zim_split_value ,created_at) VALUES ($userid,$balanced_balesid,$current_usd_amount,$current_zig_amount,$created_at)";
                    //$sql = "select * from login";
                    if ($conn->query($user_sql) === TRUE) {

                        //$current_usd_amount-=$stop_usd_amount;

                    }else{
                        $temp = array("response" => $conn->error);
                        array_push($response, $temp);
                    }



                    $charges_amount_usd=0;
                    $charges_amount_zwl=0;
                    $grower_chargeid=0;

                    $sql1 = "Select distinct charge_type.description,grower_charges.id,grower_charges.amount,currency.description as currency_d from grower_charges join charge_type on charge_type.id=grower_charges.charge_typeid join statutory on statutory.id=grower_charges.statutoryid join currency on currency.id=statutory.currencyid where grower_charges.selling_pointid=$selling_pointid";
                    $result1 = $conn->query($sql1);
                    if ($result1->num_rows > 0) {
                        // output data of each row
                        while ($row1 = $result1->fetch_assoc()) {
                            //echo "id: " . $row["id"]. " - Name: " . $row["firstname"]. " " . $row["lastname"]. "<br>";
                            //$grower_already_sold += $row["id"];
                            $charges_amount_usd=0;
                            $charges_amount_zwl=0;
                            $grower_chargeid=$row1["id"];
                            $currency=$row1["currency_d"];

                            if ($currency=="USD"){

                                if($row1["description"]=="Value"){
                                    $charges_amount_usd=$usd_value*$row1["amount"];
                                }elseif ($row1["description"]=="Mass"){
                                    $charges_amount_usd=$mass*$row1["amount"];
                                }elseif ($row1["description"]=="Mass and Value"){
                                    $charges_amount_usd=$mass*$row1["amount"] + $usd_value*$row1["amount"];
                                }elseif ($row1["description"]=="Bales"){
                                    $charges_amount_usd=$sold_bales*$row1["amount"];
                                }

                            }else{

                                if($row1["description"]=="Value"){
                                    $charges_amount_zwl=$zim_value*$row1["amount"];
                                }elseif ($row1["description"]=="Mass"){
                                    $charges_amount_zwl=$mass*$row1["amount"];
                                }elseif ($row1["description"]=="Mass and Value"){
                                    $charges_amount_zwl=$mass*$row1["amount"] + $zim_value*$row1["amount"];
                                }elseif ($row1["description"]=="Bales"){
                                    $charges_amount_zwl=$sold_bales*$row1["amount"];
                                }

                            }




                            $user_sql = "INSERT INTO balanced_bales_charges(userid,grower_chargeid,balanced_balesid,usd_value,zim_value) VALUES ($userid,$grower_chargeid,$balanced_balesid,$charges_amount_usd,$charges_amount_zwl)";
                            //$sql = "select * from login";
                            if ($conn->query($user_sql) === TRUE) {
                                $current_usd_amount-=$charges_amount_usd;
                                $current_zig_amount-=$charges_amount_zwl;
                            }else{

                                $temp = array("response" => $conn->error);
                                array_push($response, $temp);
                            }

                        }

                    }


                    $quantity=0;
                    $internal_amount=0;
                    $stop_usd_amount=0;


                    $sql2= "Select distinct amount,quantity,split_internal_stop_order.id from split_internal_stop_order join internal_stop_order_amount on internal_stop_order_amount.id=split_internal_stop_order.internal_stop_order_amountid  where split_internal_stop_order.splitsid=$splitid and split_internal_stop_order.transporter_growersid=$transporter_growersid";
                    $result2 = $conn->query($sql2);
                    if ($result2->num_rows > 0) {
                        // output data of each row
                        while ($row2 = $result2->fetch_assoc()) {
                            //echo "id: " . $row["id"]. " - Name: " . $row["firstname"]. " " . $row["lastname"]. "<br>";
                            //$grower_already_sold += $row["id"];
                            $internal_splitid=$row2["id"];
                            $quantity=$row2["quantity"];
                            $internal_amount=$row2["amount"];

                            $stop_usd_amount=$quantity*$internal_amount;

                            if ($current_usd_amount>$stop_usd_amount && $stop_usd_amount>0){


                                $user_sql = "INSERT INTO internal_stop_order_deducted_amounts(userid,split_internal_stop_orderid,balanced_balesid,amount) VALUES ($userid,$internal_splitid,$balanced_balesid,$stop_usd_amount)";
                                //$sql = "select * from login";
                                if ($conn->query($user_sql) === TRUE) {

                                    $current_usd_amount-=$stop_usd_amount;

                                }else{
                                    $temp = array("response" => $conn->error);
                                    array_push($response, $temp);
                                }

                            }else
                            {
                                //break;
                            }



                        }

                    }


                    $user_sql = "INSERT INTO daily_totals_net_amount(userid,balanced_balesid,usd_net_value,zim_net_value ,created_at) VALUES ($userid,$balanced_balesid,$current_usd_amount,$current_zig_amount,$created_at)";
                    //$sql = "select * from login";
                    if ($conn->query($user_sql) === TRUE) {

                        //$current_usd_amount-=$stop_usd_amount;
                       // $current_zig_amount

                    }else{
                        $temp = array("response" => $conn->error);
                        array_push($response, $temp);
                    }

                    $user_sql = "update balanced_bales set stoporder_processed=1,sale_batchid=$batch_number where id= $balanced_balesid";
                    //$sql = "select * from login";
                    if ($conn->query($user_sql) === TRUE) {

                    }else{

                        $temp = array("response" => $conn->error);
                        array_push($response, $temp);
                    }


                }
            }





        }else{
// off_set is set here
        }

        $temp = array("fetched" => $fetched_records);
        array_push($response, $temp);

    }

}


echo json_encode($response);