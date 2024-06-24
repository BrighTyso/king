<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type:application/json");
header("Access-Control-Allow-Origin-Methods:POST");
header("Access-Control-Allow-Headers:Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Origin-Methods,Authorization,X-Requested-With");

require_once("conn.php");


$data = json_decode(file_get_contents("php://input"));

$response=array();


$daily_totals=array();
$daily_totals_split_amounts=array();
$daily_totals_net_amount=array();

$balanced_bales_charges=array();
$internal_stop_order_deducted_amounts=array();
$bales=array();

if (isset($data->userid)) {

    $selling_pointid=$data->selling_pointid;
    $balanced_balesid=0;

    $sql = "Select balanced_bales.transporter_growersid,balanced_bales.splitid,balanced_bales.id from balanced_bales join transporter_growers on transporter_growers.id=balanced_bales.transporter_growersid join growers on growers.id=transporter_growers.growerid where stoporder_processed=1 and printed=0 and growers.selling_pointid=$selling_pointid";
    $result = $conn->query($sql);
    $fetched_records = $result->num_rows;
    if ($result->num_rows > 0) {
        // output data of each rowi
        while ($row = $result->fetch_assoc()) {

            $balanced_balesid=$row["id"];
            $transporter_growersid=$row["transporter_growersid"];
            $splitid=$row["splitid"];

            $daily_totals=array();
            $daily_totals_split_amounts=array();
            $daily_totals_net_amount=array();

            $balanced_bales_charges=array();
            $internal_stop_order_deducted_amounts=array();
            $bales=array();




            $sql1 = "Select distinct sold_bales.id,sale_code,grower_num,bale_junus.transporter_growersid,splitid,lot,bale_group,barcode,price,buyer_grades.description as buyer_grade,mass , timb_grades.description as timb_grade from bale_junus join growers on growers.id=bale_junus.growerid join bale_mass on bale_mass.id=bale_junus.bale_massid join tickets on tickets.bale_junusid=bale_junus.id 
             join sold_bales on sold_bales.ticketsid=tickets.id  join timb_grades on timb_grades.id=sold_bales.timb_gradesid  join buyer_grades on buyer_grades.id=sold_bales.buyer_gradesid where transporter_growersid=$transporter_growersid and splitid=$splitid";
            $result1 = $conn->query($sql1);

            if ($result1->num_rows > 0) {
                // output data of each row
                while($row1 = $result1->fetch_assoc()) {
                    // echo "id: " . $row["id"]. " - Name: " . $row["firstname"]. " " . $row["lastname"]. "<br>";

                    $temp=array("sale_code"=>$row1["sale_code"],"soldbaleid"=>$row1["id"],"transporter_growersid"=>$row1["transporter_growersid"],"grower_num"=>$row1["grower_num"],"mass"=>$row1["mass"],"splitid"=>$row1["splitid"],"lot"=>$row1["lot"],"bale_group"=>$row1["bale_group"],"buyer_grade"=>$row1["buyer_grade"],"timb_grade"=>$row1["timb_grade"],"barcode"=>$row1["barcode"],"price"=>$row1["price"]);
                    array_push($bales,$temp);

                }
            }




            $sql1 = "Select mass,usd_value,zim_value,laid_bales,sold_bales,rejected_bales,average_price,created_at,amount from daily_totals join exchange_rate on daily_totals.exchange_rateid=exchange_rate.id where balanced_balesid=$balanced_balesid";
            $result1 = $conn->query($sql1);
            if ($result1->num_rows > 0) {
                // output data of each row
                while ($row1 = $result1->fetch_assoc()) {

                    $temp = array("mass" => $row1["mass"],"usd_value" => $row1["usd_value"]
                    ,"zim_value" => $row1["zim_value"],"laid_bales" => $row1["laid_bales"],"sold_bales" => $row1["sold_bales"]
                    ,"rejected_bales" => $row1["rejected_bales"],"average_price" => $row1["average_price"],"exchange_rate" => $row1["amount"],"created_at" => $row1["created_at"]);
                    array_push($daily_totals, $temp);
                }
            }


            $sql1 = "Select usd_split_value,zim_split_value from daily_totals_split_amounts where balanced_balesid=$balanced_balesid";
            $result1 = $conn->query($sql1);
            if ($result1->num_rows > 0) {
                // output data of each row
                while ($row1 = $result1->fetch_assoc()) {
                    $temp = array("usd_split_value" => $row1["usd_split_value"],"zim_split_value" => $row1["zim_split_value"]);
                    array_push($daily_totals_split_amounts, $temp);
                }
            }



            $sql1 = "Select usd_value,zim_value,amount,statutory.description from balanced_bales_charges join grower_charges on balanced_bales_charges.grower_chargeid=grower_charges.id join statutory on grower_charges.statutoryid=statutory.id where balanced_balesid=$balanced_balesid";
            $result1 = $conn->query($sql1);
            if ($result1->num_rows > 0) {
                // output data of each row
                while ($row1 = $result1->fetch_assoc()) {
                    $temp = array("usd_value" => $row1["usd_value"],"zim_value" => $row1["zim_value"],"description" => $row1["description"]);
                    array_push($balanced_bales_charges, $temp);
                }
            }



            $sql1 = "Select internal_stop_order_deducted_amounts.amount,internal_stop_order.description from internal_stop_order_deducted_amounts join split_internal_stop_order on split_internal_stop_order.id=internal_stop_order_deducted_amounts.split_internal_stop_orderid join internal_stop_order_amount on internal_stop_order_amount.id=split_internal_stop_order.internal_stop_order_amountid join internal_stop_order on internal_stop_order.id=internal_stop_order_amount.internal_stop_orderid where balanced_balesid=$balanced_balesid";
            $result1 = $conn->query($sql1);
            if ($result1->num_rows > 0) {
                // output data of each row
                while ($row1 = $result1->fetch_assoc()) {
                    $temp = array("amount" => $row1["amount"],"description" => $row1["description"]);
                    array_push($internal_stop_order_deducted_amounts, $temp);
                }
            }




            $sql1 = "Select usd_net_value,zim_net_value from daily_totals_net_amount where balanced_balesid=$balanced_balesid";
            $result1 = $conn->query($sql1);
            if ($result1->num_rows > 0) {
                // output data of each row
                while ($row1 = $result1->fetch_assoc()) {
                    $temp = array("usd_net_value" => $row1["usd_net_value"],"zim_net_value" => $row1["zim_net_value"]);
                    array_push($daily_totals_net_amount, $temp);
                }
            }


            $temp = array("daily_totals" => $daily_totals,
                "daily_totals_split_amounts" => $daily_totals_split_amounts,"daily_totals_net_amount" => $daily_totals_net_amount
            ,"balanced_bales_charges" => $balanced_bales_charges,"internal_stop_order_deducted_amounts" => $internal_stop_order_deducted_amounts
            ,"bales" => $bales);
            array_push($response, $temp);

        }
    }





}

echo json_encode($response);