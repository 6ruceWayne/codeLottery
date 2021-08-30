<?php

include_once 'validator.php';

$fname = $_POST["fname"];
$email = $_POST["email"];
$number = $_POST["number"];
$code = $_POST["code"];
$tos = $_POST["tos"];
$mailing = $_POST["mailing"];

if ($tos == "true") {
    $reply = "";
    $conn = OpenCon();
    if (checkData($fname, $email, $number, $code, $conn)) {
        $reply = "let the game begin " . $code;
    }
    $conn->close();
    echo $reply;
} else {
    echo "Please confirm your agreement";
}
