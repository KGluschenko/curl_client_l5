<?php namespace Vis\CurlClient;

class CurlClient
{
    private $curl;
    private $curlResponse = [];

    public function __construct()
    {
        $this->doInitCurl();
    }

    public function __destruct()
    {
        $this->doCloseCurl();
    }

    /**
     * @param mixed $curl
     */
    private function setCurl($curl)
    {
        $this->curl = $curl;
    }

    /**
     * @return mixed
     */
    private function getCurl()
    {
        return $this->curl;
    }

    /**
     * @return mixed
     */
    public function getCurlResponse()
    {
        return $this->curlResponse;
    }

    /**
     * @param mixed $curlResponse
     */
    private function setCurlResponse($curlResponse)
    {
        $this->curlResponse = $curlResponse;
    }

    public function setCurlOpt($option, $value)
    {
        curl_setopt($this->getCurl(), $option, $value);
        return $this;
    }

    /**
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
     * @param mixed $referrer
     * @return CurlClient
     */
    public function setRequestReferrer($referrer)
    {
        return $this->setCurlOpt(CURLOPT_REFERER, $referrer);
    }

    /**
     * @param mixed $agent
     * @return CurlClient
     */
    public function setRequestUserAgent($agent)
    {
        return $this->setCurlOpt(CURLOPT_USERAGENT, $agent);
    }

    /**
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

    private function doInitCurl()
    {
        $this->setCurl(curl_init());
        $this->setCurlOpt(CURLOPT_HEADER, 1);
        $this->setCurlOpt(CURLINFO_HEADER_OUT, 1);
        $this->setCurlOpt(CURLOPT_RETURNTRANSFER, 1);
    }

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


        return $this->getCurlResponse();
    }

    public function doCloseCurl()
    {
        curl_close($this->getCurl());
    }

}