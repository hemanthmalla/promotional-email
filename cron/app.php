<?php

class Cron_helper{
    public $ob;
    function __construct() {
        require_once $_SERVER['DOCUMENT_ROOT'].'/mailer/application/connect_db.php';
        $this->ob=new connect_db();
    }
    
    public function SelectJob(){
        
        
        $ret=$this->ob->Get_Unfinished_Jobs();
        
        $job;
        if($ret['status']==0){
        $job_id=$ret['result'][rand(0,count($ret['result'])-1)];
        $ret=$this->ob->Get_job_details($job_id);
        if($ret['status']==0){
            $job=$ret['result']['0'];
        }
        }
        if(isempty($job)){
            exit();
        }
        return $job;
    }
    
    public function GetHTML_Email($job,$email,$id){
        // assuming email is saved in a file in the same server.
        //database has the link to the same file, relative to root path.
        
        $filename = $_SERVER['DOCUMENT_ROOT']."/".$job['html_email_page_link'];
        $handle = fopen($filename, "r");
        $contents = fread($handle, filesize($filename));
        fclose($handle);
        
        //$email=$job['email_id'];
        $token=md5($email.$id);
        $unsub_link="http://".$_SERVER['SERVER_NAME']."/mailer/subscription/?action=unsubscribe&email=$email&token=$token";
        $contents.="<footer style='font-size:8px; font-color:blue;'>
                    To unsubscribe from our mailing list,<a href='$unsub_link'> click here</a> 
                    </footer>";
        return $contents;
    }
    
    public function SendMail(){
        
        
        
        $job=$this->SelectJob();
        $email_id=$this->ob->retrieve_email(10, $job['current_email_index']);
        
        
        $subject=$job['details_of_job'];
        //$message=$HTML_email;
            
        $headers  ="FROM: Mobdeeds@mobdeeds.com"."\r\n";
        $headers .= 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
            
        foreach($email_id['result'] as $email){
            $message=$this->GetHTML_Email($job,$email['email_id'],$email['id']);
            $to=$email['email_id'];
            mail($to,$subject,$message,$headers);
            
            $this->ob->update_email_log($job['id'], $email['id']);
            $this->ob->update_email_job($job['id'], $email['id'], $this->is_job_completed($email['id']));
        }
        
    }
    
    public function is_job_completed($email_id){
        
        
        $last_id;
        $res=$this->ob->get_last_email_id();
        if($res['status']==0){
            $last_id=$res['result'];
        }
        if($email_id>=$last_id){
            return true;
        }else
            return false;

        
    }
    
}

//$ob= new Cron_helper();
//$ob->SendMail();
?>
