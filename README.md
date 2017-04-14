PHP cUrl extension wrapper.

Add this to composer.json require section
```json
 "vis/curl_client_l5": "1.*"
```

Execute
```json
composer update
```

Usage
```php
$curl = New Vis/CurlClient/CurlClient();

//example of all possible methods
$curl->setRequestCredentials($login, $password, $authType) //$authType is optional
     ->setRequestHeader($option, $value) //$option accepts array ["option" => "value", "option1" => "value1"]
     ->setRequestCookie($option, $value) //$option accepts array ["option" => "value", "option1" => "value1"]
     ->setRequestReferrer($referrer)
     ->setRequestUserAgent($agent)
     ->setRequestMethod($method) // 'POST', 'GET', 'PUT', 'PATCH', 'DELETE'
     ->setRequestUrl($url,$urlParams) //$urlParam is optional if you need to add params to query string
     ->setRequestPayload($payload, $encoding) // $encoding is optional. Accepts either 'json' or 'query' and encodes payload to given format, otherwise doesn't encode.
     ->setCurlOpt($option, $value) //if you need to set any other additional curl options
     ->doCurlRequest() //returns response array ['http_code', 'response_header', 'response_body']
```

```php
     $curl->doCloseCurl(); //if you want to close curl right
```

