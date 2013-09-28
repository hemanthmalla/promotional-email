<?php
echo "<script type='text/javascript'>";
echo "var trigger;";
if(isset($_GET['action'])){
    echo "var action='".$_GET['action'].'\';';
    switch($_GET['action']){
        case 'unsubscribe':{
           if(isset($_GET['email']) && isset($_GET['token'])) {
               echo 'var email=\''.$_GET['email'].'\';';
               echo 'var token=\''.$_GET['token'].'\';';
           }else{
               echo "var trigger='broken link';";
           }
        }
        break;
        default:{
            echo "var trigger='broken link';";
        }
    }
}else{
    echo "var trigger='broken link';";
}
echo "</script>";
?>    
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <script type='text/javascript' src='../js/jscript_1.js' ></script>
        <script type='text/javascript' src='../js/jscript_2.js' ></script>
        <link type='text/css' rel='stylesheet' href='../js/jscript.css'/>
        <title>Subscription</title>
    </head>
    <body>
        <h1>Subscription status</h1>
        <div id="unsubscribe">
            <span>Are You sure you want to un subscribe?</span><br>
            <button id="unsubscribe-proceed">Yes</button>
            <button id="unsubscribe-cancel">No</button>
        </div>
        <div id="message" style="display:none">
            <p>You have succesfully unsubscribed from our mailing list.</p>
        </div>
        <div id="re-subscribe" style="display:none">
            <span>You can also re subscribe here:</span>
            <button id="re-subscribe-proceed">Subscribe</button>
        </div>
        <div id="broken-link" style="display:none">
            <p>broken link</p>
        </div>
    </body>
</html>
<script type="text/javascript">
    $(document).ready(function(){
        if(trigger==='broken link'){
            $('#unsubscribe').css('display','none');
            $('#broken-link').css('display','block');
        }
        $('#unsubscribe-proceed').click(function(){
            $.post('process.php',{'action':'unsubscribe','email':email,'token':token},function(data){
                data= JSON.parse(data);
                //console.log(data);
                if(data['status']=='0'){
                    $('#unsubscribe').css('display','none');
                    $('#message').html('<p>You have succesfully un subscribed</p>');
                    $('#message').css('display','block');
                    $('#re-subscribe').css('display','block');
                }else{
                    $('#unsubscribe').css('display','none');
                    $('#message').html('<p>'+data['error']+'</p>');
                    $('#message').css('display','block');
                }
            });
        });
        
         $('#re-subscribe-proceed').click(function(){
            $.post('process.php',{'action':'subscribe','email':email,},function(data){
                data=JSON.parse(data);
                if(data['status']=='0'){
                    $('#unsubscribe').css('display','block');
                    $('#re-subscribe').css('display','none');
                    $('#message').html('<p>You have succesfully re-subscribed</p>');
                    $('#message').css('display','block');
                }else{
                    $('#unsubscribe').css('display','none');
                    $('#message').html('<p>'+data['error']+'</p>');
                    $('#message').css('display','block');
                }
            });
        });

    });
</script>    

