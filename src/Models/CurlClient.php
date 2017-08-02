<?php namespace Vis\CurlClient;

/** PHP cUrl extension wrapper
 * Class CurlClient
 * @package Vis\CurlClient
 */
class CurlClient
{

    private $curl;
    private $curlResponse = [
        "http_code"         => 0,
        "response_header"   => "",
        "response_body"     => ""
    ];


    /**
     * CurlClient constructor.
     * Calls doInitCurl method
     */
    public function __construct()
    {
        $this->doInitCurl();
    }

    /**
     * CurlClient destructor.
     * Calls doCloseCurl method
     */
    public function __destruct()
    {
        $this->doCloseCurl();
    }

    /**
     * Sets curl request property
     * @param mixed $curl
     * @return CurlClient
     */
    private function setCurl($curl)
    {
        $this->curl = $curl;
        return $this;
    }

    /**
     * Gets curl request property
     * @return mixed
     */
    private function getCurl()
    {
        return $this->curl;
    }

    /**
     * Fetches only http_code from curl request response property
     * @param mixed $curlResponse
     */
    private function setCurlResponse($curlResponse)
    {
        $this->curlResponse = $curlResponse;
    }

    /**
     * Gets curl request response property
     * @return mixed
     */
    public function getCurlResponse()
    {
        return $this->curlResponse;
    }

    /**
     * Fetches only http_code from curl request response property
     * @return mixed
     */
    public function getCurlResponseHttpCode()
    {
        return $this->getCurlResponse()['http_code'];
    }

    /**
     * Fetches only response_header from curl request response property
     * @return mixed
     */
    public function getCurlResponseHeader()
    {
        return $this->getCurlResponse()['response_header'];
    }

    /**
     * Fetches only response_body from curl request response property
     * @return mixed
     */
    public function getCurlResponseBody()
    {
        return $this->getCurlResponse()['response_body'];
    }

    /**
     * Setups curl request option
     * @param $option
     * @param $value
     * @return $this
     */
    public function setCurlOpt($option, $value)
    {
        curl_setopt($this->getCurl(), $option, $value);
        return $this;
    }

    /**
     * Setups request headers
     * @param mixed $option
     * @param mixed $value
     * @return CurlClient
     */
    public function setRequestHeader($option, $value = null)
    {
        if (is_array($option)) {
            foreach ($option as $name => $val) {
                $this->setCurlOpt(CURLOPT_HTTPHEADER, [$name . ": " . $val]);
            }
            return $this;
        }

        return $this->setCurlOpt(CURLOPT_HTTPHEADER, [$option . ": " . $value]);
    }

    /**
     * Setups request credentials
     * @param mixed $login
     * @param mixed $password
     * @param mixed $type
     * @return CurlClient
     */
    public function setRequestCredentials($login, $password, $type = CURLAUTH_BASIC)
    {
        return $this->setCurlOpt(CURLOPT_HTTPAUTH, $type)
                    ->setCurlOpt(CURLOPT_USERPWD, $login . ":" . $password);
    }

    /**
     * Setups request referrer
     * @param mixed $referrer
     * @return CurlClient
     */
    public function setRequestReferrer($referrer)
    {
        return $this->setCurlOpt(CURLOPT_REFERER, $referrer);
    }

    /**
     * Setups request user agent
     * @param mixed $agent
     * @return CurlClient
     */
    public function setRequestUserAgent($agent)
    {
        return $this->setCurlOpt(CURLOPT_USERAGENT, $agent);
    }

    /**
     * Setups request method
     * @param mixed $method
     * @throws \Exception
     * @return CurlClient
     */
    public function setRequestMethod($method)
    {
        switch ($method) {
            case 'POST':
                $this->setCurlOpt(CURLOPT_POST, 1);
                break;
            case 'GET':
                $this->setCurlOpt(CURLOPT_HTTPGET, 1);
                break;
            case 'PUT':
                $this->setCurlOpt(CURLOPT_CUSTOMREQUEST, 'PUT');
                break;
            case 'PATCH':
                $this->setCurlOpt(CURLOPT_CUSTOMREQUEST, 'PATCH');
                break;
            case 'DELETE':
                $this->setCurlOpt(CURLOPT_CUSTOMREQUEST, 'DELETE');
                break;
            default:
                throw new \Exception('Undefined request method');
        }

        return $this;
    }

    /**
     * Setups curl request URL
     * @param mixed $requestUrl
     * @param array $urlParams
     * @return CurlClient
     */
    public function setRequestUrl($requestUrl, $urlParams = [])
    {
        if (!empty($urlParams)) {
            $requestUrl .= '?' . http_build_query($urlParams);
        }

        $this->setCurlOpt(CURLOPT_URL, $requestUrl);

        return $this;
    }

    /**
     * Setups additional request payload
     * @param mixed $requestPayload
     * @param
     * @return CurlClient
     */
    public function setRequestPayload($requestPayload = [], $encode = null)
    {
        if(!empty($requestPayload)){
            switch ($encode) {
                case 'json':
                    $requestPayload = json_encode($requestPayload);
                    break;
                case 'query':
                    $requestPayload = http_build_query($requestPayload);
                    break;
                default:
                    break;
            }

            $this->setCurlOpt(CURLOPT_POSTFIELDS, $requestPayload);
        }

        return $this;
    }

    /**
     * Add Cookies to Curl request
     * @param mixed $option
     * @param mixed $value
     * @return CurlClient
     */
    public function setRequestCookie($option, $value = null)
    {
        if (!is_array($option)) {
            $option = [$option => $value];
        }

        return $this->setCurlOpt(CURLOPT_COOKIE, http_build_query($option, '', '; '));
    }

    /**
     * Default setup for curl
     */
    private function doInitCurl()
    {
        $this->setCurl(curl_init())
            ->setCurlOpt(CURLOPT_HEADER, 1)
            ->setCurlOpt(CURLINFO_HEADER_OUT, 1)
            ->setCurlOpt(CURLOPT_RETURNTRANSFER, 1);
    }

    /**
     * Doing CurlRequest and setting CurlResponse
     * @return CurlClient
     */
    public function doCurlRequest()
    {
        $response    = curl_exec($this->getCurl());
        $httpCode    = curl_getinfo($this->getCurl(), CURLINFO_HTTP_CODE);
        $headerSize  = curl_getinfo($this->getCurl(), CURLINFO_HEADER_SIZE);

        $response_header = substr($response, 0, $headerSize);
        $response_body   = substr($response, $headerSize);

        $this->setCurlResponse([
            "http_code"         => $httpCode,
            "response_header"   => $response_header,
            "response_body"     => $response_body,
        ]);

        return $this;
    }

    /**
     * If CurlRequest has successful http_code
     * @return bool
     */
    public function isSuccessful()
    {
        return $this->getCurlResponseHttpCode() >= 200 && $this->getCurlResponseHttpCode() < 300;
    }

    /**
     * Destroying curl resource if it's still up
     */
    public function doCloseCurl()
    {
        if (gettype($this->getCurl()) == 'resource') {
            curl_close($this->getCurl());
        }
    }

}
