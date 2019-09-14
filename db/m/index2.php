<?php 
$your_html_message = '<!DOCTYPE html> 
<html> 
<head> 
<meta http-equiv="content-type" content="text/html; charset=UTF-8"> 
  <title>Hello World!</title> 
</head> 
<body> 
<p>Hello World!</p> 
</body> 
</html>'; 
// Require Pear Mail Packages 
require_once ("Mail.php"); 
require_once ("Mail/mime.php"); 
$recipients  = 'Krupnik <krupnik_a@ukr.net>'; 
// Additional headers 
$headers["From"] = 'Конкурус СНР <conkstudrabot@gmail.com>'; 
$headers["To"]    = 'Krupnik <krupnik_a@ukr.net>';  
$headers["Subject"] = "Testing"; 
$crlf = "\n"; 
$mime = new Mail_mime($crlf);
$mimeparams=array(); 
$mimeparams['html_charset']="UTF-8"; 
$mimeparams['head_charset']="UTF-8";  


$mime->setHTMLBody($your_html_message);
 
$message = $mime->get($mimeparams); 
$headers = $mime->headers($headers); 
$params["host"]    = 'ssl://smtp.gmail.com'; 
$params["auth"]    = TRUE; // note: there are *no delimiters* 
$params["port"]    = 465;  
$params["username"]    = 'conkstudrabot@gmail.com'; 
$params["password"]    = 'qstl965z'; 
$params["debug"]    = "True";  
// create the mail object using the Mail::factory method 
$mail_message =& Mail::factory('smtp', $params); 
$mail_message->send ($recipients, $headers, $message); 
?>