<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type:application/json");
header("Access-Control-Allow-Origin-Methods:POST");
header("Access-Control-Allow-Headers:Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Origin-Methods,Authorization,X-Requested-With");

require_once("conn.php");


$data = json_decode(file_get_contents("php://input"));

$response=array();

if (isset($data->transporterid) && isset($data->grower_num) && isset($data->selling_pointid)){


    $userid=$data->userid;
    $transporterid=$data->transporterid;
    $grower_num=$data->grower_num;
    $selling_pointid=$data->selling_pointid;
    $bales=$data->bales;
    $created_at=$data->created_at;
    $growerid=0;


    $sql = "Select * from growers where grower_num='$grower_num'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // output data of each row
        while($row = $result->fetch_assoc()) {
            // echo "id: " . $row["id"]. " - Name: " . $row["firstname"]. " " . $row["lastname"]. "<br>";

            $growerid=$row["id"];

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




    if ($growerid>0){

        $user_sql = "INSERT INTO transporter_growers(userid,transporterid,growerid,selling_pointid,bales,created_at) VALUES ($userid,$transporterid,$growerid,$selling_pointid,$bales,'$created_at')";
        //$sql = "select * from login";
        if ($conn->query($user_sql)===TRUE) {

                $last_id = $conn->insert_id;
                $temp = array("response" => "success");
                array_push($response, $temp);

        }else{
            $temp=array("response"=>$conn->error);
            array_push($response,$temp);
        }

    }




}




echo json_encode($response);