<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type:application/json");
header("Access-Control-Allow-Origin-Methods:POST");
header("Access-Control-Allow-Headers:Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Origin-Methods,Authorization,X-Requested-With");

require_once("conn.php");


$data = json_decode(file_get_contents("php://input"));

$response=array();


if (isset($data->userid)) {

    $ref_id=$data->ref_id;
    $batch=$data->batch_number;

    $found=0;

    if ($ref_id==0 && $batch>0) {

        $sql = "Select * from balanced_bales  where stoporder_processed=1 and sale_batchid=$batch";
        $result = $conn->query($sql);
        $fetched_records = $result->num_rows;
        if ($result->num_rows > 0) {
            // output data of each rowi
            while ($row = $result->fetch_assoc()) {
                $found = $row["id"];

                if ($found > 0) {


                    $user_sql1 = "DELETE FROM internal_stop_order_deducted_amounts where balanced_balesid = $found ";
                    //$sql = "select * from login";
                    if ($conn->query($user_sql1) === TRUE) {

                        $user_sql1 = "DELETE FROM daily_totals_net_amount where balanced_balesid = $found ";
                        //$sql = "select * from login";
                        if ($conn->query($user_sql1) === TRUE) {

                            $user_sql1 = "DELETE FROM balanced_bales_charges where balanced_balesid = $found ";
                            //$sql = "select * from login";
                            if ($conn->query($user_sql1) === TRUE) {

                                $user_sql1 = "DELETE FROM daily_totals_split_amounts where balanced_balesid = $found ";
                                //$sql = "select * from login";
                                if ($conn->query($user_sql1) === TRUE) {

                                    $user_sql1 = "DELETE FROM daily_totals where balanced_balesid = $found ";
                                    //$sql = "select * from login";
                                    if ($conn->query($user_sql1) === TRUE) {


                                        $user_sql1 = "DELETE FROM balanced_bales where id = $found ";
                                        //$sql = "select * from login";
                                        if ($conn->query($user_sql1) === TRUE) {
                                            $temp = array("response" => "success");
                                            array_push($response, $temp);
                                        }else {

                                            $temp = array("response" => $conn->error);
                                            array_push($response, $temp);
                                        }

//                                        $user_sql = "update balanced_bales set stoporder_processed=0,sale_batchid=0 where sale_batchid= $batch";
//                                        //$sql = "select * from login";
//                                        if ($conn->query($user_sql) === TRUE) {
//
//                                            $temp = array("response" => "success");
//                                            array_push($data1, $temp);
//
//                                        } else {
//
//                                            $temp = array("response" => $conn->error);
//                                            array_push($response, $temp);
//                                        }


                                    }

                                }
                            }
                        }
                    }
                }
            }
        }
    }else if ($ref_id>0 && $batch==0){
        $sql = "Select * from balanced_bales  where stoporder_processed=1 and id=$ref_id limit 1";
        $result = $conn->query($sql);
        $fetched_records = $result->num_rows;
        if ($result->num_rows > 0) {
            // output data of each rowi
            while ($row = $result->fetch_assoc()) {
                $found = $row["id"];

                if ($found > 0) {

                    $user_sql1 = "DELETE FROM internal_stop_order_deducted_amounts where balanced_balesid = $found ";
                    //$sql = "select * from login";
                    if ($conn->query($user_sql1) === TRUE) {

                        $user_sql1 = "DELETE FROM daily_totals_net_amount where balanced_balesid = $found ";
                        //$sql = "select * from login";
                        if ($conn->query($user_sql1) === TRUE) {

                            $user_sql1 = "DELETE FROM balanced_bales_charges where balanced_balesid = $found ";
                            //$sql = "select * from login";
                            if ($conn->query($user_sql1) === TRUE) {

                                $user_sql1 = "DELETE FROM daily_totals_split_amounts where balanced_balesid = $found ";
                                //$sql = "select * from login";
                                if ($conn->query($user_sql1) === TRUE) {

                                    $user_sql1 = "DELETE FROM daily_totals where balanced_balesid = $found ";
                                    //$sql = "select * from login";
                                    if ($conn->query($user_sql1) === TRUE) {


                                        $user_sql1 = "DELETE FROM balanced_bales where id = $found ";
                                        //$sql = "select * from login";
                                        if ($conn->query($user_sql1) === TRUE) {
                                            $temp = array("response" => "success");
                                            array_push($response, $temp);
                                        }else {

                                            $temp = array("response" => $conn->error);
                                            array_push($response, $temp);
                                        }

//                                        $user_sql = "update balanced_bales set stoporder_processed=0,sale_batchid=0 where sale_batchid= $batch";
//                                        //$sql = "select * from login";
//                                        if ($conn->query($user_sql) === TRUE) {
//
//                                            $temp = array("response" => "success");
//                                            array_push($data1, $temp);
//
//                                        } else {
//
//                                            $temp = array("response" => $conn->error);
//                                            array_push($response, $temp);
//                                        }


                                    }

                                }
                            }
                        }
                    }
                }
            }
        }

    }



}

echo json_encode($response);