<?php
  class SimpleMail{
      private $toAddress;
      private $CCAddress;
      private $BCCAddress;
      private $fromAddress;
      private $subject;
      private $sendText;
      private $textBody;
      private $sendHTML;
      private $HTMLBody;
  public function __construct(){
       $this-> toAddress = "";
       $this-> CCAddress = "";
       $this-> BCCAddress = "";
       $this-> fromAddress = "";
       $this-> subject = "";
       $this-> sendText = true;
       $this-> textBody = "";
       $this-> sendHTML = false;
       $this-> HTMLBody = "";
   }
   public function setToAddress($value){
        $this-> toAddress = $value;
    }
   public function setCCAddress($value){
        $this-> CCAddress = $value;
    }
   public function setBCCAddress($value){
        $this-> BCCAddress = $value;
     }
   public function setFromAddress($value){
        $this-> fromAddress = $value;
      }
   public function setSubject($value){
     $this-> subject = $value;
     }
    public function setSendText($value){
      $this-> sendText = $value;
     }
    public function setTextBody($value){
        $this-> sendText = true;
        $this-> textBody = $value;
        }
    public function setSendHTML($value){
        $this-> sendHTML = $value;
        }
    public function setHTMLBody($value){
       $this-> sendHTML = true;
       $this-> HTMLBody = $value;
      }
    public function send($to = null, $subject = null, $message = null,$headers = null){
            $success = false;
         if (!is_null($to)&&!is_null($subject)&&!is_null($message)){
             $success = mail($to, $subject, $message, $headers);
             return $success;
           } 
         else{
            $headers = array();
           if(!empty($this-> fromAddress)){
            $headers[] = 'From:'. $this-> fromAddress;
             }
           if (!empty($this-> CCAddress)){
            $headers[] = 'CC:'. $this-> CCAddress;
             }
           if (!empty($this-> BCCAddress)){
            $headers[] = 'BCC:'.$this-> BCCAddress;
             }
           if ($this-> sendText&&!$this-> sendHTML){
            $message = $this-> textBody;
          } elseif (!$this-> sendText&& $this-> sendHTML){
             $headers[] = 'MIME-Version: 1.0';
             $headers[] = 'Content-type: text/html; charset="iso-8859-1"';
             $headers[] = 'Content-Transfer-Encoding: 7bit';
             $message = $this-> HTMLBody;
          } elseif ($this-> sendText && $this-> sendHTML){
             $boundary = '==MP_Bound_xyccr948x==';
             $headers[] = 'MIME-Version: 1.0';
             $headers[] = 'Content-type: multipart/alternative;
             boundary="'.$boundary .'"';
            $message = 'This is a Multipart Message in MIME format.'. "\n";
            $message .= '--' . $boundary ."\n";
            $message .= 'Content-type: text/plain; charset="iso-8859-1"'."\n";
            $message .= 'Content-Transfer-Encoding: 7bit' ."\n\n";
            $message .= $this-> textBody ."\n";
            $message .= '--' . $boundary ."\n";
            $message .= 'Content-type: text/html; charset="iso-8859-1"'."\n";
            $message .= 'Content-Transfer-Encoding: 7bit' ."\n\n";
            $message .= $this-> HTMLBody ."\n";
            $message .= '--' . $boundary .'--';
                  }
       $success = mail($this-> toAddress, $this-> subject, $message,join("\r\n", $headers));
       return $success;
                  }
              }
          }
?>