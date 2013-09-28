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
                echo json_encode($return);
            }
        }
        break;
        case 'subscribe':{
            if(isset($_POST['email'])){
                $return= subscribe($_POST['email']);
                echo json_encode($return);
            }
        }
        break;
    }
}

function unsubscribe($email,$token){
    
    require_once $_SERVER['DOCUMENT_ROOT'].'/mailer/application/connect_db.php';
    $return=array('status'=>1,'error'=>'');
    $object= new connect_db();
    $primary_key=0;
    if($object->email_exists($email)){
        //echo 'email exists';
        $primary_key=$object->get_primarykey($email);
    }
    else{
        $return['error']='Email does\'nt exists';
        return $return;
        
    }
    if($token== md5($email.$primary_key)){
       if($ret= $object->unsubscribe($email)){
           $return['status']=0;
       }else{
           $return['error']='Some internal error';
       }
    }else{
        $return['error']='Invalid token';
    }
    return $return;
}

function subscribe($email){
    
    $return=array('status'=>1,'error'=>'');
    
    require_once $_SERVER['DOCUMENT_ROOT'].'/mailer/application/connect_db.php';
    $object= new connect_db();
    
    if($object->email_exists($email)){
        if($object->subscribe($email)){
            $return['status']=0;
        }
    }else{
        $return['error']='Email does\'nt exists';
    }
    return $return;
    
}
?>
