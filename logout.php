<!DOCTYPE html>
<html>

    <body>
        <?php
            # we have to start the session to begin working with the current user's session
            session_start();
        
            # setting the session to a new empty array and clear all variables
            $_SESSION = array();

            session_destroy();
            header("location: https://www.otamotweb.com");
        ?>
    </body>
</html>
