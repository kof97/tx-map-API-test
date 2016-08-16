<?php 

namespace Curl;

/**
 * Class ResponseAnalyzer
 *
 * @category PHP
 * @package  Curl
 * @author   Arno <arnoliu@tencent.com>
 */
class ResponseAnalyzer
{
    /**
     * @var array The response headers.
     */
    protected $headers;

    /**
     * @var string The response body.
     */
    protected $body;

    /**
     * @var int The HTTP status code.
     */
    protected $httpStatusCode;

    /**
     * @param string|array $response_headers The response headers.
     * @param string       $response_body    The response body.
     * @param int|null     $http_status_code The HTTP response code.
     */
    public function __construct($response_headers, $response_body, $http_status_code = null)
    {
        if (is_numeric($http_status_code)) {
            $this->httpStatusCode = (int)$http_status_code;
        }

        $this->setHeaders($response_headers);

        $this->body = $response_body;
    }

    /**
     * Return the response headers.
     *
     * @return array
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * Return the response body.
     *
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Return the HTTP response code.
     *
     * @return int
     */
    public function getHttpResponseCode()
    {
        return $this->httpStatusCode;
    }

    /**
     * Parse the response headers.
     *
     * @param string $response_headers The response headers.
     */
    protected function setHeaders($response_headers)
    {
        if (is_array($response_headers)) {
            $this->headers = $response_headers;

            return;
        }

        $header_collection = explode("\r\n", trim($response_headers));

        foreach ($header_collection as $line) {
            if (strpos($line, ': ') !== false) {
                list($key, $value) = explode(': ', $line);
                $this->headers[$key] = $value;
            }
        }
    }

}

//end of script
