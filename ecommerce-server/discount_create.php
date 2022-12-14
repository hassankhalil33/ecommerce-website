<?php

//Takes in: seller_id and percentage
//Returns success message if insert query true
//otherwise returns error message

// include the headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");

//include the connection to the database
include("connection.php");

//decalre the post varaibales
$seller_id= $_POST["seller_id"];
$percentage= $_POST["percentage"];

//decalare length of the discount code
$length = 6;
//genarte the code from numbers and letter
$code= substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'),1,$length);

//insert query to the table discounts
$add_discount = $mysql -> prepare("INSERT INTO discounts(seller_id,percentage,code) VALUE (?, ?, ?)");

if ($add_discount=== false) {
    die(json_encode("error: " . $mysql -> error));
};

//execute the query
$add_discount -> bind_param("sss", $seller_id,$percentage,$code);
$add_discount -> execute();

// send the resposne with succces message
echo json_encode($code);

?>
