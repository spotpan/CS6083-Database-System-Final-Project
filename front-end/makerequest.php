<?php
/**
 * Created by PhpStorm.
 * User: zunchenhuang
 * Date: 5/12/2017
 * Time: 12:01 AM
 */
session_start();
$db = pg_connect("host=localhost port=5432 dbname=funfund user=postgres password=chenny1993");
$uname = $_GET['uname'];

$notStartedProjectResult = pg_prepare($db, "notStartedProjectQuery", "SELECT * FROM project WHERE uname = $1 AND status = 'not-started';");
$notStartedProjectResult = pg_execute($db, "notStartedProjectQuery", array($uname));
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Make a request!</title>
    <link rel="stylesheet" href="http://localhost/front-end/css/style.css">
</head>
<!-- Project(pid, uname, pname, pdescription, status, tag, createdtime, plannedtime) -->
<body>
<h2><a href="http://localhost/front-end/homepage.php">Home</a></h2>
<h2>Select from the following projects:</h2>
<p style="margin-left: 5%"><?php
    if(pg_num_rows($notStartedProjectResult) == 0) {
        echo htmlspecialchars("You don't have a not-started project now. You can go back and create a new project on your homepage");
    } else {
        while($notStartedProjectResultRow = pg_fetch_assoc($notStartedProjectResult)) {
        ?>
            <a href="http://localhost/front-end/requestform.php?pid=<?php echo htmlspecialchars($notStartedProjectResultRow['pid']);?>"><?php echo htmlspecialchars($notStartedProjectResultRow['pname']); ?></a>
    <?php
        }
    }
    ?></p>
</body>
</html>
