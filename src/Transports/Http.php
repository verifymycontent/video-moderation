<?php namespace VerifyMyContent\VideoModeration\Transports;

class Http {
    
    private $baseURL;

    function __construct($baseURL)
    {
        $this->baseURL = $baseURL;
    }

    public function setBaseURL($baseURL)
    {
        $this->baseURL = $baseURL;
    }

    public function post($uri, $body, $headers) 
    {
        return $this->request("POST", $uri, $body, $headers);
    }

    public function get($uri, $headers) 
    {
        return $this->request("GET", $uri, null, $headers);
    }

    private function request($method, $uri, $data, $headers) {
        $ch = curl_init($this->baseURL . $uri);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        $header = array_merge([
          'Content-Type: application/json',
          'User-Agent: VMC/' . VMC_SDK_VERSION
        ], $headers);
        if ($method != 'GET') {
          $json = json_encode($data);
          curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
          $header[] = 'Content-Length: ' . strlen($json);
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        $result = curl_exec($ch);
        $err = curl_error($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if ($err) {
          return $err;
        } else {
          if ($result) 
          {
            return json_decode($result, true);
          }
          return $httpcode === 204;
        }
      }

}