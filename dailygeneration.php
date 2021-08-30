<?php
//This file is for CRON job. It will full fill database with free codes
include_once 'Utils/codeController.php';

echo 'try to generate codes:' . '<br>';
$amount = 10000;
generateCodes($amount);

echo 'done';
