<?php
class ShortUrl{
    public function urlToShortCode($url) {
        if(empty($url)) {
            throw new Exception("No URL was supplied.");
        }
        $shortCode = $this->numToAlpha($url);
         return $shortCode;
    }
    protected function validateUrlFormat($url) {
        return filter_var($url, FILTER_VALIDATE_URL,
            FILTER_FLAG_HOST_REQUIRED);
    }
    protected function numToAlpha($num){
      $alpha = "abcdefghijklmnopqrstuvwxyz+-_";
      $return = $this->randomString();      
      $length = strlen($alpha);
      if($num > $length){
      while ($num > $length - 1){
        $return .= $alpha[$num % $length].rand(10,23);
        $num = floor($num/$length);
        }
    }
    else{
       $return .= $alpha[$length%$num].rand(10,23);  
    }          
     return $return;
   }
  protected function randomString($length = 3) {
	$str = "";
	$characters = array_merge(range('a','z'));
	$max = count($characters) - 1;
	for ($i = 0; $i < $length; $i++) {
		$rand = mt_rand(0, $max);
		$str .= $characters[$rand];
	}
	return $str;
}
}
?>