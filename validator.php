<?php
include_once 'Utils/codeController.php';
// This function validates data and returns error if something is wrong to front-end
function checkData($fname, $email, $number, $code, $conn)
{
    if (preg_match("/^[a-zA-Z-' ]*$/", $fname) && !empty($fname)) {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            if (validate_phone_number($number)) {
                if (isCodeActive($code, $conn)) {
                    return true;
                } else {
                    echo "code is invalid";
                }
            } else {
                echo "Invalid mobile number";
            }
        } else {
            echo "Invalid email address";
        }
    } else {
        echo "Please provide your full name";
    }
    return false;
}

function validate_phone_number($phone)
{
    // Allow +, - and . in phone number
    $filtered_phone_number = filter_var($phone, FILTER_SANITIZE_NUMBER_INT);
    // Remove "-" from number
    $phone_to_check = str_replace("-", "", $filtered_phone_number);
    // Check the lenght of number
    // This can be customized if you want phone number from a specific country
    if (strlen($phone_to_check) < 10 || strlen($phone_to_check) > 14) {
        return false;
    } else {
        return true;
    }
}
