<?php
namespace ReCaptcha;
class Response
{
    private $success = false;
    private $errorCodes = array();
    public static function fromJson($json)
    {
        $responseData = json_decode($json, true);
        if (!$responseData) {
            return new Response(false, array('invalid-json'));
        }
        if (isset($responseData['success']) && $responseData['success'] == true) {
            return new Response(true);
        }
        if (isset($responseData['error-codes']) && is_array($responseData['error-codes'])) {
            return new Response(false, $responseData['error-codes']);
        }
        return new Response(false);
    }
    public function __construct($success, array $errorCodes = array())
    {
        $this->success = $success;
        $this->errorCodes = $errorCodes;
    }
    public function isSuccess()
    {
        return $this->success;
    }
    public function getErrorCodes()
    {
        return $this->errorCodes;
    }
}