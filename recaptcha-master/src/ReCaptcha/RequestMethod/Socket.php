<?php
namespace ReCaptcha\RequestMethod;
class Socket
{
    private $handle = null;
    public function fsockopen($hostname, $port = -1, &$errno = 0, &$errstr = '', $timeout = null)
    {
        $this->handle = fsockopen($hostname, $port, $errno, $errstr, (is_null($timeout) ? ini_get("default_socket_timeout") : $timeout));
        if ($this->handle != false && $errno === 0 && $errstr === '') {
            return $this->handle;
        }
        return false;
    }
    public function fwrite($string, $length = null)
    {
        return fwrite($this->handle, $string, (is_null($length) ? strlen($string) : $length));
    }
    public function fgets($length = null)
    {
        return fgets($this->handle, $length);
    }
    public function feof()
    {
        return feof($this->handle);
    }
    public function fclose()
    {
        return fclose($this->handle);
    }
}