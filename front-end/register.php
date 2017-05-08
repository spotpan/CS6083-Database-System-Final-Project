<html>
<head>
    <meta charset="UTF-8">
    <title>Welcome!</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 5/8/2017
 * Time: 2:26 PM
 */

    session_start();
    $db = pg_connect("host=localhost port=5432 dbname=funfund user=postgres password=chenny1993");

    $r_uname = pg_escape_string($_POST['r_uname']);
    $r_loginname = pg_escape_string($_POST['r_loginname']);
    $r_password = pg_escape_string($_POST['r_password']);


    $query_usr_loginname = "SELECT uname FROM usr WHERE uloginname = '$r_loginname'";
    $query_usr_uname = "SELECT uname FROM usr WHERE uloginname = '$r_uname'";
    $result_loginname = pg_query($query_usr_loginname);
    $result_uname = pg_query($query_usr_uname);

    if((pg_num_rows($result_loginname) != 0) Or (pg_num_rows($result_uname) != 0)) {
        echo "Your login name or user name has been already registered. <br>";
        echo "Please try again! <br>";
    }


?>