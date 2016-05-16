<?php
ini_set('post_max_size', '64M');
ini_set('upload_max_filesize', '64M');
ini_set("date.timezone", "America/New_York");
ob_start();
 require_once "config.php";
 require_once "timeout.php";
 require_once "session.php";
 confirm_logged_in();
 require_once "functions.php";
 require_once "urlshortener.php";
 $id = trim($_GET['cpg']);
 $details = listTemplate($id);
 $longurl = getLongurl($details[0]['url'])
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
    <script type="text/javascript" src="js/index.js"></script> 
    <script type="text/javascript" src="js/urldemo.js"></script>         
  <title>View Message Template:: Autoresponder System</title>
</head>
<body>
<div class="container-fluid all">
<div class="row-fluid header">
<div class="span8" style="font-size: 2em;">Autoresponder System</div>
</div>
<div class="row-fluid">
<div class="span2" id="accordion"><?php include "header.php";?></div>
<div class="span8" id="">
<div style="background-color: #98AFC7; padding: 10px;margin-top: 10px;"><i class="fa fa-file" style="color: white;"> </i> Message Template</div>
<form action="" method="POST" class="">
 <?php
   if(isset($_POST['submit'])){
          $keyword = $_POST['keyword'];
          $message = $_POST['message']; 
          $mediaurl = $_POST['mediaurl'];
          $url = $_POST['longurl'];
          $date_ = $_POST['date_'];          
          $domain =  $_POST['domain'];
         //  if(!empty($url)){
         //  if(!urlExistsInDb($url)){
         //    if(validateUrlFormat($url)){
         //     $shortUrl = new ShortUrl();
         //    try {
         //     $id = insertUrlInDb($domain,$url);
         //     $len = strlen($url);
         //     $shorturl = $shortUrl->urlToShortCode($len);
         //     insertShortCodeInDb($id,$shorturl);             
         //    }
         //   catch (Exception $e) {
         //     echo "<div style=\"color: red; padding-bottom:5px; padding-top: 10px;\">"."Invalid url"."</div>";          
         //     }
         //   }
         //   else{
         //    echo "<div style=\"color: red; padding-bottom:5px; padding-top: 10px;\">"."URL does not appear to be in the correct format."."</div>";    
         //   }
         //  }
         // else { //fetch the short url associated with url
         //     $shorturl = getShorturl($url);          
         //   }
         //  }
          $query = mysql_query("UPDATE templates SET date_='$date_',keyword='$keyword',message='$message',domain='$domain',url='$url',mediaurl='$mediaurl' WHERE id = '$id'") or die(mysql_error());              
          echo "<div style=\"color: green; padding-bottom:5px; padding-top: 10px;\">"."Template Updated</div>";
           $page = "view.php?cpg=$id";
           header("Refresh: 2; url=$page");                                                          
   }
?>
 <div class="row-fluid content" style="height: 300px;">    
    <h5 style="padding-left: 10px;">Edit</h5>
    <div class="span5">
    <table>
    <tr>
     <td>Keyword:</td>
     <td><input type="text" name="keyword" style="" required="" placeholder="Keyword" value="<?php echo $details[0]['keyword'];?>"/></td> 
    </tr>
    <tr>
     <td>Message:</td>
     <td><input type="text" name="message" style="" required="" placeholder="Message" value="<?php echo $details[0]['message'];?>"/></td> 
    </tr>
    <tr>
     <td>Url:</td>
     <td><input type="text" name="longurl" id="long-url" placeholder="long url" value="<?php echo $details[0]['url'];?>"/>
        <a href="javascript:;" id="shorten-url">
          Shorten 
        </a>  
      </td> 
    </tr>
    <!-- <tr>
     <td>Short Url:</td>
     <td><input type="text" name="url" style="" readonly="" placeholder="Shortened url" value="<?php echo $details[0]['url'];?>"/></td> 
    </tr>
    <tr> -->
     <td>Media Url:</td>
     <td><input type="text" name="mediaurl" style="" placeholder="Media url" value="<?php echo $details[0]['mediaurl'];?>"/></td> 
    </tr>
    </table>
    </div>
    <div class="span6" style="padding-top: 40px;"> 
    <table>
    <tr>
     <td>Domain:</td>
     <td><input type="text" name="domain" style="" value="<?php echo $details[0]['domain'];?>"/></td> 
    </tr>
    <tr>
     <td>Date:</td>
     <td><input type="text" name="date_" style="" id="datepicker" value="<?php echo $details[0]['date_'];?>"/></td> 
    </tr>
    </table>
     <div style="float: right; margin-right: 50px;">
      <a href="view.php?cpg=<?php echo $id;?>" class="btn"> CLEAR</a>
     <input type="submit" value="Edit" name="submit" class="btn btn-primary"/>
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