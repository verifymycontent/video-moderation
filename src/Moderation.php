<?php namespace VerifyMyContent\VideoModeration;

define('VMC_SDK_VERSION', '1.0.0');

class Moderation {

    private $hmac;

    private $http;

    private $apiVersion = "v1";

    function __construct($apiKey, $apiSecret)
    {
        $this->hmac = new Security\Hmac($apiKey, $apiSecret);
        $this->http = new Transports\Http("https://moderations.verifymycontent.com");
    }

    public function useSandbox()
    {
        $this->http->setBaseURL("https://moderations.sandbox.verifymycontent.com");
    }

    public function start($data)
    {
        Validators\StartModeration::validate($data);

        $hmac = $this->hmac->generate($data['video']['url']);
        return $this->http->post(
            "/api/{$this->apiVersion}/moderation",
            $data,
            [
                "Authorization: hmac {$hmac}"
            ]
        );
    }

    public function get($id)
    {
        $hmac = $this->hmac->generate($id);
        return $this->http->get(
            "/api/{$this->apiVersion}/moderation/{$id}",
            [
                "Authorization: hmac {$hmac}"
            ]
        );
    }

    public function participants($id)
    {
        $hmac = $this->hmac->generate($id);
        return $this->http->get(
            "/api/{$this->apiVersion}/moderation/{$id}/participants",
            [
                "Authorization: hmac {$hmac}"
            ]
        );
    }

}