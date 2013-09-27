<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <script type='text/javascript' src='../js/jscript_1.js' ></script>
        <script type='text/javascript' src='../js/jscript_2.js' ></script>
        <link type='text/css' rel='stylesheet' href='../js/jscript.css'/>
        <title>Email Entry</title>
    </head>
    <body>
        <?php
        
        
        ?>
        <h1>Email Entry</h1>
            
            <label name="emails">Emails</label><br>
            <textarea name="emails" rows="10" cols="30">
            </textarea>
            <br>
            <button id='form-entry'>Submit</button>
         
        <div id='result'>
            
            <table id='entry-status'>
                <tr>
                    <td>Email</td>
                    <td>Status</td>
                </tr>
            </table>
        </div>
    </body>
</html>

<script type="text/javascript">
    $(document).ready(function(){
        $('#form-entry').click(function(){
            var return_val;
            var emails= $('textarea[name=emails]').val();
            $.post('process.php',{submit:"1",emails:emails},function(data){
                return_val=JSON.parse(data);
                console.log(return_val.length);
                var len= return_val.length;
                for(var i=0;i<len;i++){
                $('#entry-status').append('<tr><td>'+return_val[i][0]+'</td><td>'+return_val[i][1]+'</td></tr>')
                }
            });
            
        });
    });
        
</script>
