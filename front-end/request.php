<html>
<head>
    <meta charset="UTF-8">
    <title>Welcome!</title>
    <link rel="stylesheet" href="http://localhost/front-end/css/style.css">
</head>
<body>
    <h2><a href="http://localhost/front-end/homepage.php">Home</a></h2>
</body>

<?php
/**
 * Created by PhpStorm.
 * User: zunchenhuang
 * Date: 5/12/2017
 * Time: 1:33 AM
 */

session_start();
$db = pg_connect("host=localhost port=5432 dbname=funfund user=postgres password=chenny1993");
$uname = $_SESSION['uname'];
$timestamp = pg_escape_string(date('Y-m-d G:i:s', strtotime("+30 days")));
$max = $_POST['max'];
$pid = $_GET['pid'];

$insertRequestResult = pg_prepare($db, "insertRequestQuery", "INSERT INTO request VALUES ($1, $2, $3, $4);");
$insertRequestResult = pg_execute($db, "insertRequestQuery", array($pid, $uname, $max, $timestamp));

$updateStatusResult = pg_prepare($db, "updateStatusQuery", "UPDATE project SET status = 'processing' WHERE pid = $1;");
$updateStatusResult = pg_execute($db, "updateStatusQuery", array($pid));

if(!$insertRequestResult Or !$updateStatusResult) {
    echo htmlspecialchars("Internal database error");
} else {
    echo htmlspecialchars("Request successful");
}
?>


