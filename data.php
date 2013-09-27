<?php
$host;
$user;
$password;
$db;

//database details
if($_SERVER['SERVER_NAME']=='localhost'){
    $host="localhost";
    $user='root';
    $password="test";
    $db='promotional_mail';
    
}
if($_SERVER['SERVER_NAME']=='urbancleaning.in'){
    $host="urbancleaning.in";
    $user='urbancle_mobdeed';
    $password="Robo_76";
    $db='urbacncle_promotional_mail';
}
?>
