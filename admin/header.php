 <h5 style="padding-left: 5px;"><i class="fa fa-upload"> </i> <a href="uploadcontent.php">Upload Template</a></h5>
 <div class="accordion-content" style="height: 50px;">
           <h6 style="padding-left: 5px;"><i class="fa fa-envelope"> </i> <a href="templates.php?filter=null&page=1">View Templates</a></h6>
        </div>  
 <h5 style="padding-left: 5px;"><i class="fa fa-upload"> </i> <a href="templates.php?filter=gas&new_value=&filtering=Filter">Upload Gas</a></h5>
        <div class="accordion-content" style="height: 50px;">
           <h6 style="padding-left: 5px;"><a target="_blank" href="http://gasprices.mapquest.com/station/us/sc/charleston/29403" style="color: #D1D0CE;">Gas Prices</a></h6>
        </div>  

<h5 style="padding-left: 5px;"><i class="fa fa-upload"> </i> <a href="templates.php?filter=weekend&new_value=&filtering=Filter">Upload Weekend</a></h5>
       

       
        
    
     <h6 style="padding-left: 5px;"><a href="logout.php" style="color: #D1D0CE;">Logout</a></h6>

     <h6 style="padding-left: 5px;">Top Queries:</h6>

<?php
require_once "config.php";
require_once "functions.php";

     $sql="SELECT message_, COUNT(*) AS msg 
			FROM inbox 
			GROUP BY message_ 
			ORDER BY msg DESC
			LIMIT 3";

		$result = mysql_query($sql);
		while($row = mysql_fetch_array($result)) {
		echo '<p>'.$row['message_'].'</p>';
}
       ?>

       <h6 style="padding-left: 5px;">Most Recent:</h6>

     <?php

     $sql="SELECT message_, date_
			FROM inbox 
			ORDER BY date_ DESC
			LIMIT 3";

		$result = mysql_query($sql);
		while($row = mysql_fetch_array($result)) {
		echo '<p> '.$row['message_'].' '.$row['date_'].'</p>';
}
       ?>