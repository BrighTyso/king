<?php
require_once("conn.php");
if(isset($_POST["seasonid"]) && isset($_POST["transporterid"])  && isset($_POST["grower_num"])
    && isset($_POST["selling_pointid"]) && isset($_POST["bales"]) && isset($_POST["created_at"])){

    $userid=$_POST["userid"];
    $transporterid=$_POST["transporterid"];
    $grower_num=$_POST["grower_num"];
    $selling_pointid=$_POST["selling_pointid"];
    $bales=$_POST["bales"];
    $created_at=$_POST["created_at"];



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