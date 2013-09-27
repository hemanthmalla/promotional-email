<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Email Entry</title>
    </head>
    <body>
        <?php
        // put your code here
        
        ?>
        <form>
            <label name="html_page_link">Html Page link</label><br>
            <input type="text" name="html_page_link"><br>
            <label name='details'>Details</label><br>
            <input type='text' name='details'><br>
            <label name='expires_on'>Expires On</label>
            <span style='font-size:8'>expiry date in dd/mm/yy format</span><br>
            <input type='date' name='expires on'><br>
            <label name='num_of_emails'>Number Of Emails To send</label><br>
            <input type='number' name='num_of_emails'><br>
            <input type="submit" name="Submit">
        </form>    
    </body>
</html>