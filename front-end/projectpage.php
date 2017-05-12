<?php
/**
 * Created by PhpStorm.
 * User: zunchenhuang
 * Date: 5/12/2017
 * Time: 12:11 AM
 */
//connect to database
session_start();
$db = pg_connect("host=localhost port=5432 dbname=funfund user=postgres password=chenny1993");

$user = $_SESSION["uname"];
$pid =  pg_escape_string($_GET['pid']);

$imgData = pg_prepare($db, "imgQuery", "SELECT file_data FROM projectattachment WHERE pid = $1");
$imgData = pg_execute($db, "imgQuery", array($pid));
//header('Content-type: image/jpeg');
//echo pg_unescape_bytea($imgData['file_data']);
// Convert to binary and send to the browser

if (isset($_GET["like"])) {
    $isLike = $_GET["like"];
    if ($isLike == 0) {
        $timestamp = pg_escape_string(date('Y-m-d G:i:s'));
        $insertLikeResult = pg_prepare($db, "insertLikeQuery", "INSERT INTO usrlikeproject VALUES($1, $2, $3)");
        $insertLikeResult = pg_execute($db, "insertLikeQuery", array($pid, $user, $timestamp));
        if (!$insertLikeResult) {
            echo htmlspecialchars("Internal database error");
            exit;
        }
    } else {

        $deleteLikeResult = pg_prepare($db, "deleteLikeQuery", "DELETE FROM usrlikeproject WHERE uname = $1 AND pid = $2;");
        $deleteLikeResult = pg_execute($db, "deleteLikeQuery", array($user, $pid));
        if (!$deleteLikeResult) {
            echo htmlspecialchars("Internal database error");
            exit;
        }
    }
}
if (isset($_POST["comment"])) {
    $content =pg_escape_string($_POST["comment"]);
    $timestamp = pg_escape_string(date('Y-m-d G:i:s'));

    $insertCommentResult = pg_prepare($db, "insertCommentQuery","INSERT INTO comment VALUES($1, $2, $3, $4);");
    $insertCommentResult = pg_execute($db,"insertCommentQuery", array($pid, $user, $timestamp, $content));
}

$likeResult = pg_prepare($db, "likeQuery", "SELECT * FROM usrlikeproject WHERE uname = $1 AND pid = $2;");
$likeResult = pg_execute($db, "likeQuery", array($user, $pid));

$projResult = pg_prepare($db, "projQuery", "SELECT * FROM project WHERE pid = $1;");
$projResult = pg_execute($db, "projQuery", array($pid));
$projResultRow = pg_fetch_assoc($projResult);

$commResult = pg_prepare($db, "commQuery", "SELECT * FROM comment WHERE pid = $1;");
$commResult = pg_execute($db, "commQuery", array($pid));
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($projResultRow['pname']);?></title>
    <link rel="stylesheet" href="http://localhost/front-end/css/style.css">
</head>
<body style="margin-left: 5%">
<!--<img src="data:image/jpeg;base64,--><?php //echo base64_encode($imgData["file_data"]); ?><!--" >-->
<!--<img src="--><?php //header('Content-type: image/jpeg'); echo pg_unescape_bytea($imgData['file_data']); ?><!--">-->
<!--<img src = \"image.php\">-->

<h2><a href="http://localhost/front-end/homepage.php">Home</a></h2> <!--返回主页-->
<h2><?php echo $projResultRow['pname']; ?></h2>
<?php
if (pg_num_rows($likeResult) == 0) {
    ?>
    <a href="http://localhost/front-end/projectpage.php?pid=<?php echo htmlspecialchars($pid); ?>&like=0">Like<br></a> <!-- jump to project list of the user -->
    <?php
}
else {
    ?>
    <a href="http://localhost/front-end/projectpage.php?pid=<?php echo htmlspecialchars($pid); ?>&like=1">Unlike<br></a> <!-- jump to project list of the user -->
    <?php
}
?>
<h3>Created by:</h3>
<p><a href="userpage.php?uname=<?php echo htmlspecialchars($projResultRow['uname']); ?>"><?php echo htmlspecialchars($projResultRow['uname']); ?></a></p>
<h3>Description:</h3>
<p><?php echo htmlspecialchars($projResultRow['pdescrption']); ?></p>
<p>Tag:</p>
<a href="searchtagresult.php?tag=<?php echo htmlspecialchars($projResultRow['tag']); ?>"><?php echo htmlspecialchars($projResultRow['tag']); ?></a>
<p>Status:</p>
<p><?php echo htmlspecialchars($projResultRow['status']); ?></p>
<p><br>Comments:</p>
<p>
    <?php
    while ($commResultRow = pg_fetch_assoc($commResult)) {
        if ($commResultRow) {
    ?>
    <a href="userpage.php?uname=<?php echo htmlspecialchars($commResultRow['uname']);?>"><?php echo htmlspecialchars($commResultRow['uname']);?>:</a>
    <?php
            echo htmlspecialchars($commResultRow['content']), "<br>";
            echo "@", htmlspecialchars($commResultRow['date']), "<br>";
        }
    }
    ?>
</p>

<?php
    if ($projResultRow['status'] =='processing') {
        $endTimeQuery = "SELECT * FROM request WHERE pid = $pid;";
        $endTimeResult = pg_query($endTimeQuery);
        $endTimeResultRow = pg_fetch_assoc($endTimeResult);
        $amountSumQuery = "SELECT SUM(amount) FROM pledge where pid = $pid group by pid";
        $amountSumResult = pg_query($amountSumQuery);
        $amountSumResultRow = pg_fetch_assoc($amountSumResult);
        $avgRateQuery = "SELECT AVG(ratenumber) FROM rate where pid = $pid group by pid";
        $avgRateResult = pg_query($avgRateQuery);
        $avgRateResultRow = pg_fetch_assoc($avgRateResult);
        ?>
        <p>End Time:</p>
        <p><?php echo htmlspecialchars($endTimeResultRow['endtime']); ?></p>
        <p>Progress:</p>
        <p><?php echo $amountSumResultRow['sum']; ?> / <?php echo htmlspecialchars($endTimeResultRow['max']); ?></p>
        <p>Rate:</p>
        <p><?php echo $avgRateResultRow['avg']; ?></p>
        <form action="pledge.php?pid=<?php echo htmlspecialchars($pid);?>" method = "post" >
        <td> Pledge Amount: </td>
        <td ><input type = "text" size="40" name="amount"></td>
        <p><input type = "submit" value = "Submit" ></p>
        </form>
<?php
    } else {
        $avgRateQuery = "SELECT AVG(ratenumber) FROM rate where pid = $pid group by pid";
        $avgRateResult = pg_query($avgRateQuery);
        $avgRateResultRow = pg_fetch_assoc($avgRateResult);
        ?>
        <p>Rate:</p>
        <p><?php echo number_format(htmlspecialchars($avgRateResultRow['avg']), 2); ?></p>
<?php    }
?>
<form action="projectpage.php?pid=<?php echo htmlspecialchars($pid); ?>" method="post">
    <td>Comment:</td>
    <td><input type="text" size="40" name="comment"></td>
    <p><input type="submit" value="Submit"></p>
</form>
</body>

</html>