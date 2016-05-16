<?php
require_once "config.php";
require_once "functions.php";
require_once "yelp.php";
require_once "twitter_news.php";
date_default_timezone_set('America/New York');
$now= date('Y-m-d H:i:s',time());
$from = "+".trim(preg_replace("/[^0-9]/","",$_REQUEST['From']));
$message_body = urldecode($_REQUEST['Body']);
$date= date("Y-m-d",time());
if(!empty($message_body)){   
  $body = mysql_real_escape_string($message_body);
  $query = mysql_query("INSERT INTO inbox (date_,from_,message_) VALUES ('$now','$from','$body')");
  $keyword = strtolower(trim($message_body));

  $pieces = explode(" ", $keyword);

  $or = $pieces[1]; // or

  if (isset($or) && $or ==='or')
    $keyword = $or;

  switch ($keyword) {
      case 'weather today':
      case 'weather':
          $url = 'http://api.wunderground.com/api/00eca20ab95a7479/forecast/q/SC/Charleston.json';
          $content = file_get_contents($url);
          $json = json_decode($content, true);

          $chunk = $json['forecast']['txt_forecast'];
          $temp = $chunk['forecastday'][0];
          $message = $temp['fcttext'];

          header('Content-type: text/xml');
          echo '<?xml version="1.0" encoding="UTF-8"?>';
          echo '<Response>'; 
          echo '<Sms>'.'Today in Charleston: '.$message."\r\nText 'Weather tonight' for more!";
          echo '</Sms>';
          echo '</Response>';
          break;
      case 'weather tonight':
          $url = 'http://api.wunderground.com/api/00eca20ab95a7479/forecast/q/SC/Charleston.json';
          $content = file_get_contents($url);
          $json = json_decode($content, true);

          $chunk = $json['forecast']['txt_forecast'];
          $temp = $chunk['forecastday'][1];
          $message = $temp['fcttext'];                

          header('Content-type: text/xml');
          echo '<?xml version="1.0" encoding="UTF-8"?>';
          echo '<Response>'; 
          echo '<Sms>'.'Tonight in Charleston: '.$message."\r\nText 'Weather tomorrow' for more!";
          echo '</Sms>';
          echo '</Response>';
          break;
      case 'weather tomorrow':
          $url = 'http://api.wunderground.com/api/00eca20ab95a7479/forecast/q/SC/Charleston.json';
          $content = file_get_contents($url);
          $json = json_decode($content, true);

          $chunk = $json['forecast']['txt_forecast'];//['forecast']['txt_forecast'];
          $temp = $chunk['forecastday'][2];
          $message = $temp['fcttext'];
          
          header('Content-type: text/xml');
          echo '<?xml version="1.0" encoding="UTF-8"?>';
          echo '<Response>'; 
          echo '<Sms>'.'Tomorrow in Charleston: '.$message;
          echo '</Sms>';
          echo '</Response>';
          break;
      case 'sunrise':
          $url = 'http://api.wunderground.com/api/00eca20ab95a7479/astronomy/q/SC/Charleston.json';
          $content = file_get_contents($url);
          $json = json_decode($content, true);

          $tempArray = $json['moon_phase']['sunrise'];
          $message = implode(":", $tempArray);
          
          header('Content-type: text/xml');
          echo '<?xml version="1.0" encoding="UTF-8"?>';
          echo '<Response>'; 
          echo '<Sms>'.'Today sunrise in Charleston at '.$message. ' AM'."\r\nCare about the sunset? Text 'Sunset'";
          echo '</Sms>';
          echo '</Response>';
          break;
      case 'sunset':
          $url = 'http://api.wunderground.com/api/00eca20ab95a7479/astronomy/q/SC/Charleston.json';
          $content = file_get_contents($url);
          $json = json_decode($content, true);

          $tempArray = $json['moon_phase']['sunset'];
          $message = implode(":", $tempArray);

          $finalMsg = date("g:i a", strtotime($message));
          
          header('Content-type: text/xml');
          echo '<?xml version="1.0" encoding="UTF-8"?>';
          echo '<Response>'; 
          echo '<Sms>'.'Today sunset in Charleston at '.$finalMsg. '. Dont miss it!';
          echo '</Sms>';
          echo '</Response>';
          break;
      case 'hello':  
      case 'hi': 
        $preText = array("\r\ntext 'Events'\r\ntext 'Weather'\r\ntext 'Pizza'", "\r\ntext 'Breakfast'\r\ntext 'Gas'\r\ntext 'Weather tonight'", "\r\ntext 'Music'\r\ntext 'News'\r\ntext 'Sushi'");

        $message = 'Hi! Learn more about Charleston:'.$preText[array_rand($preText)]."\r\nMore popular queries: http://topcharleston.com";

        header('Content-type: text/xml');
          echo '<?xml version="1.0" encoding="UTF-8"?>';
          echo '<Response>'; 
          echo '<Sms>'.$message;
          echo '</Sms>';
          echo '</Response>';            
          break;
      case 'events':
      case 'music':
      case 'event':
        $ignoreDates = true;
        $keyword = 'events';
        $response = selectResponse($keyword,$date,$ignoreDates);

        $preText = array("Things to do this week:", "Top events this week:", "Something to do this week:");

        if(is_array($response)){
          $message = $response[0]['message']; 
          header('Content-type: text/xml');
            echo '<?xml version="1.0" encoding="UTF-8"?>';
            echo '<Response>'; 
            echo '<Sms>'.$preText[array_rand($preText)]."\r\n".$message."\r\nText 'Weekend' for weekend picks!";
            echo '</Sms>';
            echo '</Response>';
          } 
        break;
      case 'music2':

          $ignoreDates = false;
          $keyword = 'music';
          $response = selectResponse($keyword,$date,$ignoreDates);
          if(is_array($response)){
            $messageToday = 'Today: '.$response[0]['message'];          
          }

          $weekFromNow = date("Y-m-d", strtotime("+1 week"));
          $tomorrow = date("Y-m-d", strtotime("+1 day"));
          $url = 'https://api.blitzr.com/events/?country_code=US&date_end='.$tomorrow.'T04:00:00.000Z&date_start='.$now.'T04:00:00.000Z&key=ea890f9a05e8127b5723186fd606ad99&latitude=32.77657&limit=24&longitude=-79.93092&radius=20';

          $content = file_get_contents($url);
          $json = json_decode($content, true);
          $total = count($json);
         
          if ($total > 0)
          {
            $randomNr = rand(0, $total-1);
            $newArray = $json[$randomNr];
            $name = $newArray['name'];
            $dateStart = $newArray['date_start'];
            $dayOfWeek = date('l', strtotime( $dateStart));
            $newDateStart = date("M jS", strtotime($dateStart));
            $venue = $newArray['venue'];

            $messageTomorrow = 'Tomorrow: '.$name.' at '.$venue.'. ';

          }
         
          $url = 'http://api.blitzr.com/events/?key=ea890f9a05e8127b5723186fd606ad99&latitude=32.77657&longitude=-79.93092&radius=50&limit=50&date_end='.$weekFromNow.'&date_start='.$tomorrow;
          $content2 = file_get_contents($url);
          $json2 = json_decode($content2, true);
          $total2 = count($json2);
          if (isset($randomNr)){
            while( in_array( ($randomNr2 = rand(0,$total2-1)), array($randomNr) ) );
          }
          else{
            $randomNr2 = rand(0, $total2-1);
          }          

          $newArray2 = $json2[$randomNr2];
          $name2 = $newArray2['name'];
          $dateStart2 = $newArray2['date_start'];
          $dayOfWeek2 = date('l', strtotime( $dateStart2));
          $newDateStart2 = date("M jS", strtotime($dateStart2));
          $venue2 = $newArray2['venue'];

          $msg = '';

          if(is_array($response))
            $msg = $messageToday."\r\n";

          if ($total > 0)
            $msg = $msg.$messageTomorrow."\r\n";
          
          $messageThisWeek = 'Later this week: '.$name2.' at '.$venue2.' on '.$dayOfWeek2. ' ('.$newDateStart2.')';
          
          header('Content-type: text/xml');
          echo '<?xml version="1.0" encoding="UTF-8"?>';
          echo '<Response>'; 
          echo '<Sms>'.$msg.$messageThisWeek;
          echo '</Sms>';
          echo '</Response>';
          break;
      case 'dog friendly':
          $preText = array('We suggest', 'How about', 'Try', 'Try this place:', 'Check this place:', 'We recommend', 'You will love', 'Your puppy will love', "Your dog will love", 'You and your puppy will love');
          $ignoreDates = false;
          $keyword = "Dog friendly restaurants";
          $message = query_api($keyword, "Charleston, SC", $ignoreDates);            
          header('Content-type: text/xml');
          echo '<?xml version="1.0" encoding="UTF-8"?>';
          echo '<Response>'; 
          echo '<Sms>'.$preText[array_rand($preText)].' '.$message.'. They allows dogs :)';
          echo '</Sms>';
          echo '</Response>';
          break;
      case 'restaurant deals':
          $ignoreDates = true;
          $message = query_api($keyword, "Charleston, SC", $ignoreDates);            
          header('Content-type: text/xml');
          echo '<?xml version="1.0" encoding="UTF-8"?>';
          echo '<Response>'; 
          echo '<Sms>'.$message."\r\nText 'Deals' for more!";
          echo '</Sms>';
          echo '</Response>';
          break;
      case 'deals':
          $ignoreDates = true;
          $message = query_api($keyword, "Charleston, SC", $ignoreDates);            
          header('Content-type: text/xml');
          echo '<?xml version="1.0" encoding="UTF-8"?>';
          echo '<Response>'; 
          echo '<Sms>'.$message."\r\nText 'Restaurant deals' if you're hungry!";
          echo '</Sms>';
          echo '</Response>';
          break;
      case 'has':
      case 'need':
          //DO NOTHING
          break;
      case 'bridge':
      case 'bridge status':         
         $message = "Charleston's Ravenel Bridge is currently open!";            
          header('Content-type: text/xml');
          echo '<?xml version="1.0" encoding="UTF-8"?>';
          echo '<Response>'; 
          echo '<Sms>'.$message."\r\nText 'Traffic' before you get on the road!";
          echo '</Sms>';
          echo '</Response>';
          break;        
      case 'or':
        $tempArray = array('sushi','pizza', 'burger', 'italian', 'american', 'asian', 'bbq','burgers','steak','wings','chicken','chinese','cuban','delis','french','greek','indian','japanese','korean','mexican','salad','seafood','soup','thai','vegan', 'tacos', 'fish');

        $finalArray = array();

        if(isset($pieces[0]))
          $first = trim($pieces[0]);

        if(isset($pieces[2]))
          $second = trim($pieces[2]);

        if (in_array($first, $tempArray))
          array_push($finalArray, $first);
        
        if (in_array($second, $tempArray))
          array_push($finalArray, $second);

        $keyword = $finalArray[array_rand($finalArray)];

        $preText = array('We randomly decided for you:', 'We picked for you');

        $message = query_api($keyword, "Charleston, SC", false);            
        header('Content-type: text/xml');
        echo '<?xml version="1.0" encoding="UTF-8"?>';
        echo '<Response>'; 
        echo '<Sms>'.$preText[array_rand($preText)].' '.$message;
        echo '</Sms>';
        echo '</Response>';   

        break;
      case 'joke':
      case 'jokes':
      case 'chuck norris':
      case 'momma':
      case 'your momma':
        $nr = 1;//rand(0,1);
        if($nr > 0.5){
          $url = 'http://api.icndb.com/jokes/random';
          $content = file_get_contents($url);
          $json = json_decode($content, true);   
          $message = $json['value']['joke'];
        }
        else{
          $url = 'http://api.yomomma.info/';
          $content = file_get_contents($url);
          $json = json_decode($content, true); 
          $message = $json['joke'];
        }               
        header('Content-type: text/xml');
        echo '<?xml version="1.0" encoding="UTF-8"?>';
        echo '<Response>'; 
        echo '<Sms>'.$message."\r\nText 'Joke' for more!";
        echo '</Sms>';
        echo '</Response>';
        break;
      case 'news':
        $nrTweets = rand(0,1);

        switch ($nrTweets) {
            case 0:
                $tweets = user_tweets('CharlestonNews', 4);
                break;
            case 1:
                $tweets = user_tweets('ABCNews4', 4);
                break;
            default:
                $tweets = user_tweets('CharlestonNews', 4);
        }

        $json = json_decode($tweets, true);   
        $nr = rand(0,2);
        $temp_message = $json['tweets'][$nr]['text'];
        $temp_message = str_replace('pic.twitter.com'," Source: http://pic.twitter.com",$temp_message);
        $message = str_replace(' lang="en" data-aria-label-part="0">',"",$temp_message);

        header('Content-type: text/xml');
        echo '<?xml version="1.0" encoding="UTF-8"?>';
        echo '<Response>'; 
        echo '<Sms>'.$message;
        echo '</Sms>';
        echo '</Response>';
        break;
      case 'traffic':       
        $nrTweets = rand(0,1);

        switch ($nrTweets) {
            case 0:
                $tweets = user_tweets('CharlestonTraff', 3);
                break;
            case 1:
                $tweets = user_tweets('TotalTrafficCHS', 3);
                break;
            default:
                $tweets = user_tweets('CharlestonTraff', 3);
        }

        $json = json_decode($tweets, true);   
        $nr = rand(0,2);
        $temp_message = $json['tweets'][$nr]['text'];
        $temp_message = str_replace('pic.twitter.com'," Source: http://pic.twitter.com",$temp_message);
        $message = str_replace(' lang="en" data-aria-label-part="0">',"",$temp_message);

        header('Content-type: text/xml');
        echo '<?xml version="1.0" encoding="UTF-8"?>';
        echo '<Response>'; 
        echo '<Sms>'.$message."\r\nText 'Weather' before you drive!";
        echo '</Sms>';
        echo '</Response>';
        break;
      case 'gas':
          $ignoreDates = true;
          $response = selectResponse('gasR',$date,$ignoreDates);
          $response2 = selectResponse('gasP',$date,$ignoreDates);
          if(is_array($response)){
            $message = $response[0]['message'];
            $directions = $response[0]['url'];
            
            $message2 = $response2[0]['message'];
            $directions2 = $response2[0]['url'];

            $finalMessage = 'Cheapest gas in Charleston:'."\r\n".'Regular: '.$message.' Directions: '.$directions."\r\nPremium: ".$message2.' Directions: '.$directions2;

            header('Content-type: text/xml');
            echo '<?xml version="1.0" encoding="UTF-8"?>';
            echo '<Response>'; 
            echo '<Sms>'.$finalMessage."\r\nText 'Traffic' before you get on the road!";
            echo '</Sms>';
            echo '</Response>';

          } 

          break;
      case 'weekend':
          $ignoreDates = true;
          $response = selectResponse('weekendFri',$date,$ignoreDates);
          $response2 = selectResponse('weekendSat',$date,$ignoreDates);
          $response3 = selectResponse('weekendSun',$date,$ignoreDates);

          if(is_array($response))
            $friday = "\r\nFriday:"."\r\n".$response[0]['message']; 

          if(is_array($response2))
            $saturday = "\r\nSaturday:"."\r\n".$response2[0]['message']; 

          if(is_array($response3))
            $sunday = "\r\nSunday:"."\r\n".$response3[0]['message']; 
            

          $finalMessage = 'This weekend in Charleston:'.$friday.$saturday.$sunday;

          header('Content-type: text/xml');
          echo '<?xml version="1.0" encoding="UTF-8"?>';
          echo '<Response>'; 
          echo '<Sms>'.$finalMessage;
          echo '</Sms>';
          echo '</Response>';

          break;
      case 'fact':
      case 'facts':
          $ignoreDates = true;
          $response = selectResponse('fact',$date,$ignoreDates);
          if(is_array($response)){
            $message = $response[0]['message']." ".$response[0]['url'];
            $mms = $response[0]['mediaurl'];
            if(!empty($mms)){ //there is a media url
              header('Content-type: text/xml');
              echo '<?xml version="1.0" encoding="UTF-8"?>';
              echo '<Response>'; 
              echo '<Message>'.$message;
              echo '<Media>'.$mms;
              echo '</Media>';
              echo '</Message>';
              echo '</Response>';
            } 
            else{
              header('Content-type: text/xml');
              echo '<?xml version="1.0" encoding="UTF-8"?>';
              echo '<Response>'; 
              echo '<Sms>'.$message."\r\nText 'fact' for more!";
              echo '</Sms>';
              echo '</Response>';
            }  
          }
          break;
      default:
          $ignoreDates = false;
          $response = selectResponse($keyword,$date,$ignoreDates);
          if(is_array($response)){
            $message = $response[0]['message']." ".$response[0]['url'];
            $mms = $response[0]['mediaurl'];
            if(!empty($mms)){ //there is a media url
              header('Content-type: text/xml');
              echo '<?xml version="1.0" encoding="UTF-8"?>';
              echo '<Response>'; 
              echo '<Message>'.$message;
              echo '<Media>'.$mms;
              echo '</Media>';
              echo '</Message>';
              echo '</Response>';
            } 
            else{
              header('Content-type: text/xml');
              echo '<?xml version="1.0" encoding="UTF-8"?>';
              echo '<Response>'; 
              echo '<Sms>'.$message;
              echo '</Sms>';
              echo '</Response>';
            }  
          }
          else
          {           
            $preText = array('We suggest', 'How about', 'Try', 'Try this place:', 'Check this place:', 'We recommend', 'You will love', 'You will like');

            $message = query_api($keyword, "Charleston, SC", false);            
            header('Content-type: text/xml');
            echo '<?xml version="1.0" encoding="UTF-8"?>';
            echo '<Response>'; 
            echo '<Sms>'.$preText[array_rand($preText)].' '.$message;
            echo '</Sms>';
            echo '</Response>';            
          }
          break;
  }
}
     
?>