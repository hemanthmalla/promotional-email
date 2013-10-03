<?php
require $_SERVER['DOCUMENT_ROOT'].'/mailer/include.php';



class connect_db{
    private $host;
    private $user;
    private $password;
    private $db;
    
    public function __construct(){
        require_once ROOT_DIR."/data.php";
        $this->host=$host;
        $this->user=$user;
        $this->password=$password;
        $this->db=$db;
    }
    private function connect(){
        $mysqli= new mysqli($this->host,$this->user,$this->password,$this->db);
        return $mysqli;
    }
    public function email_exists($email){
        $mysqli= $this->connect();
        
        $query="SELECT * FROM `promotional_email_table` WHERE email_id='$email'";
        
        $mysqli->query($query);// or die("Error:".$mysqli->error);
        //echo $mysqli->affected_rows;
        if($mysqli->affected_rows>0){
            return true;
        }else{
        return false;
        }
        $mysqli->close();
    }
    public function add_email($email){
        $mysqli=$this->connect();
        
        $query="INSERT INTO `promotional_email_table` (`email_id`) VALUES ('$email')";
        $mysqli->query($query);//or die("Error:".$mysqli->error);
        
        if($mysqli->affected_rows==1){
            return true;
        }else{
            return false;
        }
        $mysqli->close();

    }
    public function add_job($html_link,$details, $expiry, $num_of_emails){
        $mysqli=$this->connect();
        
        $expiry= date('Y-m-d H:m:s',$expiry);
        $query="INSERT INTO `email_job_table` (html_email_page_link,details_of_job,expired_on,email_number_to_send)";
        $query.="VALUES('$html_link','$details','$expiry','$num_of_emails')";
        
        $mysqli->query($query) or die($mysqli->error);
        
        $job_id;
        if($mysqli->affected_rows>0){
            //return true;
            $query="SELECT id FROM `email_job_table` WHERE `html_email_page_link`='$html_link'";
            $result=$mysqli->query($query) or die($mysqli->error);
            if($row=$result->fetch_array()){
                //print_r($row);
                $job_id=$row[0];
               // return $row[0];
            }
        }
        
      // echo 'TO num email<br>';
        if($num_of_emails>0){
            $email_list=array();
            $query="SELECT id FROM `promotional_email_table` ORDER BY id DESC LIMIT $num_of_emails";
            $result=$mysqli->query($query);
            while($row=$result->fetch_array(MYSQLI_NUM)){
                //print_r($row);
                array_push($email_list,$row[0]);
            }
            //print_r($email_list);
            $email_list=implode(',',$email_list);
            //echo $email_list;
            $query="INSERT INTO `temp_email_pool` (list_email_id_foreign_key,job_id) VALUES('$email_list','$job_id')";
            $result=$mysqli->query($query)or die($mysqli->error);
            //return $job_id;
        }
        
        return $job_id;
        $mysqli->close();
    }
    
    public function status(){
        
        $mysqli=$this->connect();
        
        $query="SELECT * FROM `email_job_table`";
        
        $result=$mysqli->query($query);
        $data=array();
        while($row=$result->fetch_assoc()){
            array_push($data,$row);
        }
        $mysqli->close();
        return $data;
    }
    
    public function get_primarykey($email){
        $mysqli= $this->connect();
        
        $query="SELECT id FROM `promotional_email_table` WHERE `email_id` ='$email'";
        $result=$mysqli->query($query);//or die($mysqli->error);
        
        if($row=$result->fetch_row()){
            return $row[0];
        }else{
            return false;
        }
        $mysqli->close();
    }
    
    public function unsubscribe($email){
        $mysqli=$this->connect();
        
        $query="UPDATE `promotional_email_table` SET `is_subscribed`='0' WHERE `email_id`='$email'";
        $result= $mysqli->query($query);// or die($mysqli->error);
        
        if($mysqli->affected_rows>0){
            return true;
        }else{
            return false;
        }
        $mysqli->close();
    }
    
    public function subscribe($email){
    
            $mysqli=$this->connect();
            
            $query="UPDATE `promotional_email_table` SET `is_subscribed`='1' WHERE `email_id`='$email'";
            $result=$mysqli->query($query);// or die($mysqli->error);
            
            if($mysqli->affected_rows>0){
            return true;
            }else{
                return false;
            }
        $mysqli->close();
        
    }
    public function Get_Unfinished_Jobs(){
        $mysqli=  $this->connect();
        
        $query="SELECT id FROM `email_job_table` WHERE `is_job_completed`='0'";
        $result=$mysqli->query($query);// or die($mysqli->error);
        
        $return_val=array('status'=>'1','error'=>'','result'=>array());
        if($result){
            $return_val['status']='0';
        }else{
            $return_val['error']='Some internal error';
        }
        while($row=$result->fetch_row()){
            array_push($return_val['result'],$row[0]);
        }
        $mysqli->close();
        return $return_val;
    }
    
    public function Get_job_details($job_id){
        $mysqli= $this->connect();
        
        $query="SELECT * FROM `email_job_table` WHERE `id`='$job_id'";
        $result=$mysqli->query($query);
        
        $return_val=array('status'=>'1','error'=>'','result'=>array());
        if($result){
            $return_val['status']='0';
        }else{
            $return_val['error']='Some internal error';
        }
        while($row=$result->fetch_assoc()){
            array_push($return_val['result'],$row);
        }
        $mysqli->close();
        return $return_val;
    }
    
    public function retrieve_email($num_of_emails,$index){
        $mysqli=$this->connect();
        $query="SELECT email_id,id FROM `promotional_email_table` WHERE is_subscribed='1' and id>'$index' ORDER BY `id` ASC LIMIT 0,$num_of_emails ";
        
        $result=$mysqli->query($query);
        $return_val=array('status'=>1,'error'=>'','result'=>array());
        
        if($result){
            $return_val['status']='0';
        }else{
            $return_val['error']='Some internal error';
        }
        while($row=$result->fetch_assoc()){
            array_push($return_val['result'],$row);
        }
        $mysqli->close();
        return $return_val;
    }
    
    public function update_email_log($job_id,$email_id){
        
        $mysqli=$this->connect();
        $time=  time();
        $query="INSERT INTO `email_sent_log` (email_id,job_id,email_sent_on)
                VALUES ('$email_id','$job_id','$time')";
        $result=$mysqli->query($query);
        $mysqli->close();
        if($result){
            return true;
        }
        else return false;
    }
    public function update_email_job($job_id,$email_index,$job_completed){
        $mysqli=$this->connect();
        $query="UPDATE `email_job_table` SET `current_email_index`='$email_index',`is_job_completed`='$job_completed'
                WHERE `id`='$job_id'";
        
        $result=$mysqli->query($query)or die($mysqli->error);
        
        $mysqli->close();
        if($result){
            return true;
        }else return false;
    }


    public function get_last_email_id(){
        $mysqli=$this->connect();
        $query="SELECT * FROM `promotional_email_table` WHERE `is_subscribed`='1' ORDER BY `id` DESC LIMIT 1";
        $result=$mysqli->query($query) or die($mysqli->error);
        $return_val=array('status'=>1,'error'=>'');
        while($row=$result->fetch_assoc()){
            $return_val['status']=0;
            $return_val['result']=$row['id'];
        }
        $mysqli->close();
        return $return_val;
    }
            
}

?>
