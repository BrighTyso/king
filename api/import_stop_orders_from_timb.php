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

    $key = "";
    $iv = ""; // Make sure it matches the one used during encryption


    if (file_put_contents($path, base64_decode($file))) {

        $fh = fopen($path,'r');
        while ($line = fgets($fh)) {
            // <... Do your work with the line ...>
            //echo($line);
            $ciphertext=$line;
        }
        fclose($fh);
    }





    $sql = "Select * from selling_point join stop_order_keys on stop_order_keys.selling_pointid=selling_point.id where floor_code='$floor_code'   limit 1";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // output data of each row
        while($row = $result->fetch_assoc()) {
            // echo "id: " . $row["id"]. " - Name: " . $row["firstname"]. " " . $row["lastname"]. "<br>";
            $key = $row["system_key"];
            $iv = $row["vector"];

        }
    }



        if ($key!="" && $iv!=""){

            //$ciphertext = "p57J/R5c2tn+nrPnk8mzHbQr4vJ18dEltCJj93SuUMd6InEpnnHfjlgm4Hh3pwAaVwMN9zrKES/YgXl7LWWqMprQ74Sr0lQR1R8/HIo+Sbc1FO/mYghDJ12Q5eQv7NY6gUSGD23wF+5IbgNbq4cP6wy4Z22ZJw1G/tV4o4vGAecbx4scts2Yh5uanyxm7r3mR6nG/1b50FXRO8ETN3pgL9sr5wFbvDbgQgCZQs1bdm33eOzaaqxgUKXqBy/knhxJIDdclLSo0EraehnPccoiGGMvAUvJZ0FtwKDYIT4cKbSZ4N8CWCVyAlqR3S9aV8avYbiJtR3gW8Jf79H9gfa2uEHv73zWpuMXdbKMMBi1fRzCsndp7MMDTXkvGxA86vn0QQy7GOMeMpm6j5bAJDJxw18wthz8lH/CZpob9KtQDX6/dPdrEbkA/8pqUToYJUbI5FqgUzFazYO8gh4jKd2x0/jzmnJJW/tIqIvUnvJxNOTxzBdM0DWqs1NXWquAif3pZS45mactzW4sOPzbku/JK4cVx4X1YtjpfJRaXAB/aeQF5Dz0udbUH4ji9/Ug3dnT+s5MDGAO+82ERitdQ0K5HS8Dx4PhsCvVZhlQBqlFtdamVNhBbXyt8Gswmnh1iDwEKBQr+cNzUcmviu48qeVNVVlswvfquOMkhLMgLFoJ4F75dvutXD1qUwjYbdWmdR8ClXTyKGJAhvyNUMBl4y53sN+sY4whFpObOMEOYt6jOlpOAdwfz+8KzwDY4jDZmL/1SyuroX7JjXoFfDV5UJ1J6HYNwzQ5gTZ1P6Mw03k/POa845VKAjSJzM4D97rInqKPRCjsdQgcvjz7OcQUuvTxLB2jaxiYLQxjW+0Lps2RC8M=";
            $encryptedData = $ciphertext;
            $cipherMethod = "AES256";
            //$key = "E13ED75C8046B825973B35C7AA5946F3";
            $options = 0; // You can adjust this based on your needs
            //$iv = "142a2bbb11c64825"; // Make sure it matches the one used during encryption

            $decryptedData = openssl_decrypt($encryptedData, $cipherMethod, $key, $options, $iv);
            if ($decryptedData !== false) {
                //echo $decryptedData;
                $array = explode("\n", $decryptedData);
                for($i = 0; $i < count($array); $i++) {
                    $data_array=explode("+", $array[$i]);
                    $first_data_array=explode("", $data_array[0]);
                    echo json_encode($first_data_array);
                    //echo json_encode($data_array);
                }


            } else {
                //echo "Decryption failed.";
            }

        }else{
            $temp = array("response" => "No Key");
            array_push($response, $temp);
        }

}

echo json_encode($decryptedData);