<?php

    if(!empty($_POST['details'])){
        
        //$html_link= $_POST['html_page_link'];
        $details=$_POST['details'];
        $expires_on='0';
        $num_of_emails='0';
        
        if(!empty($_POST['expires_on'])){
            $expires_on=$_POST['expires_on'];
        }
        if(!empty($_POST['num_of_emails'])){
            $num_of_emails=$_POST['num_of_emails'];
        }
        
        $status= addJob( $details, $expires_on, $num_of_emails);
        
        echo json_encode($status);
        
        
    }else{
        echo 'invalid call';
    }
    


function addJob( $details, $expires_on, $num_of_emails){
    //filter_var($html_link,FILTER_VALIDATE_URL);
    $return_val=array('status'=>1,'error'=>'');
    //check if date is not already expired.
    $expiry_timestamp;
    if($expires_on!=0){
        $date=explode('/',$expires_on);
        $expiry_timestamp=mktime(0,0,0,$date[1],$date[0],$date[2]);
        //echo $expiry_timestamp;
        if(time()>mktime(0,0,0,$date[1],$date[0],$date[2])){
            $return_val['error']='Expiry date not valid';
            return $return_val;
            exit;
        }
    }
    
    require_once $_SERVER['DOCUMENT_ROOT'].'/mailer/application/connect_db.php';
    //echo $expiry_timestamp;
    $object= new connect_db();
    $ret=$object->add_job($details, $expires_on, $num_of_emails);
    if($ret['status']){
        $return_val['status']=1;
        $return_val['job_id']=$ret['result']['job_id'];
        $return_val['folder_stat']=$ret['result']['folder_stat'];
    }else{
        $ret['error']='Internal error';
    }
    return $return_val;
}
?>
