<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <script type='text/javascript' src='../js/jscript_1.js' ></script>
        <script type='text/javascript' src='../js/jscript_2.js' ></script>
        <link type='text/css' rel='stylesheet' href='../js/jscript.css'/>
        <title>Add Job</title>
    </head>
    <body>
        <?php
        // put your code here
        
        ?>
        <div id="form">
            <label name="html_page_link">Html Page link</label><br>
            <input type="text" name="html_page_link"><br>
            <label name='details'>Details</label><br>
            <input type='text' name='details'><br>
            <label name='expires_on'>Expires On</label><br>
            <span style="font-size:12px;">expiry date in dd/mm/yy format</span><br>
            <span style='font-size: 12px'>Enter 0 if no expiry date.  </span><br>
            <input type='text' name='expires_on'><br>
            <label name='num_of_emails'>Number Of Emails To send</label><br>
            <span style='font-size: 12px'> Enter 0 if send to all</span><br>
            <input type='text' name='num_of_emails'><br>
            <button id="add-job">Submit</button>
        </div>
        <div id='status'>
            
        </div>
    </body>
</html>
<script type="text/javascript">
    $(document).ready(function(){
        $('#add-job').click(function(){
            var html_link=$('input[name=html_page_link]').val();
            var details=$('input[name=details]').val();
            var expires_on=$('input[name=expires_on]').val();
            var num_of_emails=$('input[name=num_of_emails]').val();
            
            $.post('process.php',{'html_page_link':html_link,'details':details,'expires_on':expires_on,'num_of_emails':num_of_emails},function(data){
                var data=JSON.parse(data);
                if(data['status']!=0){
                    $('#status').html('<p>'+data['error']+'</p>');
                }else{
                    $('#status').html('<p>Succecfully added</p>');
                }
            });
        });
    });
</script>    