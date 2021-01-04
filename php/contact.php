<?php

//  CONFIGURE EVERYTHING HERE

// require ReCaptcha class
require('recaptcha-master/src/autoload.php');

// "innen érkezik" az email
$from = 'Céges email <demo@domain.com>';

// email cím, amire a form kimenete érkezik
$sendTo = 'Fedor Felicián <fedor.felician@gmail.com>';

// email tárgya
$subject = 'Új üzenet érkezett a weboldalról';

// form mezőnevek és fordításaik // array variable name => Text to appear in the email
$fields = array('name' => 'Név', 'email' => 'Email', 'tel' => 'Telefonszám', 'message' => 'Üzenet'); 

// oké üzenet
$okMessage = 'Üzenet elküldve. Köszönjük!';

// hibaüzenet
$errorMessage = 'Hiba az üzenet küldésében, kérjük próbálja újra később.';

// ReCaptch Secret
$recaptchaSecret = '6LeNZPcZAAAAAJ34Q_GubW0CrNHbTCQGbvNw8lve';

//  LET'S DO THE SENDING

 // if you are not debugging and don't need error reporting, turn this off by error_reporting(0);
//error_reporting(E_ALL & ~E_NOTICE);
error_reporting(0);

try
{
    //if(count($_POST) == 0) throw new \Exception('Form is empty');
    // validate the ReCaptcha, if something is wrong, we throw an Exception,
        // i.e. code stops executing and goes to catch() block
        
    if (!isset($_POST['g-recaptcha-response'])) {
        throw new \Exception('ReCaptcha is not set.');
    }

    // do not forget to enter your secret key from https://www.google.com/recaptcha/admin
    $recaptcha = new \ReCaptcha\ReCaptcha($recaptchaSecret, new \ReCaptcha\RequestMethod\CurlPost());

    // we validate the ReCaptcha field together with the user's IP address
    $response = $recaptcha->verify($_POST['g-recaptcha-response'], $_SERVER['REMOTE_ADDR']);

    if (!$response->isSuccess()) {
        throw new \Exception('ReCaptcha was not validated.');
    }
   
    $emailText = "Új üzenet érkezett a weboldal kapcsolat menüpontján keresztül\n=============================\n";

    foreach ($_POST as $key => $value) {
        // If the field exists in the $fields array, include it in the email 
        if (isset($fields[$key])) {
            $emailText .= "$fields[$key]: $value\n";
        }
    }

    // All the neccessary headers for the email.
    $headers = array('Content-Type: text/plain; charset="UTF-8";',
        'From: ' . $from,
        'Reply-To: ' . $from,
        'Return-Path: ' . $from,
    );
    
    // Send email
    mail($sendTo, $subject, $emailText, implode("\n", $headers));
    $responseArray = array('type' => 'success', 'message' => $okMessage);
}
catch (\Exception $e)
{
    $responseArray = array('type' => 'danger', 'message' => $errorMessage);
}

// if requested by AJAX request return JSON response
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    $encoded = json_encode($responseArray);
    header('Content-Type: application/json');
    echo $encoded;
}
// else just display the message
else {
    echo $responseArray['message'];
}
