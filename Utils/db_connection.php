<?php
function OpenCon()
{
    $dbhost = "credentials";
    $dbuser = "credentials";
    $dbpass = "credentials";
    $db = "credentials";
    $conn = new mysqli($dbhost, $dbuser, $dbpass, $db) or die("Connect failed: %s\n" . $conn->error);

    return $conn;
}
