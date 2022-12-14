<?php

//Takes in: new name / new description / new password /new money
//Returns true if success
//otherwise returns error message

// include the headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");

//include the connection to the database
include("connection.php");

//decalre the input varaibales
$username = $_POST["username"];
$new_name = $_POST["new_name"];
$new_desc = $_POST["new_desc"];


//select info from the table sellers of specific username
$seller_info = $mysql -> prepare("SELECT name,description,date_joined FROM sellers WHERE username = '$username'");

if ($seller_info === false) {
    die(json_encode("error: " . $mysql -> error));
};

//execute the select query
$seller_info -> execute();
$array = $seller_info -> get_result();
$response = [];

//put the data in the response array
while($info  = $array -> fetch_assoc()){
    $response[] = $info;
};

//fetch the array and get the data
$name = $response[0]["name"];
$desc = $response[0]["description"];
$date_joined  = $response[0]["date_joined"];

//check if the varaibles is set
if (isset($new_name)) {
    $Name = $new_name;
} else {
    $Name = $name;
};

if (isset($new_desc)) {
    $Desc = $new_desc;
} else {
    $Desc  = $desc;
};

//decalre newpassword variable
$new_password = hash("sha256", $_POST["new_password"] . $date_joined . "thcaj5445");

//prepare the query update
$query = $mysql -> prepare ("UPDATE sellers set name='$Name', description='$Desc', password='$new_password' WHERE username ='$username'");

if ($query === false) {
    die(json_encode("error: " . $mysql -> error));
};

//execute the query
$query -> execute();

// send the resposne with succces message
echo json_encode("success");

?>
