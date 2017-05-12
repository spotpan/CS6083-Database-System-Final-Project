<?php
/**
 * Created by PhpStorm.
 * User: zunchenhuang
 * Date: 5/12/2017
 * Time: 12:43 AM
 */
session_start();
//connect to dabatabse
$db = pg_connect("host=localhost port=5432 dbname=funfund user=postgres password=chenny1993");

$user = $_SESSION["uname"];
$pid = $_GET["pid"];
$uname = $_GET['uname'];
$ratenumber = $_POST['rate'];

//check两件事，一个是不能给自己评分，既user和uname不一样， 另一个是不能重复评分
?>


<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars("$uname's Project'");?></title>
    <link rel="stylesheet" href="css/style.css">

</head>
<body>
<h2><a href="homepage.php">Home</a></h2> <!--返回主页-->
</body>
<?php
if ($user == $uname) {
    echo htmlspecialchars('You can not rate your project!<br>');
} else {
    $rateResult = pg_prepare($db, "rateQuery", "SELECT * FROM rate WHERE pid = $1 AND uname = $2;");
    $rateResult = pg_execute($db, "rateQuery", array($pid, $user));
    if (pg_num_rows($rateResult) == 1) {
        //如果已经评分过了
        echo htmlspecialchars('You have rated this project!');
    } else {
        // insert
        $insertRateResult = pg_prepare($db, "insertRateQuery", "INSERT INTO rate VALUES ($1, $2, $3); ");
        $insertRateResult = pg_execute($db, "insertRateQuery",array($pid, $user, $ratenumber));
        if($insertRateResult){
            echo htmlspecialchars("Rate success!");
        }
    }
}
?>