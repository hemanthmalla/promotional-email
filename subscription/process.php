<?php

/*input data action--> subscribe, unsubscribe
for unsubsribe--> token(sent to the email), and email_id.
for subscribe--> emails_id
*/
if(isset($_POST['action'])){
    switch($_POST['action']){
        case 'unsubscribe':{
            if(isset($_POST['token'])&& isset($_POST['email'])){
                $return= unsubscribe($_POST['email'],$_POST['token']);
            }
        }
        break;
        case 'subscribe':{
            if(isset($_POST['token'])){
                $return= subscribe($_POST['email']);
            }
        }
    }
}

function unsubsribe($email,$token){
    
    require_once $_SERVER['DOCUMENT_ROOT'].'/mailer/application/connect_db.php';
    
    $object= new connect_db();
    $primary_key=$object->get_primarykey($email);
    
    if($token== md5($email.$primary_key)){
       if($ret= $object->unsubscribe($email)){
           return true;
       }else{
           return false;
       }
    }
}

function subscribe($email){
    
    require_once $_SERVER['DOCUMENT_ROOT'].'/mailer/application/connect_db.php';
    $object= new connect_db();
    
    return $object->subscribe($email);
    
}
?>
