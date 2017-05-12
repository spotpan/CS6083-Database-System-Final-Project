<?php
/**
 * Created by PhpStorm.
 * User: zunchenhuang
 * Date: 5/11/2017
 * Time: 11:23 PM
 */
session_start();
$db = pg_connect("host=localhost port=5432 dbname=funfund user=postgres password=chenny1993");

$uname = $_SESSION["uname"];
$userResult = pg_prepare($db, "userQuery", "SELECT * FROM usrinfo WHERE uname = $1");
$userResult = pg_execute($db, "userQuery", array($uname));
$userResultRow = pg_fetch_assoc($userResult);

$uemail = $_POST["uemail"];
if(strlen($uemail) == 0) {
    $uemail = $userResultRow['uemail'];
}

$uhometown = $_POST["uhometown"];
if(strlen($uemail) == 0) {
    $uemail = $userResultRow['uemail'];
}

$uinterests = $_POST["uinterests"];
if(strlen($uinterests) == 0) {
    $uinterests = $userResultRow['uinterests'];
}

$ucreditcard = pg_escape_string($_POST["ucreditcard"]);
if(strlen($ucreditcard) == 0) {
    $ucreditcard = $userResultRow['ucreditcard'];
}


$updateInfoResult = pg_prepare($db, "updateInfoQuery", "UPDATE usrinfo SET uemail = $1, 
                               uhometown = $2, uinterests = $3, ucreditcard = $4 WHERE uname = $5;");
$updateInfoResult = pg_execute($db, "updateInfoQuery", array($uemail, $uhometown, $uinterests, $ucreditcard, $uname));
if(!$updateInfoResult) {
    echo htmlspecialchars("Internal database error.");
    exit;
}
header("Location: homepage.php");

?>