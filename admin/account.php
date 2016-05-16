<?php
ob_start();
 require_once "config.php";
 require_once "session.php";
 require_once "functions.php";
 confirm_logged_in();
 $cid = $_SESSION['uid'];
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
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css"/>
    <script src='js/jquery-1.9.1.js'></script>
    <script src='js/bootstrap.min.js'></script>
    <script src='js/jquery-ui.js'></script>
    <script type="text/javascript" src="js/js.js"></script>			
	<title>My Account:: Autoresponder System</title>
</head>
<body>
<div class="container-fluid all">
<div class="row-fluid header">
<div class="span8" style="font-size: 2em;">Autoresponder System</div>
</div>
<div class="row-fluid">
<div class="span2" id="accordion"><?php include "header.php";?></div>
<div class="span8">
<h4 style="color: #0097AA; padding: 5px 5px 5px 2px; font-family:Arial, sans-serif;border-bottom: 1px solid rgb(204, 204, 204);">Account Details</h4>
    <form action="" method="POST" style="height: 350px;">
       <div class="span12 shadow" style="padding-left: 10px; margin-top: 10px;">
       <fieldset style="margin-top: 10px;">         
          <table style="" align="center">
          <tr><td></td><td>
           <?php  
              if($_POST['update']){ //
                $uname = protect($_POST['username']);
                $email = trim($_POST['email']);
                $password = $_POST['password'];
                $cid = $_SESSION['uid'];
                  $query = mysql_query("UPDATE users SET username='$uname',email='$email',password='$password' WHERE username='$cid'");
                  $_SESSION['uid'] = $uname;
                  $_SESSION['email'] = $email;
                  echo "<div style=\"color: green; padding-bottom:5px; padding-top: 10px;\">"."Information Updated"."</div>";
             }
       ?></td></tr>
            <tr>
            <td><label>Email Address</label></td>
            <td><input type="email" name="email" value="<?php echo $_SESSION['email']; ?>" required="" style="" placeholder="Enter Email"/></td>
           </tr>
           <tr>
            <td><label>Username</label></td>
            <td><input type="text" name="username" id="username" value="<?php echo $_SESSION['uid']; ?>" required="" style="" placeholder="Enter Preferred Username"/></td>
           </tr>
            <tr>
            <td><label>Password</label></td>
            <td><input type="password" name="password" id="password" value="<?php echo $_SESSION['passCode'];?>" required="" style="width: 80%;" placeholder="Choose a Password"/></td>
           </tr>
           <tr><td></td><td><input type="submit" name="update" class="btn btn-info" value="Update"/></td></tr>
          </table>
          </div>                 
          </fieldset>
</form> 
</div> 
</div>
<?php include "footer.php";?>
</div>
</body>
</html>