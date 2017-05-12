<?php
// connect to datebase
session_start();
$db = pg_connect("host=localhost port=5432 dbname=funfund user=postgres password=chenny1993");

$uname = $_SESSION["uname"];
$result = pg_prepare($db, "query", "SELECT pid, pname FROM project WHERE uname = $1;");
$result = pg_execute($db, "query", array($uname));
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Home Page</title>
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>
<h2><a href="../homepage.php">Home</a></h2>
<table style="margin-left: 5%">
    <tr><th>
        <a href="../createproj.html">Click here to <strong>Create a NEW Project</strong></a><br>
    </th></tr>
    <tr><th><br><br>Here is your list of projects:
        </th></tr>
    <?php
        while ($myrow = pg_fetch_assoc($result)) {
            ?>
    <tr><th>
            <a href="../projectpage.php?pid=<?php echo htmlspecialchars($myrow['pid']); ?>"><?php echo htmlspecialchars($myrow['pname']), "<br>"; ?></a>
    </th></tr>
            <?php
        }
    $previous = "javascript:history.go(-1)";
    if(isset($_SERVER['HTTP_REFERER'])) {
        $previous = $_SERVER['HTTP_REFERER'];
    }
    ?>
</table>
<a href="<?= $previous ?>" style="margin-left:5.5%;font-size:10pt;color:white;background-color:green;border:2px solid #336600;padding:3px">Back</a>
</body>
</html>
