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
    <script type="text/javascript" src="js/reports.js"></script>			
	<title>Templates:: Autoresponder System</title>
</head>
<body>
<div class="container-fluid all">
<div class="row-fluid header">
<div class="span8" style="font-size: 2em;">Autoresponder System</div>
</div>
<div class="row-fluid">
<div class="span2" id="accordion"><?php include "header.php";?></div>
<div class="span10">
<h4 style="color: #0097AA; padding: 5px 5px 5px 2px; font-family:Arial, sans-serif;border-bottom: 1px solid rgb(204, 204, 204);">All Message Templates</h4>
  <div class="row-fluid">
  <div class="span8">
    <form action="" method="GET" >
     <div class="input-prepend" style="margin-top: 10px;"><span class="add-on" style="color: black;">Filter By</span>
        <select name="filter" class="select">
            <option value="" selected="">..select..</option>
            <option value="keyword">Keyword</option>
            <option value="date">Date</option>
            <option value="gas">Gas</option>
         </select>
    <input type="text" name="new_value" value="" placeholder="" id="new_value"/>
    <input type="submit" name="filtering" value="Filter" style="margin-top: 3px;margin-left: 5px;" />
    </div>
   </form>
   </div>
</div>
<?php 
    include "pagination.php";
    $per_page = 15;
    $page = !empty($_GET['page']) ? (int)$_GET['page'] : 1;
  if(isset($_GET['filter'])){
       $filter_term = $_GET['filter'];
       $value = $_GET['new_value'];
        switch($filter_term){
            case 'keyword':
            if(empty($value)) break;
             $keyword = $value;
             $query = mysql_query("SELECT COUNT(*) AS count FROM templates WHERE keyword = '$keyword'");
             $row = mysql_fetch_array($query);
             $total_count = $row['count'];
             $pagination = new Pagination($page, $per_page, $total_count);
             $que = mysql_query("SELECT * FROM templates WHERE keyword = '$keyword' ORDER by date_ DESC LIMIT {$per_page} OFFSET {$pagination->offset()}");
            break;
            case 'gas':
             $que = mysql_query("SELECT * FROM templates WHERE keyword LIKE '%gas%'");
            break;
            case 'date':
            if(empty($value)) break;
             $date = $value;
             $query = mysql_query("SELECT COUNT(*) AS count FROM templates WHERE DATE(date_) = '$date'");
             $row = mysql_fetch_array($query);
             $total_count = $row['count'];
             $pagination = new Pagination($page, $per_page, $total_count);
             $que = mysql_query("SELECT * FROM templates WHERE DATE(date_) = '$date' ORDER by date_ DESC LIMIT {$per_page} OFFSET {$pagination->offset()}");
            break;
            case 'null':
             $query = mysql_query("SELECT COUNT(*) AS count FROM templates");
             $row = mysql_fetch_array($query);
             $total_count = $row['count'];
             $pagination = new Pagination($page, $per_page, $total_count);
             $que = mysql_query("SELECT * FROM templates ORDER by date_ DESC LIMIT {$per_page} OFFSET {$pagination->offset()}");
            break;
             }
          }
  if(is_resource($que)){
    if(mysql_num_rows($que)!=0){ ?>
<div class="row-fluid">
    <div class="span2" style=""><b>Keyword</b></div>
    <div class="span2"><strong>Date</strong></div>
    <div class="span2"><b>Message</b></div>
    
    <div class="span2"><b>URL</b></div>
    <div class="span2"><b>Media URL</b></div>
    <div class="span1"><b>ACTION</b></div>
</div>
<div style="height: 290px; overflow-y: auto; font-size: 1.0em;">
<?php     while($value=mysql_fetch_array($que)){
           if($i%2 == 0) $style="background-color:#eee;";
             else $style="background-color:#fff;";
              $id = $value['id'];?>
<div class="row-fluid" style="<?php echo $style; ?>">
			<div class="span2" style=""><?php echo $value['keyword'];?></div>
			<div class="span2" style=""><?php echo $value['date_'];?></div>
  	        <div class="span2" style=""><?php echo $value['message'];?></div>
            
          	<div class="span2" style=""><?php echo $value['url'];?></div>
          	<div class="span2" style=""><?php echo $value['mediaurl'];?></div>
          	<div class="span1" style="">
               <a href="view.php?cpg=<?php echo $id; ?>" class="" title="Edit" style="padding-right: 10px;"><i class="fa fa-pencil" style="color: green;"></i></a>
               <a href="delete.php?cpg=<?php echo $id; ?>"  onclick="return deleteContact()" title="Delete" style=""><i class="fa fa-trash"></i> </a>
            </div>
            
</div>
<?php	$i++; } ?>
</div>
<div class="shadows">
<?php
	if($pagination->total_pages() > 1) {
		if($pagination->has_previous_page()) {
        $url = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        $page = $pagination->previous_page(); ?>
    	<a href="<?php echo change_page($url,$page);?>">
        <?php 
        echo "&laquo; Previous</a> "; 
       }
		echo "...Page ".++$page."...";
		if($pagination->has_next_page()){ 
        $url = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        $page = $pagination->next_page(); ?>
	   <a href="<?php echo change_page($url,$page);?>">
		<?php 
		echo "Next &raquo;</a>"; 
            }		
    	}
?> 
<div style="float: right; margin-right: 0px; font-size: 0.8em; padding-right: 5px; padding-bottom: 15px"><b>RECORDS : <span style="color: black;"><?php echo $total_count;?></span></b></div>
</div>
<?php } else { echo "Nothing found!"; } 
  }
  else { echo "Nothing found!"; }
?>
</div> 
</div>
<?php include "footer.php";?>
</div>
</body>
<script type="text/javascript">
  function deleteContact(){
	var test = confirm("Are you sure you want to delete this template?");
        if(test == true){
         return true;
       }
      else {
        return false;
      }
}
</script>
</html>