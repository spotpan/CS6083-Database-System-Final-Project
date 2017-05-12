<?php
/**
 * Created by PhpStorm.
 * User: zunchenhuang
 * Date: 5/11/2017
 * Time: 1:13 PM
 */

session_start();
$db = pg_connect("host=localhost port=5432 dbname=funfund user=postgres password=chenny1993");
$uname = $_SESSION['uname'];
$pname = $_POST['pname'];
$tag = $_POST['tag'];
$pdescription = $_POST['pdescription'];
$plannedtime = $_POST['plannedtime'];
$createdtime =  pg_escape_string(date('Y-m-d G:i:s'));
$status = "not-started";
$gotIDQuery = "SELECT pid as pid FROM project ORDER BY pid DESC LIMIT 1;";
$gotID = pg_query($gotIDQuery);
$ID = pg_fetch_assoc($gotID);

$IID = $ID['pid'] + 1;

$insertProjectResult = pg_prepare($db, "insertProjectQuery", "INSERT INTO project VALUES ($1, $2, $3, $4, $5, $6, $7, $8)");
$insertProjectResult = pg_execute($db, "insertProjectQuery", array($IID, $uname, $pname, $pdescription, $status, $tag, $createdtime, $plannedtime));
if(!$insertProjectResult) {
    echo htmlspecialchars ("Internal database error");
    exit;
}
header("Location: homepage.php");
?>