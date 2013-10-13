<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/mailer/application/connect_db.php';

$object= new connect_db();
$status=$object->status();

//print_r($status);
?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <script type='text/javascript' src='../js/jscript_1.js' ></script>
        <script type='text/javascript' src='../js/jscript_2.js' ></script>
        <link type='text/css' rel='stylesheet' href='../js/jscript.css'/>
        <title>Status</title>
    </head>
    <body>
        <h1>Status</h1>
        <table border='1px'>
            <tr>
                <td>id</td>
                <td>Details</td>
                <td>status</td>
                <td>Job added on</td>
                <td>email no to send</td>
                <td>email already sent</td>
                <td>expired on</td>
                <td>send to all</td>
                <td>current email index</td>
                <td>is job completed</td>
                <td>Change state</td>
            </tr>    
        <?php
        foreach($status as $loop){
            echo '<tr>';
            foreach($loop as $key=>$value){
                echo '<td>'.$value.'</td>';
            }
            echo "<td><a href='changestate.php?job_id=".$loop['id']."'>change state</a></td>";
            echo '</tr>';
        }
        ?>
        </table>
        
    </body>
</html>
