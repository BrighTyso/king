<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type:application/json");
header("Access-Control-Allow-Origin-Methods:POST");
header("Access-Control-Allow-Headers:Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Origin-Methods,Authorization,X-Requested-With");

require_once("conn.php");

$data = json_decode(file_get_contents("php://input"));

$name="";
$created_at="";
$found=0;

$response=array();

if (isset($data->description) && isset($data->userid)){

    $description=$data->description;
    $seasonid=0;
    $userid=$data->userid;
    $amount=$data->amount;



    $sql = "Select * from start_of_day where description='$description'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // output data of each row
        while($row = $result->fetch_assoc()) {
            // echo "id: " . $row["id"]. " - Name: " . $row["firstname"]. " " . $row["lastname"]. "<br>";

            $found=$row["id"];

        }
    }

    $sql = "Select * from seasons order by id desc limit 1";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // output data of each row
        while($row = $result->fetch_assoc()) {
            // echo "id: " . $row["id"]. " - Name: " . $row["firstname"]. " " . $row["lastname"]. "<br>";

            $seasonid=$row["id"];

        }
    }


    if ($found==0 && $seasonid>0) {
        $user_sql = "INSERT INTO start_of_day(description,userid,seasonid) VALUES ('$description',$userid,$seasonid)";
        //$sql = "select * from login";
        if ($conn->query($user_sql)===TRUE) {

            $last_id = $conn->insert_id;

            $user_sql1 = "update start_of_day set active=0 where id != $last_id";
            //$sql = "select * from login";
            if ($conn->query($user_sql1)===TRUE) {

                    $user_sql = "INSERT INTO exchange_rate(userid,start_of_dayid,seasonid,amount) VALUES ($userid,$last_id,$seasonid,'$amount')";
                    //$sql = "select * from login";
                    if ($conn->query($user_sql)===TRUE) {

                        $last_id = $conn->insert_id;
                        $temp=array("response"=>"success");
                        array_push($response,$temp);
                    }else{
                        $temp=array("response"=>$conn->error);
                        array_push($response,$temp);
                    }

            }else{


                $temp=array("response"=>"failed");
                array_push($response,$temp);

            }


        }else{

            $temp=array("response"=>"failed To Update Seasons");
            array_push($response,$temp);

        }

    }else{


        $user_sql1 = "update start_of_day set active=0 where id != $found";
        //$sql = "select * from login";
        if ($conn->query($user_sql1)===TRUE) {

            $user_sql2 = "update start_of_day set active=1 where id = $found";
            //$sql = "select * from login";
            if ($conn->query($user_sql2)===TRUE) {

                $last_id = $conn->insert_id;
                $temp=array("response"=>"success");
                array_push($response,$temp);
            }

        }else{
            $temp=array("response"=>"failed");
            array_push($response,$temp);

        }

    }


}else{

    $temp=array("response"=>"Field Empty");
    array_push($response,$temp);
}


echo json_encode($response)



?>





