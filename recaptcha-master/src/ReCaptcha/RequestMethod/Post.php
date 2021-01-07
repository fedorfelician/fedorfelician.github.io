<?php
namespace ReCaptcha\RequestMethod;
use ReCaptcha\RequestMethod;
use ReCaptcha\RequestParameters;
class Post implements RequestMethod
{
    const SITE_VERIFY_URL = 'https://www.google.com/recaptcha/api/siteverify';
    public function submit(RequestParameters $params)
    {
        $peer_key = version_compare(PHP_VERSION, '5.6.0', '<') ? 'CN_name' : 'peer_name';
        $options = array(
            'http' => array(
                'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                'method' => 'POST',
                'content' => $params->toQueryString(),
                // Force the peer to validate (not needed in 5.6.0+, but still works)
                'verify_peer' => true,
                // Force the peer validation to use www.google.com
                $peer_key => 'www.google.com',
            ),
        );
        $context = stream_context_create($options);
        return file_get_contents(self::SITE_VERIFY_URL, false, $context);
    }
}