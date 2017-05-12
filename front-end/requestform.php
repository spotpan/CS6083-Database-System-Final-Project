<?php
/**
 * Created by PhpStorm.
 * User: zunchenhuang
 * Date: 5/12/2017
 * Time: 1:24 AM
 */

session_start();
$db = pg_connect("host=localhost port=5432 dbname=funfund user=postgres password=chenny1993");
$uname = $_SESSION['uname'];
$pid = $_GET['pid'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Request</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<h2><a href="homepage.php">Home</a></h2>
<form action="request.php/?pid=<?php echo htmlspecialchars($pid);?>" method="post" style="margin-left: 5%">
    Your target amount: <br>
    <input type="number" name="max"><br>
    <input type="submit" value="Request">
</form>
</body>
</html>
