<?php
// require ReCaptcha class
require('php/autoload.php');

// configure
// an email address that will be in the From field of the email.
$from = 'Céges email <demo@domain.com>';

// an email address that will receive the email with the output of the form
$sendTo = 'Fedor Felicián <fedor.felician@gmail.com>';

// subject of the email
$subject = 'Új üzenet a kapcsolat menüponton keresztül';

// form field names and their translations.
// array variable name => Text to appear in the email
$fields = array('name' => 'Név', 'phone' => 'Telefonszám', 'email' => 'Email', 'message' => 'Üzenet');

// message that will be displayed when everything is OK :)
$okMessage = 'Az üzenet sikeresen elküldve.';

// If something goes wrong, we will display this message.
$errorMessage = 'Hiba történt az üzenet küldése közben. Kérem, próbálja újra később.';

// ReCaptch Secret
$recaptchaSecret = '6LeNZPcZAAAAAJ34Q_GubW0CrNHbTCQGbvNw8lve';

// let's do the sending

// if you are not debugging and don't need error reporting, turn this off by error_reporting(0);
error_reporting(E_ALL & ~E_NOTICE);

try {
    if (!empty($_POST)) {

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
        
        // everything went well, we can compose the message, as usually
        
        $emailText = "Új üzenet érkezett a kapcsolat menüponton keresztül\n=============================\n";

        foreach ($_POST as $key => $value) {
            // If the field exists in the $fields array, include it in the email
            if (isset($fields[$key])) {
                $emailText .= "$fields[$key]: $value\n";
            }
        }
    
        // All the neccessary headers for the email.
        $headers = array('Content-Type: text/plain; charset="UTF-8";',
            'Feladó: ' . $from,
            'Válasz: ' . $from,
            'Return-Path: ' . $from,
        );
        
        // Send email
        mail($sendTo, $subject, $emailText, implode("\n", $headers));

        $responseArray = array('type' => 'success', 'message' => $okMessage);
    }
} catch (\Exception $e) {
    $responseArray = array('type' => 'danger', 'message' => $e->getMessage());
}

if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    $encoded = json_encode($responseArray);

    header('Content-Type: application/json');

    echo $encoded;
} else {
    echo $responseArray['message'];
}
