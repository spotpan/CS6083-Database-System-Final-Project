<html>
<head>
    <meta charset="UTF-8">
    <title>Welcome!</title>
    <link rel="stylesheet" href="css/style.css">

</head>
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

	//$_SESSION ['cname'] = $cname;
    $query_usr = "SELECT uname FROM usr WHERE uloginname = '$loginname' AND upassword = '$password'";
    $result = pg_query($query_usr);

    if(pg_num_rows($result) != 1){
        echo "<p style='color: azure '>", "Wrong login name or password: ", $loginname, "!", '</p><br>';
        $previous = "javascript:history.go(-1)";
        if(isset($_SERVER['HTTP_REFERER'])) {
            $previous = $_SERVER['HTTP_REFERER'];
        }
?>
<a href="<?= $previous ?>">Back</a>
<?php
    } else {
        echo "<p style='color: azure '>Welcome, ", $loginname, "!", '</p><br>';
    }

?>
</html>
