<?php
namespace ReCaptcha;
class ReCaptcha
{
    const VERSION = 'php_1.1.2';
    private $secret;
    private $requestMethod;
    public function __construct($secret, RequestMethod $requestMethod = null)
    {
        if (empty($secret)) {
            throw new \RuntimeException('No secret provided');
        }
        if (!is_string($secret)) {
            throw new \RuntimeException('The provided secret must be a string');
        }
        $this->secret = $secret;
        if (!is_null($requestMethod)) {
            $this->requestMethod = $requestMethod;
        } else {
            $this->requestMethod = new RequestMethod\Post();
        }
    }
    public function verify($response, $remoteIp = null)
    {
        if (empty($response)) {
            $recaptchaResponse = new Response(false, array('missing-input-response'));
            return $recaptchaResponse;
        }
        $params = new RequestParameters($this->secret, $response, $remoteIp, self::VERSION);
        $rawResponse = $this->requestMethod->submit($params);
        return Response::fromJson($rawResponse);
    }
}