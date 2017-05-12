<?php
/**
 * Created by PhpStorm.
 * User: zunchenhuang
 * Date: 5/11/2017
 * Time: 12:00 AM
 */
//connect to database
session_start();
$db = pg_connect("host=localhost port=5432 dbname=funfund user=postgres password=chenny1993");
$uname = $_SESSION['uname'];

$keyword = $_POST['keyword'];
$logging = "Search: " . $keyword;

$timestamp = pg_escape_string(date('Y-m-d G:i:s'));
$loginfoResult = pg_prepare($db, "loginfoQuery", "INSERT INTO logactivity VALUES ($1, $2, $3);");
$loginfoResult = pg_execute($db, "loginfoQuery", array($uname, $timestamp, $logging));

$projSearchQuery = "SELECT * FROM project WHERE LOWER(pname) LIKE LOWER('%$keyword%') OR LOWER(tag) LIKE LOWER('%$keyword%')
                    OR LOWER('%$keyword%') LIKE LOWER(pdescrption);";
$projSearchResult = pg_query($projSearchQuery);


$userSearchQuery = "SELECT * FROM usrinfo WHERE LOWER(uname) LIKE LOWER('%$keyword%');";
$userSearchResult = pg_query($userSearchQuery);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Come here and fun funding!</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
<h2><a href="http://localhost/front-end/homepage.php">Home</a></h2> <!--返回主页-->
<h1>Search Result</h1>
<div id="wrap">
    <div id="regbar">
        <h2><a href="#" id="projform">Project</a> | <a href="#" id="userform">User</a></h2>
        <div id="project">
            <!-- 此处打印project查询结果 !记得都做成超链接 -->
            <table style="margin-left: 5%">
                <?php
                // print project he/she like or he/she funded
                // 可以pledge like各打印三个 打印project name， 做成超链接
                while($projSearchResultRow = pg_fetch_assoc($projSearchResult)){
                    ?><tr><th><a href="projectpage.php?pid=<?php echo htmlspecialchars($projSearchResultRow['pid']); ?>"><?php echo htmlspecialchars($projSearchResultRow['pname']); ?></a></th></tr><?php
                }
                ?>

            </table>
        </div>
        <div id="user">
            <table>
            <!-- 此处打印user查询结果 -->
            <?php
            while($userSearchResultRow = pg_fetch_assoc($userSearchResult)) {
            ?>
                <tr><th><a href="userpage.php/?uname=<?php echo htmlspecialchars($userSearchResultRow['uname']); ?>"><?php echo htmlspecialchars($userSearchResultRow['uname']); ?></a></th></tr><?php
            }
            ?>
            </table>
        </div>
    </div>
</div>
<script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>

<script src="js/searchresult.js"></script>
</body>
</html>