<?php
/**
 * Created by PhpStorm.
 * User: zunchenhuang
 * Date: 5/11/2017
 * Time: 8:55 AM
 */
session_start();
$db = pg_connect("host=localhost port=5432 dbname=funfund user=postgres password=chenny1993");

$tag = pg_escape_string($_GET['tag']);

$projSearchResult = pg_prepare($db, "projSearchQuery", "SELECT * FROM project WHERE tag = $1; ");
$projSearchResult = pg_execute($db, "projSearchQuery", array($tag));
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Come here and fun funding!</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
<h2><a href="http://localhost/front-end/homepage.php">Home</a></h2>
<h1>Search Tag Result</h1>
    <table style="margin-left: 5%">
        <tr><th>Related projects: </tr></th>
    <?php
    while($projSearchResultRow = pg_fetch_assoc($projSearchResult)){
        ?><tr><th><a href="projectpage.php?pid=<?php echo htmlspecialchars($projSearchResultRow['pid']); ?>"><?php echo htmlspecialchars($projSearchResultRow['pname']); ?></a></th></tr><?php
    }
    ?>
    </table>
</body>
</html>