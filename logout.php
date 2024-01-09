<?php
    session_start();        //Start the session

    //Perform any other logout related tasks if needed

    session_destroy();      //Destroy the session (i.e. logout the user)

    //redirect the (now logged out) user to the index.php page: 

    header('Location: index.php');
    exit;

?>