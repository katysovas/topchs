<?php

$url = 'http://api.yomomma.info/';
          $content = file_get_contents($url);
          $json = json_decode($content, true); 


	var_dump($json);  
?>