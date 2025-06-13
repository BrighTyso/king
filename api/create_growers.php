<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type:application/json");
header("Access-Control-Allow-Origin-Methods:POST");
header("Access-Control-Allow-Headers:Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Origin-Methods,Authorization,X-Requested-With");

require_once("conn.php");

$data = json_decode(file_get_contents("php://input"));

$found=0;

$response=array();

if (isset($data->userid)){


    $userid=$data->userid;
    $selling_pointid=0;
    $grower_num=$data->grower_num;
    $name=$data->name;
    $surname=$data->surname;
    $national_id=$data->national_id;
    $phone=$data->phone;
    $address1=$data->address1;
    $address2=$data->address2;
    $contractor=$data->contractor;
    $farm_name=$data->farm_name;
    $province=$data->province;
    $district=$data->district;
    $dry_land_ha=$data->dry_land_ha;
    $irr_land_ha=$data->irr_land_ha;
    $dryland=$data->dryland;
    $created_at=date("Y-m-d");



    $sql = "Select * from selling_point where floor_id='$contractor' limit 1";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // output data of each row
        while($row = $result->fetch_assoc()) {
            // echo "id: " . $row["id"]. " - Name: " . $row["firstname"]. " " . $row["lastname"]. "<br>";

            $selling_pointid=$row["id"];

        }
    }




    $sql = "Select * from growers where grower_num='$grower_num'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // output data of each row
        while($row = $result->fetch_assoc()) {
            // echo "id: " . $row["id"]. " - Name: " . $row["firstname"]. " " . $row["lastname"]. "<br>";

            $found=$row["id"];

        }
    }


    if ($found==0 && $selling_pointid>0) {
        $user_sql = "INSERT INTO growers(userid,selling_pointid,grower_num,name,surname,national_id,phone,address1,address2,contractor,farm_name,province,district,dry_land_ha,irr_land_ha,dryland,created_at
) VALUES ($userid,$selling_pointid,'$grower_num','$name','$surname','$national_id','$phone','$address1','$address2','$contractor','$farm_name','$province','$district','$dry_land_ha','$irr_land_ha','$dryland','$created_at'
)";
        //$sql = "select * from login";
        if ($conn->query($user_sql)===TRUE) {

            $last_id = $conn->insert_id;

            $temp=array("response"=>"success");
            array_push($response,$temp);

        }else{

            $temp=array("response"=>"failed To Update Seasons");
            array_push($response,$temp);

        }

    }else{

        if($selling_pointid>0) {
            $user_sql1 = "update growers set selling_pointid=$selling_pointid where id= $found";
            //$sql = "select * from login";
            if ($conn->query($user_sql1) === TRUE) {


                $last_id = $conn->insert_id;
                $temp = array("response" => "success","contractor" => $contractor);
                array_push($response, $temp);


            } else {
                $temp = array("response" => "failed","contractor" => $contractor);
                array_push($response, $temp);

            }
        }else{
            $temp = array("response" => "selling point ","contractor" => $contractor);
            array_push($response, $temp);
        }

    }


}else{

    $temp=array("response"=>"Field Empty");
    array_push($response,$temp);
}


echo json_encode($response)



?>





