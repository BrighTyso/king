<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type:application/json");
header("Access-Control-Allow-Origin-Methods:POST");
header("Access-Control-Allow-Headers:Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Origin-Methods,Authorization,X-Requested-With");

require_once("conn.php");


$data = json_decode(file_get_contents("php://input"));

$response=array();
$decryptedData="";
if (isset($data->userid)) {

    $userid=$data->userid;
    $file=$data->file;
    $name=$data->name;
    $floor_code=$data->floor_code;

    $file_name=$name.time().".txt";
    $path="../files/".$file_name;
    $line="";
    $ciphertext="";

//    $key = "40B977F5C9D2CE83B12EE5D45B7EA626";
//    // You can adjust this based on your needs
//    $iv = "9839ff6f82e27155"; // Make sure it matches the one used during encryption


    $key = "";
    // You can adjust this based on your needs
    $iv = "";

    if (file_put_contents($path, base64_decode($file))) {

        $fh = fopen($path,'r');
        while ($line = fgets($fh)) {
            // <... Do your work with the line ...>
            //echo($line);
            $ciphertext=$line;
        }
        fclose($fh);
    }


    $sql = "Select * from selling_point join booking_keys on booking_keys.selling_pointid=selling_point.id where floor_id='$floor_code'   limit 1";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // output data of each row
        while($row = $result->fetch_assoc()) {
            // echo "id: " . $row["id"]. " - Name: " . $row["firstname"]. " " . $row["lastname"]. "<br>";
            $key = $row["system_key"];
            $iv = $row["vector"];
        }
    }


    if ($key!="" && $iv!="") {
        //$ciphertext = "p57J/R5c2tn+nrPnk8mzHbQr4vJ18dEltCJj93SuUMd6InEpnnHfjlgm4Hh3pwAaVwMN9zrKES/YgXl7LWWqMprQ74Sr0lQR1R8/HIo+Sbc1FO/mYghDJ12Q5eQv7NY6gUSGD23wF+5IbgNbq4cP6wy4Z22ZJw1G/tV4o4vGAecbx4scts2Yh5uanyxm7r3mR6nG/1b50FXRO8ETN3pgL9sr5wFbvDbgQgCZQs1bdm33eOzaaqxgUKXqBy/knhxJIDdclLSo0EraehnPccoiGGMvAUvJZ0FtwKDYIT4cKbSZ4N8CWCVyAlqR3S9aV8avYbiJtR3gW8Jf79H9gfa2uEHv73zWpuMXdbKMMBi1fRzCsndp7MMDTXkvGxA86vn0QQy7GOMeMpm6j5bAJDJxw18wthz8lH/CZpob9KtQDX6/dPdrEbkA/8pqUToYJUbI5FqgUzFazYO8gh4jKd2x0/jzmnJJW/tIqIvUnvJxNOTxzBdM0DWqs1NXWquAif3pZS45mactzW4sOPzbku/JK4cVx4X1YtjpfJRaXAB/aeQF5Dz0udbUH4ji9/Ug3dnT+s5MDGAO+82ERitdQ0K5HS8Dx4PhsCvVZhlQBqlFtdamVNhBbXyt8Gswmnh1iDwEKBQr+cNzUcmviu48qeVNVVlswvfquOMkhLMgLFoJ4F75dvutXD1qUwjYbdWmdR8ClXTyKGJAhvyNUMBl4y53sN+sY4whFpObOMEOYt6jOlpOAdwfz+8KzwDY4jDZmL/1SyuroX7JjXoFfDV5UJ1J6HYNwzQ5gTZ1P6Mw03k/POa845VKAjSJzM4D97rInqKPRCjsdQgcvjz7OcQUuvTxLB2jaxiYLQxjW+0Lps2RC8M=";
        $encryptedData = $ciphertext;
        $cipherMethod = "AES256";
        //$key = "40B977F5C9D2CE83B12EE5D45B7EA626";
        $options = 0; // You can adjust this based on your needs
        //$iv = "9839ff6f82e27155"; // Make sure it matches the one used during encryption


        $decryptedData = openssl_decrypt($encryptedData, $cipherMethod, $key, $options, $iv);
        if ($decryptedData !== false) {
            //echo $decryptedData;
            $array = explode("\r\n", $decryptedData);
            for ($i = 0; $i < count($array); $i++) {

                echo json_encode($array[$i]);
                // if($i>0){
                $file_data = explode(",", $array[$i]);

                if ($i > 0) {

                    $growerid = 0;
                    $ar = (array)$file_data;

                    if (count($file_data) > 5) {


                        $book_date = json_encode($ar[0]);
                        $grower_num = json_encode($ar[1]);
                        $comm_graded = json_encode($ar[2]);
                        $bales_booked = json_encode($ar[3]);
                        $bales_delivered = json_encode($ar[4]);
                        $bales_handled = json_encode($ar[5]);
                        $sell_date = json_encode($ar[6]);
                        $book_user = json_encode($ar[7]);
                        $ip_address = json_encode($ar[8]);
                        $floor = json_encode($ar[9]);
                        $sale = json_encode($ar[10]);
                        $booked_id = json_encode($ar[11]);
                        $reoffer = json_encode($ar[12]);
                        $booked_by = json_encode($ar[13]);
                        $prefered_rep = json_encode($ar[14]);
                        $last_sell = json_encode($ar[15]);
                        $prefered_time = json_encode($ar[16]);
                        $venue = json_encode($ar[17]);

                        $sql = "Select distinct * from growers where grower_num=$grower_num limit 1";
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            // output data of each row
                            while ($row = $result->fetch_assoc()) {
                                // echo "id: " . $row["id"]. " - Name: " . $row["firstname"]. " " . $row["lastname"]. "<br>";
                                $growerid = $row["id"];


                            }
                        }


                        $user_sql = "INSERT INTO booked_bales(userid,growerid,book_date,comm_graded,bales_booked,bales_delivered,bales_handled,sell_date,book_user,ip_address,floor,sale,booked_id
                                                        ,reoffer,booked_by,prefered_rep,last_sell,prefered_time,venue
                                ) VALUES ($userid,$growerid,$book_date,$comm_graded,$bales_booked,$bales_delivered,$bales_handled
                                ,$sell_date,$book_user,$ip_address,$floor,$sale,$booked_id
                                                        ,$reoffer,$booked_by,$prefered_rep,$last_sell,$prefered_time,$venue)";
                        //$sql = "select * from login";
                        if ($conn->query($user_sql) === TRUE) {

                            $last_id = $conn->insert_id;
//                            $temp=array("response"=>"success");
//                            array_push($response,$temp);
                        } else {
                            $temp = array("response" => "Failed");
                            array_push($response, $temp);
                        }

                    }


                }


//            for($x = 0; $x < count($file_data); $x++) {
//                echo $file_data[$x]."\n";
//            }


//

                // }

            }


        } else {
            //echo "Decryption failed.";
            $temp = array("response" => "No Key");
            array_push($response, $temp);
        }

    }else{

    }
}

echo json_encode($response);