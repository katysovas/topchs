<?php
ob_start();
 require_once "config.php";
 require_once "session.php";
 require_once "functions.php";
 if(logged_in()){
    header("Location: account.php");
 }
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
	<title>Home:: Autoresponder System</title>	
</head>
<body>
<div class="container-fluid all">
<div class="row-fluid">  
		<div class="span5 offset4" style="height: 500px; margin-top: 150px;">
     <h3 style="">LOGIN PANEL</h3>          
      <form action="" method="POST">
      <?php  
         if($_POST['submit']){  //during log in
         $username	= trim($_POST['username']);
         $password	= $_POST['password']; 
         if(empty($username) || empty($password)){
            echo "<div style='color: red;'>Please fill in all fields!</div>";
         }
         else{
         $details = show_user($username,$password);
         if(is_array($details)){
            $lastlogin = date("Y-m-d H:i:s",time());
            foreach($details as $value){
             $_SESSION['fullname'] = $value['fname'];
             $_SESSION['email'] = $value['email'];
             $_SESSION['uid'] = $value['username'];
             $_SESSION['user_level'] = $value['level']; 
             $_SESSION['passCode'] = $value['password'];            
             $query = mysql_query("UPDATE users SET lastlogin = '$lastlogin' WHERE username = '$username'",$connect);
             if($_SESSION['user_level']=='1'){ //validated user log in
                    header("Location: account.php");
                       }
              elseif($_SESSION['user_level']=='0'){ //inactive user login
                 echo "<div style='color: #254117;'>Check your email to activate account.</div>";
                       }
                    }
              // create the timestamp for timeout session
              $_SESSION['timestamp']=time();   	   
                 }
             else{
                echo "<div style='color: red;'>Unknown username or password!</div>";
             } 
          }
       }
?>
         <label>Username:</label>
         <input type="text" name="username" id="username" value="<?php echo isset($username)?$username:"";?>" placeholder="Enter your username" autofocus=""/><br />
         <label>Password:</label>
         <input type="password" name="password" id="password" value="<?php echo isset($password)?$password:"";?>" placeholder="Password"/><br />
         <input type="submit" name="submit" value="LOGIN" class="btn btn-info"/>
         <a href="forgot.php" style="color: blue;">Forgot Password?</a>
      </form>
    </div>
</div>  	
<?php include "footer.php";?>
</div>
</body>
</html>
