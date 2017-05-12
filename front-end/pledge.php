<?php
/**
 * Created by PhpStorm.
 * User: zunchenhuang
 * Date: 5/11/2017
 * Time: 4:25 PM
 */
session_start();
$db = pg_connect("host=localhost port=5432 dbname=funfund user=postgres password=chenny1993");

$uname = $_SESSION["uname"];
$amount = $_POST['amount'];
$pid = $_GET['pid'];

$pledgetime =  pg_escape_string(date('Y-m-d G:i:s'));

$projStatusResult = pg_prepare($db, "projStatusQuery", "SELECT p.uname, p.status FROM project AS p WHERE pid = $1;");
$projStatusResult = pg_execute($db, "projStatusQuery", array($pid));
$projStatusResultRow = pg_fetch_assoc($projStatusResult);

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($projResultRow['pname']);?></title>
    <link rel="stylesheet" href="http://localhost/front-end/css/style.css">
</head>
<h2><a href="http://localhost/front-end/homepage.php">Home</a></h2>
<form action="rateresult.php?pid=<?php echo htmlspecialchars($pid); ?>&uname=<?php echo htmlspecialchars($projStatusResultRow['uname']); ?>" method="post">
    <select name="rate" id="rate">
        <option value="5">5</option>
        <option value="4">4</option>
        <option value="5">3</option>
        <option value="5">2</option>
        <option value="5">1</option>
    </select>
    <input type="submit" value="Rate">
</form>
</html>
<?php
    if($projStatusResultRow['status'] != "processing") {
        echo htmlspecialchars("This project is not processing now.");
        } else {
        $insertPledgeQuery = "INSERT INTO pledge VALUES ($pid, '$uname', '$amount', '$pledgetime')";
        $insertPledgeResult = pg_query($insertPledgeQuery);
        if(!$insertPledgeResult) {
        echo htmlspecialchars("Internal database error");
        exit;
        } else {
        echo htmlspecialchars("Pledge successful.");
        }
    }
?>