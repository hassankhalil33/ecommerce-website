<?php

//take user_id and voucher code
//update user_id in table voucher if code is exsist
//else return error message

// include the headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");

//include the connection to the database
include("connection.php");

//decalre the post varaibales
$user_id= $_POST["user_id"];
$code= $_POST["code"];


$get_amount = $mysql -> prepare("SELECT amount FROM vouchers WHERE user_id = $user_id and code='$code'");

//execute the select query
$get_amount -> execute();
$array = $get_amount -> get_result();
$response = [];

//put the data in the array
while($info  = $array -> fetch_assoc()){
    $response[] = $info;
};

$json=json_encode($response);
$amount=$response[0]['amount'];

//udpate the user_id on the insert code
$add_amount = $mysql -> prepare("UPDATE users set money= money+$amount where id = $user_id ");

//execute the update query
$add_amount -> execute();

$delete_voucher = $mysql -> prepare("DELETE from vouchers where user_id = $user_id and code='$code'");
$delete_voucher -> execute();

echo json_encode("voucher applied");

?>