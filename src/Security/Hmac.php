<?php namespace VerifyMyContent\VideoModeration\Security;

class Hmac {

    private $apiKey;

    private $apiSecret;

    function __construct($apiKey, $apiSecret)
    {
        $this->apiKey = $apiKey;
        $this->apiSecret = $apiSecret;
    }

    public function generate($input)
    {
        $hash = hash_hmac('sha256', $input, $this->apiSecret);
        return "{$this->apiKey}:{$hash}";
    }


    public function validate($hash, $input)
    {
        return $this->generate($input) === $this->removePrefix($hash);
    }

    private function removePrefix($header) 
    {
        return preg_replace('/^hmac ?/i', '', $header);
    }

}