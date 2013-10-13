<?php

if(isset($_GET['job_id'])&& !empty($_GET['job_id'])){
    $id=$_GET['job_id'];
    
    require_once $_SERVER['DOCUMENT_ROOT'].'/mailer/application/connect_db.php';

    $object= new connect_db();
    $status=$object->Get_job_details($id);
    $job_det=$status['result'];
    
    
    if(isset($_FILES) && !empty($_FILES)){
        
        require_once $_SERVER['DOCUMENT_ROOT'].'/mailer/status/process.php';
        
        $ob= new changestate($id);
        $res=$ob->file_upload($_FILES);
    }
    
    if(isset($_POST['change-state'])){
        
        $res=$object->ChangeState($id,$job_det[0]['status']);
        
    }
    
    $status=$object->Get_job_details($id);
    $job_det=$status['result'];
    
    $dir=$_SERVER['DOCUMENT_ROOT']."/mailer/mail/".$id;
    $files=  scandir($dir);
    
    
}
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
    <style type="text/stylesheet">
        #modal{
            background-color: #DEC6C6;
            
        }
    </style>
    <body>
        <h1>Files for job <?=$id?></h1>
        <?php
        foreach($files as $value){
            echo $value."<br>";
        }
        ?>
        <!-- file upload-->
        <h4>Upload files here</h4>
        <form action="<? echo $_SERVER['PHP_SELF']."?job_id=".$id;?>" method="post" enctype="multipart/form-data">
        <label for="file">Filename:</label>
        <input type="file" name="file" id="file"><br>
        <input type="submit" name="submit" value="upload">
        </form>
        
        <h4>Change job state</h4>
        <span>Current job state:</span><span><?echo $job_det[0]['status'];?></span>
        <form method="POST" action="<? echo $_SERVER['PHP_SELF']."?job_id=".$id;?>">
            <input type="hidden" value="1" name="change-state">
        <button id="state-change" type="submit">Change</button>
        </form>
    </body>
</html>