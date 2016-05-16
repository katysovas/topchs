<?php
ob_start();
 session_start();
  require_once "config.php";
  require_once "functions.php";
  $cid = $_SESSION['uid'];
  $cpg=$_GET['cpg'];//delete single template
  if(isset($cpg)){
        $query = mysql_query("DELETE FROM templates WHERE id = '$cpg'");
          header("Location: templates.php?filter=null&page=1");  
  }  
  elseif(isset($content)){
        $query = @mysql_query("DELETE FROM uploadcontent WHERE id = '$content'");
        $query = @mysql_query("DROP TABLE $service_id");
        $file_name = "uploads/".$file;  
        unlink($file_name);   
        header("Location: uploadcontent?filter=null&page=1");
           
  }
  elseif(isset($newuser)){
        $query = mysql_query("DELETE FROM newuser WHERE id = '$newuser'");
          if($query){
              header("Location: newuser");
           } 
  }
  elseif(isset($skip)){
        $query = mysql_query("DELETE FROM skip WHERE id = '$skip'");
          if($query){
              header("Location: specialschedules");
           } 
  }
  elseif(isset($early)){
        $query = mysql_query("DELETE FROM early WHERE id = '$early'");
          if($query){
              header("Location: specialschedules");
           } 
  }
  elseif(isset($late)){
        $query = mysql_query("DELETE FROM late WHERE id = '$late'");
          if($query){
              header("Location: specialschedules");
           } 
  }
?>