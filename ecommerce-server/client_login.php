<?php

// Takes in: userName and password
// Returns: "Username Not Found!" or "Incorrect Password!" if failed
// Returns token if success

// example:

// {
//     "id": 2,
//     "userName": "LambdaTiger",
//     "token": "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c
//     2VybmFtZSI6IkxhbWJkYVRpZ2VyIiwidHlwZSI6ImNsaWVudCIsIm
//     lhdCI6IjE2NjM3OTUzMDkiLCJleHAiOiIxNjYzODA2MTA5In0=.c5c
//     9be36b4bdb6a3f47912bc65633022d8219ab330e9b9ba4f2de8eeecd4fd09"
// }

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");

include("connection.php");
include("token.php");

// Init Variables

$userName = $_POST["userName"];
$password = $_POST["password"];

// Functions

function retrieveDate($user, $mysql) {
    $query = $mysql -> prepare(
        "SELECT date_joined AS dj, id FROM users
        WHERE username = '$user'"
    );

    $query -> execute();
    $array = $query -> get_result();

    $response = [];

    while($i = $array -> fetch_assoc()) {
        $response[] = $i;
    };

    if($response == null) {
        die(json_encode("Username Not Found!"));
    };

    return $response;
};

function checkPassword($user, $pass, $date, $mysql) {
    $hashedPassword = hash("sha256", $pass . $date . "thcaj5445");

    $query = $mysql -> prepare(
        "SELECT username FROM users
        WHERE username = '$user' AND password = '$hashedPassword'"
    );

    $query -> execute();
    $array = $query -> get_result();

    $response = [];
    $response[] = $array -> fetch_assoc();

    if($response[0] == null) {
        die(json_encode("Incorrect Password!"));
    }

    return true;
};

// Main

$data = retrieveDate($userName, $mysql);
$id = $data[0]["id"];
$dateJoined = $data[0]["dj"];

if(checkPassword($userName, $password, $dateJoined, $mysql)) {
    $tokenPayload = payloadCreate($userName, "client");
    $token = tokenEncode($tokenHeader, $tokenPayload, $SECRETKEY);

    $json = new stdClass();
    $json -> id = $id;
    $json -> userName = $userName;
    $json -> token = $token;

    die(json_encode($json));
};

?>
