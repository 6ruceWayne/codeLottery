<?php

include_once 'validator.php';
include_once 'mailer.php';
include_once 'Utils/codeController.php';
include_once 'Utils/prizeList.php';
include_once 'Utils/prizesController.php';
include_once 'Utils/db_connection.php';

// Catch all form data

$fname = $_POST["fname"];
$email = $_POST["email"];
$number = $_POST["number"];
$code = $_POST["code"];
$mailing = $_POST["mailing"];
$playerChoise = $_POST["playerChoise"];

$conn = OpenCon();

// Check if the code was already used and validate data

if (checkData($fname, $email, $number, $code, $conn)) {
    playLottery($fname, $email, $number, $code, $mailing, $playerChoise, $conn);
}

/* The main lottery process is here.
It checks if the amount of given today prizes hasn't been reached,
calculates chances to win depending on the last time of the last win and decides whether player wins or not
*/
function playLottery($fname, $email, $number, $code, $mailing, $playerChoise, $conn)
{
    $prizesAmount = amountOfAvailablePrizesToday($conn);
    $result = array();
    if ($prizesAmount > 0) {
        $lastWin = whenWasTheLastWin($conn);
        $chance = chanceToWin($lastWin);
        //decide if used wins
        if ($chance != 0 && rand(1, 100) < $chance) {
            // changing the code status and full fill the user data to it
            updateCode($fname, $email, $number, $code, $mailing, 'won', $conn);
            $result[9] = "true";
            // take all the available prizes (not given to other users)
            $list = getAvailablePrizes($conn);
            // take one prize
            $prizeOpt = $list[rand(0, count($list) - 1)];
            $prizeName = $prizeOpt[0];
            $prizeValue = $prizeOpt[1];
            $prizeVoucher = getRandomVoucher($prizeName, $prizeValue, $conn);
            $price = $prizeOpt[1];
            $result[$playerChoise - 1] = [$prizeName, $price];
            // change prize's status as given
            updatePrize($prizeName, $prizeValue, $prizeVoucher, $email, $conn);
            // HERE YOU CONNECT SEDNING EMAILS
            sendEmail($email, $code, $fname, $prizeName, $prizeValue);
        } else {
            // changing the code status and full fill the user data to it
            updateCode($fname, $email, $number, $code, $mailing, 'loose', $conn);
            $result[9] = "false";
            $result[$playerChoise - 1] = ['loose'];
        }
    } else {
        $result[9] = "false";
        $result[$playerChoise - 1] = ['loose'];
    }
    if ($result[9] == "false") {
    }

    $result = generateOthersPrizes($result, $playerChoise);
    for ($i = 0; $i < 9; $i++) {
        if (is_null($result[$i])) {
            $result[$i] = ['loose'];
        }
    }
    $conn->close();
    echo json_encode($result);
}
// Calculation of chance to win. It depends on the last time when was the previous win. Up to 3 hours is 100% on next win
function chanceToWin($lastWin)
{
    $rough = (int) ((time() - $lastWin) / 60);
    $chance = 0;
    if ($rough > 120) {
        if ($rough > 180) {
            $rough = rand(120, 180);
        }
        $rough = $rough - 120;
        $chance = (int) ($rough * 100) / 60;
    }
    return $chance;
}
// Gives the available amount of prizes for today. Also it considers last days of the lottery as a condition to amount of prizes
function amountOfAvailablePrizesToday($conn)
{
    $amount = 0;
    if (isSunday() && !isLastSunday()) {
        $amount = 7;
    } else {
        $amount = 8;
    }
    return ($amount - todaysSentPrizes($conn));
}

function isLastSunday()
{
    $current_date = date("d-m");
    $subscription = "07-11-2020";
    $subscription_date = date("d-m", strtotime($subscription));
    return $current_date == $subscription_date;
}

function isSunday()
{
    return date("D") == 'Sun';
}
// In order to show user non repeated prizes on its screen we need to make sure that we send unique 'possible' prizes and also if user wins its prize is not shown twice
function generateOthersPrizes($result, $playerChoise)
{
    $prizeList = getOthersPrizes($result[9], $result[$playerChoise - 1][0]);

    for ($i = 0; $i < count($prizeList); $i++) {
        $random = rand(0, 8);
        $prizeNotSet = true;
        while ($prizeNotSet) {
            if (is_null($result[$random])) {
                $result[$random] = $prizeList[$i];
                $prizeNotSet = false;
            } else {
                $random = rand(0, 8);
            }
        }
    }
    return $result;
}

function getOthersPrizes($win, $prize)
{
    $result = array();
    $prizeList = getPrizesList();
    for ($i = 0; $i < count($prizeList); $i++) {
        if ($win == "true") {
            if ($prize != $prizeList[$i][0]) {
                $prices = $prizeList[$i][1];
                array_push($result, [$prizeList[$i][0], $prices[rand(0, count($prices) - 1)]]);
            }
        } else {
            $prices = $prizeList[$i][1];
            array_push($result, [$prizeList[$i][0], $prices[rand(0, count($prices) - 1)]]);
        }
    }
    return $result;
}
