<?php 
$username="root";
$host="localhost";
$database="king";
$password="";


$conn=new mysqli($host,$username,$password,$database);

if($conn->connect_error){
die("connection field ". $conn->connect_error);
}

?>