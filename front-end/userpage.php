<?php
/**
 * Created by PhpStorm.
 * User: zunchenhuang
 * Date: 5/11/2017
 * Time: 11:50 PM
 */
//connect to database
session_start();
$db = pg_connect("host=localhost port=5432 dbname=funfund user=postgres password=chenny1993");

$user = $_SESSION["uname"];
$uname = $_GET["uname"];

if($user != $uname) {
    if (isset($_GET["follow"])) {
        $isFollow = $_GET["follow"];
        if($isFollow == 0) {
            $insertFollowResult = pg_prepare($db, "insertFollowQuery", "INSERT INTO follow VALUES($1, $2);");
            $insertFollowResult = pg_execute($db, "insertFollowQuery", array($user, $uname));
            if(!$insertFollowResult) {
                echo htmlspecialchars("Internal database error");
                exit;
            }
        } else {
            $deleteFollowResult = pg_prepare($db, "deleteFollowQuery", "DELETE FROM follow WHERE uname = $1 AND following = $2;");
            $deleteFollowResult = pg_execute($db, "deleteFollowQuery", array($user, $uname));
            if(!$deleteFollowResult) {
                echo htmlspecialchars("Internal database error");
                exit;
            }
        }
    }
}
$followResult = pg_prepare($db, "followQuery", "SELECT * FROM follow WHERE uname = $1 AND following = $2;");
$followResult = pg_execute($db, "followQuery", array($user, $uname));

$usrInfoResult = pg_prepare($db, "usrInfoQuery", "SELECT * FROM Usrinfo WHERE uname = $1;");
$usrInfoResult = pg_execute($db, "usrInfoQuery", array($uname));
$usrInfoResultRow = pg_fetch_assoc($usrInfoResult);

$usrLikeResult = pg_prepare($db, "usrLikeQuery","SELECT p.pname, p.pid FROM Project as p, usrlikeproject as ul WHERE ul.uname = $1
AND ul.pid = p.pid ORDER BY ul.commenttime LIMIT 3;");
$usrLikeResult = pg_execute($db, "usrLikeQuery", array($uname));

$pledgeResult = pg_prepare($db, "pledgeQuery", "SELECT p.pname, pl.amount, pl.date, p.pid FROM Project as p, Pledge as pl, Usr as u WHERE p.pid = pl.pid AND pl.uname = u.uname 
AND u.uname=$1 ORDER BY pl.date LIMIT 3;");
$pledgeResult = pg_execute($db, "pledgeQuery", array($uname));
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
<div id="main">
    <table style="margin-left: 5%">
        <tr>
            <th><?php echo htmlspecialchars($uname), "'s ";?> recent liked projects:</th>
        </tr>

        <!--    --><?php
        // print project he/she like or he/she funded
        while($usrLikeResultRow = pg_fetch_assoc($usrLikeResult)){
            ?><tr><th><a href="projectpage.php?pid=<?php echo htmlspecialchars($usrLikeResultRow['pid']); ?>"><?php echo htmlspecialchars($usrLikeResultRow['pname']); ?></a></th></tr><?php
        }
        ?>
        <tr>
            <th><?php echo htmlspecialchars($uname), "'s ";?> recent pledges:</th>
        </tr>
        <tr>
            <th>Project name</th>
            <th>Amount</th>
            <th>Date</th>
        </tr>
        <?php
        while($pledgeResultRow = pg_fetch_assoc($pledgeResult)) {
            ?> <tr><td><a href="projectpage.php?pid=<?php echo htmlspecialchars($pledgeResultRow['pid']); ?>"><?php echo htmlspecialchars($pledgeResultRow['pname']); ?></a></td>
            <td><?php echo htmlspecialchars($pledgeResultRow['amount']); ?></td>
            <td><?php echo htmlspecialchars($pledgeResultRow['date']); ?></td>
            </tr><?php
        }?>

    </table>
</div>


<div id="userinfo">
    <!-- <a href="userpage.php/?uname=$uname&follow=$user">Follow</a> -->
    <?php
    if($user != $uname) {
        // print user info from query result
        if (pg_num_rows($followResult) == 0) {
            ?>
            <a href="http://localhost/front-end/userpage.php?uname=<?php echo "$uname"; ?>&follow=0">Follow<br></a> <!-- jump to project list of the user -->
            <?php
        }
        else {
            ?>
            <a href="http://localhost/front-end/userpage.php?uname=<?php echo "$uname"; ?>&follow=1">Following<br></a> <!-- jump to project list of the user -->
            <?php
        }
    }
    echo "Username: ", $usrInfoResultRow['uname'], "<br>";
    echo "Email: ", $usrInfoResultRow['uemail'], "<br>";
    echo "Hometown: ", $usrInfoResultRow['uhometown'], "<br>";
    echo "Interest: ", $usrInfoResultRow['uinterests'], "<br>";
    ?>
    <a href="http://localhost/front-end/userproject.php?uname=<?php echo"$uname"; ?>">Click here to see user projects.</a> <!-- jump to project list of the user -->

</div>
</body>
</html>