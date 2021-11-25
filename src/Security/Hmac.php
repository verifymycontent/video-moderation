<?php namespace VerifyMyContent\VideoModeration\Security;

class Hmac {

    private $apiKey;

    private $apiSecret;

    function __construct($apiKey, $apiSecret)
    {
        $this->apiKey = $apiKey;
        $this->apiSecret = $apiSecret;
    }

    /**
     * Generate the HMAC signature based on input and API keys
     */
    public function generate($input)
    {
        $hash = hash_hmac('sha256', $input, $this->apiSecret);
        return "{$this->apiKey}:{$hash}";
    }

    /**
     * Validates that a generated HMAC
     */
    public function validate($hash, $input)
    {
        return $this->generate($input) === $this->removePrefix($hash);
    }

    private function removePrefix($header) 
    {
        return preg_replace('/^hmac ?/i', '', $header);
    }

}