<?php

class changestate{
    public $job_id;
    function __construct($id){
        $this->job_id=$id;
    }
    
    public function file_upload($files){
        //print_r($files);
        if(!$files['file']['error']>0){
            $destination=$_SERVER['DOCUMENT_ROOT']."/mailer/mail/".$this->job_id."/";
           if(move_uploaded_file($files['file']['tmp_name'], $destination.$files['file']['name'])){
               return true;
           }
        }else{
            return $files['file']['error'];
        }
    }
}
?>
