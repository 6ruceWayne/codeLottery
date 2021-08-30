<?php

include_once 'db_connection.php';
include_once 'prizeList.php';

function whenWasTheLastWin($conn)
{
    $prizesList = getPrizesList();
    $dates = array();
    foreach ($prizesList as $prizeOpt) {
        $prizeName = strtolower($prizeOpt[0]);
        $prizePrices = $prizeOpt[1];
        foreach ($prizePrices as $price) {
            $columnName = 'sent_in_R' . $price;
            $query = "SELECT $columnName FROM $prizeName WHERE $columnName = (SELECT MAX($columnName) FROM $prizeName)";
            $stmt = $conn->prepare($query);
            $check = $stmt->execute();
            $result = $stmt->get_result();
            $number = $result->fetch_row()[0];
            $dates[] = strtotime($number);
            $result->free();
        }
    }
    $theLast = 0;
    foreach ($dates as $date) {
        if (!empty($date) && $date > $theLast) {
            $theLast = $date;
        }
    }
    return $theLast;
}

function getAvailablePrizes($conn)
{
    $prizesList = getPrizesList();
    $availablePrices = array();
    foreach ($prizesList as $prizeOpt) {
        $prizeName = strtolower($prizeOpt[0]);
        $prizePrices = $prizeOpt[1];
        foreach ($prizePrices as $price) {
            $columnName = 'sent_to_R' . $price;
            $columnVoucher = $prizeName . '_R' . $price;
            $query = "SELECT COUNT(*) FROM $prizeName WHERE $columnName IS NULL AND $columnVoucher IS NOT NULL";
            $stmt = $conn->prepare($query);
            $check = $stmt->execute();
            $result = $stmt->get_result();
            $number = $result->fetch_row()[0];
            $result->free();
            if ($number > 0) {
                $availablePrices[] = [$prizeName, $price, $number];
            }
        }
    }
    return $availablePrices;
}

function maxTimeStamp()
{
    $conn = OpenCon();
    echo 'test1';
    $query = "SELECT * FROM netflix WHERE sent_in_R250 = (SELECT MAX(sent_in_R250) FROM netflix)";
    echo 'test2';
    $stmt = $conn->prepare($query);
    echo 'test3' . '<br>' . $conn->error;
    $stmt->execute();
    echo 'test4' . '<br>';
    $result = $stmt->get_result();
    echo 'test5' . '<br>';
    $conn->close();
    return $result->fetch_row()[2];
}

function todaysSentPrizes($conn)
{
    $prizesList = getPrizesList();
    $amount = 0;
    foreach ($prizesList as $prizeOpt) {
        $prizeName = strtolower($prizeOpt[0]);
        $prizePrices = $prizeOpt[1];
        foreach ($prizePrices as $price) {
            $columnName = 'sent_in_R' . $price;
            $query = "SELECT COUNT(*) FROM $prizeName WHERE DATE(`$columnName`) = CURDATE()";
            $stmt = $conn->prepare($query);
            $check = $stmt->execute();
            $result = $stmt->get_result();
            $number = $result->fetch_row()[0];
            $amount = $amount + $number;
            $result->free();
        }
    }
    return $amount;
}

function todaySentThisType($prizeName, $prizeValue)
{
    $conn = OpenCon();
    $columnName = 'sent_in_R' . $prizeValue;
    $query = "SELECT COUNT(*) FROM $prizeName WHERE DATE(`$columnName`) = CURDATE()";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $result = $stmt->get_result();
    $conn->close();
    return '$result = ' . $result->fetch_row()[0];
}

function massSavePrizes($prizeName, $prizeValue, $prizeArray)
{
    $sql = array();
    foreach ($prizeArray as $prizeCode) {
        $sql[] = '(\'' . $prizeCode . '\')';
    }

    $conn = OpenCon();
    $columnName = $prizeName . '_R' . $prizeValue;
    $query = "INSERT INTO $prizeName ($columnName) VALUES " . implode(',', $sql);
    $stmt = $conn->prepare($query);
    $stmt->execute();
    echo $stmt->error;
    $conn->close();
    return $stmt->error;
}

function getRandomVoucher($prizeName, $prizeValue, $conn)
{
    $columnName = $prizeName . '_R' . $prizeValue;
    $columnCheck = 'sent_to_R' . $prizeValue;
    $query = "SELECT $columnName FROM $prizeName WHERE $columnCheck IS NULL";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $results = $stmt->get_result();
    $array = array();
    foreach ($results as $result) {
        foreach ($result as $code) {
            if (!empty($code)) {
                $array[] = $code;
            }
        }
    }
    return $array[rand(0, count($array))];
}

function updatePrize($prizeName, $prizeValue, $prizeCode, $email, $conn)
{
    $updatedColumnEmail = 'sent_to_R' . $prizeValue;
    $updatedColumnDate = 'sent_in_R' . $prizeValue;
    $comparedColumn = $prizeName . '_R' . $prizeValue;
    $query = "UPDATE $prizeName SET $updatedColumnEmail = ?, $updatedColumnDate = CURRENT_TIMESTAMP WHERE $comparedColumn LIKE ?";
    $stmt = $conn->prepare($query);
    $prizeCodeFiltered = "%$prizeCode%";
    $stmt->bind_param('ss', $email, $prizeCodeFiltered);
    $result = $stmt->execute();
    return $query;
}
