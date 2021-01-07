<?php
namespace ReCaptcha\RequestMethod;
use ReCaptcha\RequestMethod;
use ReCaptcha\RequestParameters;
class CurlPost implements RequestMethod
{
    const SITE_VERIFY_URL = 'https://www.google.com/recaptcha/api/siteverify';
    private $curl;
    public function __construct(Curl $curl = null)
    {
        if (!is_null($curl)) {
            $this->curl = $curl;
        } else {
            $this->curl = new Curl();
        }
    }
    public function submit(RequestParameters $params)
    {
        $handle = $this->curl->init(self::SITE_VERIFY_URL);
        $options = array(
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $params->toQueryString(),
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/x-www-form-urlencoded'
            ),
            CURLINFO_HEADER_OUT => false,
            CURLOPT_HEADER => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => true
        );
        $this->curl->setoptArray($handle, $options);
        $response = $this->curl->exec($handle);
        $this->curl->close($handle);
        return $response;
    }
}