<?php
/**
 * Created by PhpStorm.
 * User: zunchenhuang
 * Date: 5/11/2017
 * Time: 11:45 PM
 */
// connect to datebase
session_start();
$db = pg_connect("host=localhost port=5432 dbname=funfund user=postgres password=chenny1993");

$uname = $_GET["uname"];
$result = pg_prepare($db, "query","SELECT pid, pname FROM Project WHERE uname = $1;");
$result = pg_execute($db, "query", array("$uname"));
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Home Page</title>
    <style>
        #main{width: 65%; height:100%; float:left;}
        #userinfo{width:30%; height:100%; float:right;}

    </style>
    <link rel="stylesheet" href="http://localhost/front-end/css/style.css">

</head>
<body>
<h2><a href="http://localhost/front-end/homepage.php">Home</a></h2>
<?php
while ($myrow = pg_fetch_assoc($result)) {?>
<a href="http://localhost/front-end/projectpage.php?pid=<?php echo htmlspecialchars($myrow['pid']); ?>"><?php echo htmlspecialchars($myrow['pname']), "<br>"; ?>
    <?php
    }
?>
</body>
</html>

