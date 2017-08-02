PHP cUrl extension wrapper.

Execute
```json
composer require "vis/curl_client_l5":"1.*"
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
```

To execute curl request
```php
$curl->doCurlRequest() //returns self
```

After that you can get request response with following methods
```php
$curl->getCurlResponse();  //returns array ['http_code', 'response_header', 'response_body']

$curl->getCurlResponseHttpCode(); //returns http_code
$curl->getCurlResponseHeader(); //returns response_header
$curl->getCurlResponseBody(); //returns response_body
```

Also you can use helper method isSuccessful to check if response http code was in range from 200 to 300
```php
$curl->isSuccessful();
```

If you wish to close curl resource earlier (this method is also executed upon destruction of object)
```php
 $curl->doCloseCurl();
```
