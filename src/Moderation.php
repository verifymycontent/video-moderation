<?php namespace VerifyMyContent\VideoModeration;

define('VMC_SDK_VERSION', '1.0.1');

class Moderation {

    private $hmac;

    private $http;

    private $apiVersion = "v1";

    function __construct($apiKey, $apiSecret)
    {
        $this->hmac = new Security\Hmac($apiKey, $apiSecret);
        $this->http = new Transports\Http("https://moderation.verifymycontent.com");
    }

    public function useSandbox()
    {
        $this->http->setBaseURL("https://moderation.sandbox.verifymycontent.com");
    }

    public function start($data)
    {
        Validators\StartModeration::validate($data);
        $json = json_encode($data);
        $hmac = $this->hmac->generate($json);
        return $this->http->post(
            "/api/{$this->apiVersion}/moderation",
            $json,
            [
                "Authorization: hmac {$hmac}"
            ]
        );
    }

    public function get($id)
    {
        $uri = "/api/{$this->apiVersion}/moderation/{$id}";
        $hmac = $this->hmac->generate($uri);
        return $this->http->get(
            $uri,
            [
                "Authorization: hmac {$hmac}"
            ]
        );
    }

    public function participants($id)
    {
        $uri = "/api/{$this->apiVersion}/moderation/{$id}/participants";
        $hmac = $this->hmac->generate($uri);
        return $this->http->get(
            $uri,
            [
                "Authorization: hmac {$hmac}"
            ]
        );
    }

    public function setBaseUrl($url)
    {
        $this->http->setBaseURL($url); 
    }

}