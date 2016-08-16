<?php 

namespace Curl;

use Curl\ResponseAnalyzer;
use Exception;

/**
 * Class CurlClient
 *
 * @category PHP
 * @package  Curl
 * @author   Arno <arnoliu@tencent.com>
 */
class CurlClient
{
    /**
     * @var resource The curl instance.
     */
    protected $curl;

    /**
     * @var string The curl exec response.
     */
    protected $curlResponse;

    /**
     * @var int The curl exec error number.
     */
    protected $curlErrorCode;

    /**
     * @var string The curl exec error information.
     */
    protected $curlErrorInfo;

    /**
     * Init curl instance.
     *
     * @param Curl|null $curl
     */
    public function __construct(Curl $curl = null)
    {
        $this->curl = $curl ? $curl : new Curl();
    }

    /**
     * Sends a request to the server and returns the response.
     *
     * @param string $url      The endpoint to send the request to.
     * @param string $method   The request method.
     * @param string $params   The params of the request.
     * @param array  $headers  The request headers.
     * @param int|30 $time_out The timeout in seconds for the request.
     *
     * @return ReponseAnalyzer response from the server.
     */
    public function send($url, $method, $params, array $headers, $time_out = 30)
    {
        $this->openConnection($url, $method, $params, $headers, $time_out);
        $this->sendCurl();

        // if ($this->curlErrorCode = $this->curl->errno()) {
        //     throw new Exception($this->curl->error(), $this->curlErrorCode);
        // }

        list($response_headers, $response_body, $http_status_code) = $this->analyzeResponse();
        $this->closeConnection();

        return new ResponseAnalyzer($response_headers, $response_body, $http_status_code);
    }

    /**
     * Open a curl connection.
     *
     * @param string $url      The endpoint to send the request to.
     * @param string $method   The request method.
     * @param string $params   The params of the request.
     * @param array  $headers  The request headers.
     * @param int    $time_out The timeout for the request.
     */
    public function openConnection($url, $method, $params, array $headers, $time_out)
    {
        $options = array(
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_HTTPHEADER => $this->compileHeaders($headers),
            CURLOPT_URL => $url,
            CURLOPT_CONNECTTIMEOUT => 120,
            CURLOPT_TIMEOUT => $time_out,
            CURLOPT_RETURNTRANSFER => true, // Follow 301
            CURLOPT_HEADER => true, // header processing
        );

        if ($method !== "GET") {
            $options[CURLOPT_POSTFIELDS] = http_build_query($params);
        }

        $this->curl->init();
        $this->curl->setoptArray($options);
    }

    /**
     * Close the curl connection.
     */
    public function closeConnection()
    {
        $this->curl->close();
    }

    /**
     * Send the request and get the response.
     */
    public function sendCurl()
    {
        $this->curlResponse = $this->curl->exec();
    }

    /**
     * Compile the headers into the curl format.
     *
     * @param array $headers The request headers.
     *
     * @return array
     */
    protected function compileHeaders(array $headers)
    {
        $return = array();

        foreach ($headers as $key => $value) {
            $return[] = $key . ': ' . $value;
        }

        return $return;
    }

    /**
     * Get the headers and the body.
     *
     * @return array
     */
    protected function analyzeResponse()
    {
        $parts = explode("\r\n\r\n", $this->curlResponse);

        $response_body = array_pop($parts);
        $response_headers = implode('', $parts);
        $http_status_code = $this->curl->getinfo(CURLINFO_HTTP_CODE); 

        return array(trim($response_headers), trim($response_body), $http_status_code);
    }

}

//end of script
