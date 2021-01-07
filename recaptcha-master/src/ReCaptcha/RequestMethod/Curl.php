<?php
namespace ReCaptcha\RequestMethod;
class Curl
{
    public function init($url = null)
    {
        return curl_init($url);
    }
    public function setoptArray($ch, array $options)
    {
        return curl_setopt_array($ch, $options);
    }
    public function exec($ch)
    {
        return curl_exec($ch);
    }
    public function close($ch)
    {
        curl_close($ch);
    }
}