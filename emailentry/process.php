<?php

if(isset($_POST['submit'])){
    if(isset($_POST['emails']) && !empty($_POST['emails'])){
        
        $emails=trim($_POST['emails']);
        $entry_result= Email_entry($emails);
        
        echo json_encode($entry_result);
    }else{
    echo 'invalid input';
    }
}

function Email_entry($emails){
    require_once $_SERVER['DOCUMENT_ROOT'].'/mailer/application/connect_db.php';
    
    $object= new connect_db();
    $emails= explode(',',$emails);
    
    $entry_result=array();
    
    foreach($emails as $email){
        $temp=array();
        array_push($temp,$email);
        if(filter_var($email,FILTER_VALIDATE_EMAIL)){
            if(! $object->email_exists($email)){
                if($object->add_email($email)){
                    array_push($temp,'Succesful');
                }
            }else{
                array_push($temp,'Email exists');
            }
        }else{
            array_push($temp,'Not valid email');
        }
        array_push($entry_result, $temp);
    }
    //print_r($entry_result);
    return $entry_result;
}
?>
