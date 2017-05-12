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
 * Date: 5/8/2017
 * Time: 2:26 PM
 */

    session_start();
    $db = pg_connect("host=localhost port=5432 dbname=funfund user=postgres password=chenny1993");

    $r_uname = pg_escape_string($_POST['r_uname']);
    $r_loginname = pg_escape_string($_POST['r_loginname']);
    $r_password = pg_escape_string($_POST['r_password']);

    $result_uname = pg_prepare($db, "query_usr_loginname", "SELECT uname FROM usr WHERE uloginname = $1");
    $result_uname = pg_execute($db, "query_usr_loginname", array($r_loginname));

    $result_loginname = pg_prepare($db,"query_usr_uname", "SELECT uname FROM usr WHERE uloginname = $1");
    $result_loginname = pg_execute($db,"query_usr_uname", array($r_uname));

    if((pg_num_rows($result_loginname) != 0) Or (pg_num_rows($result_uname) != 0)) {
        echo htmlspecialchars("<p style='color: azure'>Your login name or user name has been already registered. </p>");
        echo htmlspecialchars("<p style='color: azure'>Please try again! </p>");
        $previous = "javascript:history.go(-1)";
        if(isset($_SERVER['HTTP_REFERER'])) {
            $previous = $_SERVER['HTTP_REFERER'];
        }
        ?>
        <a href="<?= $previous ?>" style="font-size:10pt;color:white;background-color:green;border:2px solid #336600;padding:3px">Back</a>
        <?php
    } elseif(strlen($r_loginname)<6 Or strlen($r_password)<6 Or strlen($r_uname)<6) {
        echo htmlspecialchars("<p style='color: azure'>Your login name or user name or password must be at least 6 chars! </p>");
        echo htmlspecialchars("<p style='color: azure'>Please try again! </p>");
        if(isset($_SERVER['HTTP_REFERER'])) {
            $previous = $_SERVER['HTTP_REFERER'];
        }
        ?>
        <a href="<?= $previous ?>" style="font-size:10pt;color:white;background-color:green;border:2px solid #336600;padding:3px">Back</a>
        <?php
    } else {
        $result_newusr_insert = pg_prepare($db, "query_newusr_insert", "INSERT INTO usr VALUES($1, $2, $3);");
        $result_newusr_insert = pg_execute($db, "query_newusr_insert", array($r_uname, $r_loginname, $r_password));

        if(!$result_newusr_insert) {
            echo htmlspecialchars("<p style='color: azure'>Internal database error!</p>");
            exit;
        } else {
            $_SESSION ['uname'] = $r_uname;
            echo htmlspecialchars("<p style='color: azure'>You've registered successful!</p>");
            echo htmlspecialchars("<p style='color: azure'>Going to info completion session...</p>");
            sleep(3);
            header('Location: userinfo.html');
        }
    }
?>

</body>
