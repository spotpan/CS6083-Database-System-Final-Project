<?php
/**
 * Created by PhpStorm.
 * User: zunchenhuang
 * Date: 5/11/2017
 * Time: 12:56 PM
 */
session_start();
$db = pg_connect("host=localhost port=5432 dbname=funfund user=postgres password=chenny1993");
$uname = $_SESSION["uname"];
$uemail = $_POST["uemail"];
$uhometown = $_POST["uhometown"];
$uinterests = $_POST["uinterests"];
$ucreditcard = pg_escape_string($_POST["ucreditcard"]);


$insertInfoResult = pg_prepare($db, "insertInfoQuery", "INSERT INTO usrinfo VALUES ($1, $2, $3, $4, $5);");
$insertInfoResult = pg_execute($db, "insertInfoQuery", array($uname, $uemail, $uhometown, $uinterests, $ucreditcard));
if(!$insertInfoResult) {
    echo htmlspecialchars("Internal database error.");
    exit;
}
header("Location: homepage.php");
?>