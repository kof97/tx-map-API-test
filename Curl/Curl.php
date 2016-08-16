<?php 

namespace Curl;

use Exception;

/**
 * Class Curl
 *
 * @category PHP
 * @package  Curl
 * @author   Arno <arnoliu@tencent.com>
 */
class Curl
{
    /**
     * @var Curl instance.
     */
    protected $curl;

    /**
     * Check the curl extension.
     */
    public function __construct()
    {
        if (!extension_loaded('curl')) {
            throw new Exception('The cURL extension must be loaded to use the "curl".');
        }
    }

    /**
     * Init a new curl instance.
     */
    public function init()
    {
        $this->curl = curl_init();
    }

    /**
     * Set a curl option.
     *
     * @param string $key
     * @param string $value
     */
    public function setopt($key, $value)
    {
        curl_setopt($this->curl, $key, $value);
    }

    /**
     * Set an array of options.
     *
     * @param array $options
     */
    public function setoptArray(array $options)
    {
        curl_setopt_array($this->curl, $options);
    }

    /**
     * Send a curl request.
     *
     * @return mixed
     */
    public function exec()
    {
        return curl_exec($this->curl);
    }

    /**
     * Return the curl error number.
     *
     * @return int
     */
    public function errno()
    {
        return curl_errno($this->curl);
    }

    /**
     * Return the curl error message.
     *
     * @return string
     */
    public function error()
    {
        return curl_error($this->curl);
    }

    /**
     * Get info.
     *
     * @param $type
     *
     * @return mixed
     */
    public function getinfo($type)
    {
        return curl_getinfo($this->curl, $type);
    }

    /**
     * Get the curl version.
     *
     * @return array
     */
    public function version()
    {
        return curl_version();
    }

    /**
     * Close the curl connection.
     */
    public function close()
    {
        curl_close($this->curl);
    }

}

//end of script
