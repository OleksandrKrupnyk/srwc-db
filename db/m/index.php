<?php
// Pear Mail Library
require_once "Mail.php";

$from = '<conkstudrabot@gmail.com>';
$to = '<elm@dstu.dp.ua>';
$subject = 'Testing mail system';
$body = "Запрошення на конференцію всеукраїнського конкурсу СНР \"Електротехніка та електромеханіка\"\n
		 Testing mail system.";

$headers = array(
    'From' => $from,
    'To' => $to,
    'Subject' => $subject
);

$smtp = Mail::factory('smtp', array(
        'host' => 'ssl://smtp.gmail.com',
        'port' => '465',
        'auth' => true,
        'username' => 'conkstudrabot@gmail.com',
        'password' => 'qstl965z'
    ));

$mail = $smtp->send($to, $headers, $body);

if (PEAR::isError($mail)) {
    echo('<p>' . $mail->getMessage() . '</p>');
} else {
    echo('<p>Message successfully sent!</p>');
}
?>