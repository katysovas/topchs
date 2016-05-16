<?php
ini_set('post_max_size', '64M');
ini_set('upload_max_filesize', '64M');
ini_set("date.timezone", "America/New_York");
ob_start();
 require_once "config.php";
 require_once "timeout.php";
 require_once "session.php";
 require_once "functions.php";
 confirm_logged_in();
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
    <link href='//cdnjs.cloudflare.com/ajax/libs/jquery-ui-timepicker-addon/1.6.1/jquery-ui-timepicker-addon.min.css' rel='stylesheet'/>
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css"/>
    <script src='js/jquery-1.9.1.js'></script>
    <script src='js/bootstrap.min.js'></script>
    <script src='js/jquery-ui.js'></script>
    <script src='//cdnjs.cloudflare.com/ajax/libs/jquery-ui-timepicker-addon/1.6.1/jquery-ui-timepicker-addon.min.js'></script>
    <script type="text/javascript" src="js/index.js"></script>	
    <script type="text/javascript" src="js/urldemo.js"></script>		
  <title>Upload Message Template:: Autoresponder System</title>
</head>
<body>
<div class="container-fluid all">
<div class="row-fluid header">
<div class="span8" style="font-size: 2em;">Autoresponder System</div>
</div>
<div class="row-fluid">
<div class="span2" id="accordion"><?php include "header.php";?></div>
<div class="span8" id="">
<div style="background-color: #98AFC7; padding: 10px;margin-top: 10px;"><i class="fa fa-file" style="color: white;"> </i> Upload Message Template</div>
<form action="" method="POST" class="">
 <?php
   if(isset($_POST['submit'])){
          $keyword = $_POST['keyword'];
          $message = $_POST['message'];
          $url = (!empty($_POST['longurl'])? $_POST['longurl']:"");          
          $mediaurl = (!empty($_POST['mediaurl'])? $_POST['mediaurl']:""); 
          $from_ = trim($_POST['from_']);
          $to_  = $_POST['to_'];
          $to = trim(substr($to_,0,-2))."00";
          $domain = $_SERVER['HTTP_HOST'];
          $end = date('Y-m-d H:i:s', strtotime($to.'+1 day'));
          $dates = createDateRange($from_, $end);    
          $ignoreDates = $_POST['ignoreDates'];
          if (!empty($to_)){
            foreach($dates as $date){
                  $query = mysql_query("INSERT INTO templates (date_,keyword,message,domain,url,mediaurl) VALUES ('$date','$keyword','$message','$domain','$url','$mediaurl')") or die(mysql_error());  
            } 
          }
          else{
            $query = mysql_query("INSERT INTO templates (date_,keyword,message,domain,url,mediaurl,ignoreDates) VALUES ('$from_','$keyword','$message','$domain','$url','$mediaurl','$ignoreDates')") or die(mysql_error());  
          }
         echo "<div style=\"color: green; padding-bottom:5px; padding-top: 10px;\">"."Template Upload</div>";                                                          
   }
?>
 <div class="row-fluid content" style="height: 300px;">    
    <h5 style="padding-left: 10px;">Fill in the fields below</h5>
    <div class="span5">
    <table>
    <tr>
     <td>Keyword:</td>
     <td>
       <select name="keyword">
          <option value="music">Music</option>
          <option value="events">Events</option>
          <option value="gasR">Regular Gas</option>
          <option value="gasP">Premium Gas</option>
      </select>
    </td> 
    </tr>
    <tr>
     <td>Message:</td>
     <td><textarea name="message" rows="4" cols="50" onkeyup="countChar(this)"></textarea><div id="charNum"></div></td> 
    </tr>
    <tr>
     <td>Long Url:</td>
     <td><input type="text" name="longurl" style="" id="long-url" placeholder="long url" value=""/> </td> 
    </tr>
    <tr><td></td>
        <td><a href="javascript:;" id="shorten-url">Shorten </a></td> 
    </tr>
    <tr>
     <td>Media Url:</td>
     <td><input type="text" name="mediaurl" style="" placeholder="Media url" value=""/></td> 
    </tr>
    </table>
    </div>
    <div class="span6"> <h6>Date Range</h6>
    <table>
    <tr>
     <td>From:</td>
     <td><input type="text" name="from_" style="" id="datepicker"/></td> 
    </tr>
    <tr>
     <td>To:</td>
     <td><input type="text" name="to_" style=""id="datepicker2"/></td> 
    </tr>
    <tr>
     <td>Ignore Dates</td>
     <td><input type="checkbox" name="ignoreDates" value="1"/></td> 
    </tr>
    </table>
     <div style="float: right; margin-right: 50px;">
      <a href="uploadcontent.php" class="btn"> CLEAR</a>
     <input type="submit" value="SUBMIT" name="submit" class="btn btn-primary"/>
    </div>
    </div>
    </div>
  </form>
</div>
</div>  	
<?php include "footer.php";?>
</div>
</body>
</html>