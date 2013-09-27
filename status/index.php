<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/mailer/application/connect_db.php';

$object= new connect_db();
$status=$object->status();

//print_r($status);
?>
