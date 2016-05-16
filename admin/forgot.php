<?php
ob_start();
 require_once "config.php";
 require_once "functions.php";
?>
<!DOCTYPE HTML>
    <html>
    <head>
	<meta http-equiv='content-type' content='text/html'/>
    <meta name='description' content=''/>
    <meta name='keywords' content=''/>
    <link rel='shortcut icon' href='images/logo.png'/>
    <link href='css/bootstrap.css' rel='stylesheet' type='text/css'/>
    <link href='css/bootstrap-responsive.css' rel='stylesheet' type='text/css'/>
    <link rel='stylesheet' href='css/main.css'/>
    <link href='css/smoothness/jquery-ui-1.10.3.custom.css' rel='stylesheet'/>    
    <script src='js/jquery-1.9.1.js'></script>
    <script src='js/bootstrap.min.js'></script>
    <script src='js/jquery-ui.js'></script>
    <script type="text/javascript" src="js/index.js"></script>				
	<title>Forgot Password:: Autoresponder System</title>	
</head>
<body>
<div class="container-fluid all">
<div class="row-fluid"> 
		<div class="span5 offset4" style="height: 500px; margin-top: 150px;">
			<h3 style="">Forgot Password</h3>           
      <form action="" method="POST" class="">
      <?php  
         if($_POST['submit']){  //
         $email	= trim($_POST['email']);
         if(check_db_email($email) == true){
            include "mail_class.php";
            $password = list_db_password($email);
            $mail = new SimpleMail();
            $emailBody = "";
            $mail-> setToAddress($email);
            $mail-> setFromAddress('support@autorespondersystem.com');
            $mail-> setSubject("Password Retrieval");
            $emailBody .= "Hi "."<br/>";;
            $emailBody .= "Your password is <b>$password</b><br/>";
            $emailBody .= "If you did not request for password retrieval, kindly  contact us at."."<br/><br/>";
            $emailBody .= "Thank You,"."<br>"."------"."<br/><br/>";
            $emailBody .= "Email Panel"."<br>";
            $mail-> setHTMLBody($emailBody);
            $mail-> send();
            unset($mail);
            echo "<div style=\"color: green; padding-bottom:5px; padding-top: 10px;\">"."Password has been sent to $email"."</div>";      
           }
          else{
            echo "<div style=\"color: red; padding-top: 10px; padding-bottom: 5px;\">"."Email not registered!"."</div>";
          }
      }
?>
         <label>Enter Email:</label>
         <input type="email" name="email" id="username" required="" value="<?php echo isset($email)?$email:"";?>" placeholder="Enter your email"/><br />
         <input type="submit" name="submit" value="RETRIEVE" class="btn btn-info"/><br /><br />
         <a href="home" class="btn">HOME</a>
     </form>
  </div>  	
<?php include "footer.php";?>
</body>
</html>
