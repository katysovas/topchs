# sms-responder
sms autoresponder system using Twilio. Allows a user to receive a specified message on sending a particular keyword to a twilio number.
USAGE:create a database in your dbms ..give it any name
     import autorespnder.sql to migrate the tables
     modify config.php 
          $connect = mysql_connect('localhost', 'DATABASE USERNAME', 'DATABASE PASSWORD');
          mysql_select_db('DATABASE NAME', $connect) or die('Error:Database connection failed !');
     login into the system using the credentials in the users table
