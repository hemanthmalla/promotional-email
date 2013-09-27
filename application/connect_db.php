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
        if($this->email_exists($email)){
            $mysqli=$this->connect();
            
            $query="UPDATE `promotional_email_table` SET `is_subscribed`='1' WHERE `email_id`='$email'";
            $result=$mysqli->query($query);// or die($mysqli->error);
            
            if($mysqli->affected_rows>0){
            return true;
            }else{
                return false;
            }
        $mysqli->close();
        }else{
            return false;
        }
    }
    function test(){
        echo $this->host;
    }
}

?>