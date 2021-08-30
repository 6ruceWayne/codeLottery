<?php

include_once 'db_connection.php';

function generateCode()
{
    $code = createCode();
    while (checkExistance($code)) {
        $code = createCode();
    }
    saveCode($code);
    return $code;
}

function createCode()
{
    $code = generate_string();
    return $code;
}

function generate_string($strength = 10)
{
    $input = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $input_length = strlen($input);
    $random_string = '';
    for ($i = 0; $i < $strength; $i++) {
        $random_character = $input[mt_rand(0, $input_length - 1)];
        $random_string .= $random_character;
    }
    return $random_string;
}

function checkExistance($code)
{
    $conn = OpenCon();
    $query = "SELECT * FROM generated_codes WHERE code_name = ? ";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $code);
    $stmt->execute();
    $result = $stmt->get_result();

    $rows = mysqli_num_rows($result);
    $conn->close();
    return $rows === 1;
}

function saveCode($code)
{
    $conn = OpenCon();
    $query = "INSERT INTO generated_codes (code_name) VALUES (?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $code);
    $stmt->execute();
    if ($stmt->error) {
        echo $stmt->error;
    }
    $conn->close();
}

function saveMultCode($array)
{
    $sql = array();
    foreach ($array as $code) {
        $sql[] = '(\'' . $code . '\')';
    }
    $conn = OpenCon();
    $query = "INSERT INTO generated_codes (code_name) VALUES " . implode(',', $sql);
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $conn->close();
    return $stmt->error;
}

function clearTable()
{
    $conn = OpenCon();
    $query = "TRUNCATE TABLE generated_codes";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $conn->close();
}

function getAmountOfAvailableCodes()
{
    $conn = OpenCon();
    $query = "SELECT COUNT(*) FROM generated_codes WHERE used = 0 ";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $result = $stmt->get_result();
    $conn->close();
    return $result->fetch_row()[0];
}

function isCodeActive($code, $conn)
{
    $query = "SELECT * FROM generated_codes WHERE code_name = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $code);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($stmt->error) {
        return $stmt->error;
    }
    $exist = false;
    while ($row = $result->fetch_row()) {
        if ($row[0] == $code) {
            if ($row[1] == false) {
                return true;
            } else if ($row[1] == true) {
                return false;
            }
        } else {
            $exist = false;
        }
    }
    return $exist;
}

function updateCode($fname, $email, $number, $code, $mailing, $result, $conn)
{
    $mysql_date_now = date("Y-m-d H:i:s");
    if ($mailing == "true") {
        $mailing = 1;
    } else {
        $mailing = 0;
    }
    $query = "UPDATE `generated_codes` SET used=true, full_name=?, email=?, mobile_number=?, mailing=?, result=? WHERE code_name = ?";
    $stmt = $conn->prepare($query);
    $number = str_replace("+", "", $number);
    $stmt->bind_param('sssiss', $fname, $email, $number, $mailing, $result, $code);
    $stmt->execute();
    return $stmt->error;
}

function getRandomCode($conn)
{
    $query = "SELECT * FROM generated_codes WHERE used = 0 ORDER BY RAND() LIMIT 1";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_row();
    return $row[0];
}

function generateCodes($amount)
{
    $count = getAmountOfAvailableCodes();
    if ($count < $amount) {
        $necesattyAmount = $amount - $count;
        $errors = true;
        while ($errors) {
            $array = array();
            for ($i = 0; $i < $necesattyAmount; $i++) {
                array_push($array, createCode());
            }
            $errors = saveMultCode($array);
        }
    }
}
