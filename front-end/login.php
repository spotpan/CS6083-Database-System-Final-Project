<html>
<head>
    <meta charset="UTF-8">
    <title>Welcome!</title>
    <link rel="stylesheet" href="css/style.css">

</head>
<body>
<?php
/**
 * Created by PhpStorm.
 * User: zunchenhuang
 * Date: 5/7/2017
 * Time: 12:38 AM
 */
	session_start();
	$db = pg_connect("host=localhost port=5432 dbname=funfund user=postgres password=chenny1993");

	$loginname = pg_escape_string($_POST['loginname']);
	$password = pg_escape_string($_POST['password']);


//    $queryUsr = "SELECT uname FROM usr WHERE uloginname = '$loginname' AND upassword = '$password'";
    $result = pg_prepare($db, "queryUsr", "SELECT uname FROM usr WHERE uloginname = $1 AND upassword = $2");
    $result = pg_execute($db, "queryUsr", array($loginname, $password));

    if(pg_num_rows($result) != 1){
        echo "<p style='color: azure'>", htmlspecialchars("Wrong login name or password: ", $loginname, "!", '</p><br>');
        $previous = "javascript:history.go(-1)";
        if(isset($_SERVER['HTTP_REFERER'])) {
            $previous = $_SERVER['HTTP_REFERER'];
        }
?>

<a href="<?= $previous ?>" style="font-size:10pt;color:white;background-color:green;border:2px solid #336600;padding:3px">Back</a>

<?php
    } else {
        $myrow = pg_fetch_assoc($result);
        $_SESSION ['uname'] = $myrow['uname'];
        echo htmlspecialchars("<p style='color: azure'>Welcome, ", $loginname, "!", '</p><br>');
        sleep(3);
        header("Location: homepage.php");
    }

?>
</html>
</body>