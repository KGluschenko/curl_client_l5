PHP cUrl extension wrapper.

В composer.json добавляем в блок require
```json
 "vis/curl-client_l5": "1.*"
```

Выполняем
```json
composer update
```

Использование
```php
$curl = New CurlClient();

//example of all possible methods
$curl->setRequestCredentials($login, $password, $authType) //$authType is optional
     ->setRequestHeader($option, $value) //also accepts array ["option" => "value", "option1" => "value1"]
     ->setRequestCookie($option, $value) //also accepts array ["option" => "value", "option1" => "value1"]
     ->setRequestReferrer($referrer)
     ->setRequestUserAgent($agent)
     ->setRequestMethod($method)
     ->setRequestUrl($url,$urlParams) //$urlParam is optional if you need to add params to query string
     ->setRequestPayload($payload, $encoding) // $encoding is optional. Accepts either 'json' or 'query' and encodes payload to given format, otherwise doesn't encode.
     ->setCurlOpt($option, $value) //if you need to set any other additional curl options
     ->doCurlRequest() //returns response array ['http_code', 'response_header', 'response_body']
     ->doCloseCurl(); //if you want to close curl right after execution.
```