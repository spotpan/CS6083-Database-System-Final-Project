<?php
/**
 * Created by PhpStorm.
 * User: zunchenhuang
 * Date: 5/12/2017
 * Time: 12:01 AM
 */
//connect to database
session_start();
$db = pg_connect("host=localhost port=5432 dbname=funfund user=postgres password=chenny1993");

$uname = pg_escape_string($_SESSION["uname"]);

$usrInfoResult = pg_prepare($db, "usrInfoQuery", "SELECT * FROM Usrinfo WHERE uname = $1");
$usrInfoResult = pg_execute($db, "usrInfoQuery", array($uname));
$usrInfoResultRow = pg_fetch_assoc($usrInfoResult);

$usrLikeResult = pg_prepare($db, "usrLikeQuery", "SELECT p.pid, p.pname FROM Project as p, usrlikeproject as ul WHERE ul.uname = $1
AND ul.pid = p.pid ORDER BY ul.commenttime LIMIT 3;");
$usrLikeResult = pg_execute($db, "usrLikeQuery", array($uname));

$pledgeResult = pg_prepare($db,"pledgeQuery","SELECT p.pid, p.pname, pl.amount, pl.date FROM Project as p, Pledge as pl, Usr as u WHERE p.pid = pl.pid AND pl.uname = u.uname AND u.uname =$1
                ORDER BY pl.date LIMIT 3;");
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
    <link rel="stylesheet" href="css/style.css">

</head>

<body>
<h2><a href="homepage.php">Home</a></h2>
<form method="post" action="searchresult.php">
    <table style="margin-left: 5%">
        <td><input type="text" size="10" name="keyword"></td>
        <td><input type="submit" value="Search"></td>
    </table>
</form>
<div id="main">
    <table style="margin-left: 5%">
        <tr>
            <th>Your recent liked project:</th>
        </tr>

<!--    --><?php
    while($usrLikeResultRow = pg_fetch_assoc($usrLikeResult)){
        ?><tr><th><a href="projectpage.php?pid=<?php echo htmlspecialchars($usrLikeResultRow['pid']); ?>"><?php echo $usrLikeResultRow['pname']; ?></a></th></tr><?php
    }
    ?>
        <tr>
            <th><br><br>Your recent pledge:</th>
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
    <?php
    // print user info from query result
    echo "Username: ", htmlspecialchars($usrInfoResultRow['uname']), "<br>";
    echo "Email: ", htmlspecialchars($usrInfoResultRow['uemail']), "<br>";
    echo "Hometown: ", htmlspecialchars($usrInfoResultRow['uhometown']), "<br>";
    echo "Interest: ", htmlspecialchars($usrInfoResultRow['uinterests']), "<br>";
    ?>
    <a style="font-size: large " href="myproject.php/?uname=<?php echo htmlspecialchars($uname); ?>"><strong>See your projects</strong></a><br> <!-- jump to project list of the user -->
    <a style="font-size: large " href="updateinfo.html"><strong>Update your information</strong></a><br>
    <a style="font-size: large " href="makerequest.php/?uname=<?php echo htmlspecialchars($uname); ?>"><strong>Make a request of your project</strong></a>
</div>
</body>
</html>