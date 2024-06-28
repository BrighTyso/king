<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type:application/json");
header("Access-Control-Allow-Origin-Methods:POST");
header("Access-Control-Allow-Headers:Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Origin-Methods,Authorization,X-Requested-With");

require_once("conn.php");


$data = json_decode(file_get_contents("php://input"));

$response=array();


if (isset($data->userid)) {
    $grower_already_sold = 0;
    $number_of_bales = 0;
    $gross_amount = 0.0;
    $sold_bales = 0;
    $rejected_bales = 0;
    $average_price = 0;
    $all_prices = 0;
    $exchange_rateid = 0;
    $exchange_rate_amount = 0;
    $zim_value = 0;
    $selling_date = "";

    $userid = $data->userid;
    $transporter_growersid = $data->transporter_growersid;
    $splitid = $data->splitid;
    $created_at = $data->created_at;
    $growerid = $data->growerid;
    $grower_ytd_mass = 0;
    $split_bales = 0;
    //$growerid = 0;
    $selling_dateid = 0;
    $junused_tickets_bales = 0;
    $bales_booked=0;


    $sql = "Select distinct grower_num,bale_junus.transporter_growersid,splitid,lot,bale_group,barcode,price,buyer_grades.description as buyer_grade , timb_grades.description as timb_grade from bale_junus join growers on growers.id=bale_junus.growerid join tickets on tickets.bale_junusid=bale_junus.id 
    left join sold_bales on sold_bales.ticketsid=tickets.id left join timb_grades on timb_grades.id=sold_bales.timb_gradesid left join buyer_grades on buyer_grades.id=sold_bales.buyer_gradesid where transporter_growersid=$transporter_growersid and splitid=$splitid";
    $result = $conn->query($sql);
    $split_bales = $result->num_rows;


    $sql = "Select distinct growers.id as growerid,grower_num,bale_junus.transporter_growersid,splitid,lot,bale_group,barcode,price,buyer_grades.description as buyer_grade,start_of_day.description as selling_date ,start_of_day.id as selling_dateid , timb_grades.description as timb_grade,mass,sale_code,exchange_rate.amount as exchange_rate_amount,exchange_rate.id as exchange_rateid from bale_junus join growers on growers.id=bale_junus.growerid join tickets on tickets.bale_junusid=bale_junus.id 
    left join sold_bales on sold_bales.ticketsid=tickets.id  join timb_grades on timb_grades.id=sold_bales.timb_gradesid  join buyer_grades on buyer_grades.id=sold_bales.buyer_gradesid join bale_mass on bale_mass.id=bale_junus.bale_massid join start_of_day on tickets.start_of_dayid=start_of_day.id join exchange_rate on start_of_day.id=exchange_rate.start_of_dayid  where transporter_growersid=$transporter_growersid and splitid=$splitid";
    $result = $conn->query($sql);
    $number_of_bales = $result->num_rows;


    if ($result->num_rows > 0) {
        // output data of each row
        while ($row = $result->fetch_assoc()) {
            // echo "id: " . $row["id"]. " - Name: " . $row["firstname"]. " " . $row["lastname"]. "<br>";
            if ($row["sale_code"] == "") {

                $grower_ytd_mass += $row["mass"];
                $gross_amount += $row["mass"] * ($row["price"] / 100);
                $sold_bales += 1;
                $all_prices += $row["price"] / 100;
                $exchange_rateid = $row["exchange_rateid"];
                $exchange_rate_amount = $row["exchange_rate_amount"];
                $selling_date = $row["selling_date"];
                $selling_dateid = $row["selling_dateid"];
                //$growerid=$row["growerid"];

            } else {
                $rejected_bales += 1;
            }

        }
    }


    $sql = "Select transporter_growers.growerid,transporter_growers.bales from bale_junus join tickets on tickets.bale_junusid=bale_junus.id join transporter_growers on transporter_growers.id=bale_junus.transporter_growersid where tickets.start_of_dayid=$selling_dateid and transporter_growers.growerid=$growerid";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // output data of each row
        while ($row = $result->fetch_assoc()) {
            // echo "id: " . $row["id"]. " - Name: " . $row["firstname"]. " " . $row["lastname"]. "<br>"
            $junused_tickets_bales = $row["bales"];

        }
    }


// Unix time = 1685491200
    $unixTime = strtotime($selling_date);
// Pass the new date format as a string and the original date in Unix time
    $newDate = date("d-M-y", $unixTime);



    $sql = "Select * from booked_bales where growerid=$growerid and sell_date='$newDate'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        // output data of each row
        while ($row = $result->fetch_assoc()) {
            // echo "id: " . $row["id"]. " - Name: " . $row["firstname"]. " " . $row["lastname"]. "<br>";
            //$bale_booked=$row["id"];
            $bales_booked = $row["bales_booked"];
        }
    }


    if ($number_of_bales > 0 && $split_bales == $number_of_bales && $bales_booked <= $junused_tickets_bales && $bales_booked>0) {

        $average_price = $all_prices / $number_of_bales;
        $zim_value = $gross_amount * $exchange_rate_amount;
        $already_balanced = 0;


        $sql = "Select * from balanced_bales where growerid=$growerid";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            // output data of each row
            while ($row = $result->fetch_assoc()) {
                // echo "id: " . $row["id"]. " - Name: " . $row["firstname"]. " " . $row["lastname"]. "<br>";
                $grower_already_sold += $row["id"];


            }
        }


        $sql = "Select * from balanced_bales where splitid=$splitid and transporter_growersid=$transporter_growersid";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            // output data of each row
            while ($row = $result->fetch_assoc()) {
                // echo "id: " . $row["id"]. " - Name: " . $row["firstname"]. " " . $row["lastname"]. "<br>";
                $already_balanced = $row["id"];

            }
        }


        if ($already_balanced == 0) {


            $user_sql = "INSERT INTO balanced_bales(userid, splitid, growerid,transporter_growersid,created_at ) VALUES ($userid,$splitid,$growerid,$transporter_growersid,'$created_at')";
            //$sql = "select * from login";
            if ($conn->query($user_sql) === TRUE) {

                $last_id = $conn->insert_id;


                $user_sql = "INSERT INTO daily_totals(userid,balanced_balesid,exchange_rateid,mass,usd_value,zim_value,laid_bales,sold_bales,rejected_bales,average_price,created_at ) VALUES ($userid,$last_id,$exchange_rateid,$grower_ytd_mass,$gross_amount,$zim_value,$number_of_bales,$sold_bales,$rejected_bales,$average_price,'$created_at')";
                //$sql = "select * from login";
                if ($conn->query($user_sql) === TRUE) {

                    $last_id = $conn->insert_id;
                    $temp = array("response" => "success");
                    array_push($response, $temp);

                }
//
            } else {
                $temp = array("response" => $conn->error);
                array_push($response, $temp);
            }

        } else {

            $temp = array("response" => "Already balanced");
            array_push($response, $temp);

        }


    } else {
        if ($bales_booked < $junused_tickets_bales) {

            $temp = array("response" => "Booking===> Bales received($junused_tickets_bales) are more than bales booked($bales_booked).");
            array_push($response, $temp);

        } else {
            if ($split_bales > $number_of_bales) {
                $c = $split_bales - $number_of_bales;
                $temp = array("response" => $c . " bales Need Correction ");
                array_push($response, $temp);
            } else {
                $temp = array("response" => "Zero bales proccessed");
                array_push($response, $temp);
            }
        }

    }
}

echo json_encode($response);