<?php
session_start();
    $idletime=1200;//after 20 minutes the user gets logged out
    if(time()-$_SESSION['timestamp']>$idletime){
        session_destroy();
        session_unset();
     }
    else{
    $_SESSION['timestamp']=time();
    }
?>
