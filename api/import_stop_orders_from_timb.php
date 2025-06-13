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
    $ciphertext=$data->description;
    $selling_pointid=$data->selling_pointid;
    $seasonid=$data->seasonid;
    $key="";
    $iv="";
    $floor_code="";

    $count=0;
    $fetched=0;

    $key = "";
    $iv = ""; // Make sure it matches the one used during encryption

    $sql = "Select * from selling_point join stop_order_keys on stop_order_keys.selling_pointid=selling_point.id where selling_point.id=$selling_pointid   limit 1";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // output data of each row
        while($row = $result->fetch_assoc()) {
            // echo "id: " . $row["id"]. " - Name: " . $row["firstname"]. " " . $row["lastname"]. "<br>";
            $key = $row["system_key"];
            $iv = $row["vector"];

        }
    }

    function splitStringByLengths( $dataString, $lengths)
    {
        $result = []; // Initialize an empty array to store the split segments
        $currentPosition = 0; // Keep track of the current starting position in the data string

        // Iterate through each specified length in the $lengths array
        foreach ($lengths as $segmentLength) {
            // Check if there are enough characters remaining in the string for the current segment length
            if ($currentPosition + $segmentLength <= strlen($dataString)) {
                // Extract the substring using substr() and add it to the result array
                $result[] = substr($dataString, $currentPosition, $segmentLength);
                // Move the current position forward by the length of the extracted segment
                $currentPosition += $segmentLength;
            } else {
                // If the remaining string is shorter than the requested segmentLength,
                // extract the rest of the string and stop processing further lengths.
                // This prevents an error if the sum of lengths exceeds the string length.
                if ($currentPosition < strlen($dataString)) { // Ensure there's anything left to extract
                    $result[] = substr($dataString, $currentPosition);
                }
                break; // Exit the loop as we've consumed the rest of the string
            }
        }

        return $result; // Return the array of split substrings
    }




    function formatYYYYMMDDToYYYY_MM_DD($dateString)
    {
        // Define the expected input format (YYYYMMDD)
        $inputFormat = 'Ymd';

        // Define the desired output format (YYYY-MM-DD)
        $outputFormat = 'Y-m-d';

        // Attempt to create a DateTime object from the input string using the specified format.
        // DateTime::createFromFormat() returns false on failure.
        $date = DateTime::createFromFormat($inputFormat, $dateString);

        // Check if the parsing was successful and if there were no warnings/errors during parsing.
        // The check for errors is important because createFromFormat might successfully parse *some*
        // parts of an invalid date string, but still mark it as invalid.
        if ($date && $date->format($inputFormat) === $dateString) {
            // If parsing was successful, format the DateTime object into the desired output string.
            return $date->format($outputFormat);
        } else {
            // Handle the case where the input string is not in the expected YYYYMMDD format.
            return "Error: Could not parse date string '{$dateString}'. Please ensure it is in YYYYMMDD format.";
        }
    }

// The input date string you want to format




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
                    //$data_array=explode("+", $array[$i]);
                    //$first_data_array=explode("", $data_array[0]);


                    //echo json_encode($decryptedData);
                    //echo json_encode($array);

                    $segmentLengths = [1, 2, 1, 6, 2, 4, 2, 10, 14, 21, 21, 21, 8, 8, 1, 2];

// Call the function to split the string without any special space-skipping logic
                    $splitData = splitStringByLengths($array[$i], $segmentLengths);

// Output the results in a readable format for verification

                    try {


                    $sale_id=trim($splitData[0]);
                    $record_type=trim($splitData[1]);
                    $grower_type=trim($splitData[2]);
                    $grower=trim($splitData[3]);
                    $grower_suffix=trim($splitData[4]);
                    $creditor_no=trim($splitData[5]);
                    $priority=trim($splitData[6]);
                    $creditor_ref=trim($splitData[7]);
                    $account_no=trim($splitData[8]);
                    $amount_1=trim($splitData[9]);
                    $amount_2=trim($splitData[10]);
                    $amount_3=trim($splitData[11]);
                    $percent=trim($splitData[12]);
                    $date=trim($splitData[13]);
                    $type=trim($splitData[14]);
                    $serial_no=trim($splitData[15]);

// Call the function to format the date
                    $formattedDate = formatYYYYMMDDToYYYY_MM_DD($date);

                    //echo $formattedDate;

                    $grower_num=$grower_type."".$grower.$grower_suffix;

                    $growerid=0;
                    $sosid=0;

                    $sql = "Select * from growers  where grower_num='$grower_num' limit 1";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        // output data of each row
                        while($row = $result->fetch_assoc()) {
                            // echo "id: " . $row["id"]. " - Name: " . $row["firstname"]. " " . $row["lastname"]. "<br>";
                            $growerid=$row["id"];

                        }
                    }


                        $sql = "Select * from stoporders  where growerid=$growerid and creditor_no='$creditor_no' and sos_date='$$date'  limit 1";
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            // output data of each row
                            while($row = $result->fetch_assoc()) {
                                // echo "id: " . $row["id"]. " - Name: " . $row["firstname"]. " " . $row["lastname"]. "<br>";
                                $sosid=$row["id"];

                            }
                        }

                    if ($growerid>0 && $sosid==0){

                        $user_sql = "INSERT INTO stoporders(userid,selling_pointid,growerid,sale_id,record_type,grower_type,creditor_no,priority,creditor_ref,account_no,amount_1,amount_2,amount_3,percent,sos_date,type,serial_no,formatted_sos_date,seasonid)VALUES ($userid,$selling_pointid,$growerid,'$sale_id',$record_type,'$grower_type','$creditor_no',$priority,'$creditor_ref','$account_no','$amount_1','$amount_2','$amount_3',$percent,'$date',$type,'$serial_no','$formattedDate',$seasonid)";
                        //$sql = "select * from login";
                        if ($conn->query($user_sql)===TRUE) {

                            $last_id = $conn->insert_id;
                            $temp=array("response"=>"success");
                            array_push($response,$temp);
                        }else{
                            $temp=array("response"=>$conn->error);
                            array_push($response,$temp);
                        }

                    }

                    print_r($splitData);
                    //echo $array[$i];
                    echo "</pre>";

                    }catch (Exception $exception){

                    }



                }


            } else {
                //echo "Decryption failed.";
            }

        }else{
            $temp = array("response" => "No Key For the selling Point");
            array_push($response, $temp);
        }

}

echo json_encode($response);