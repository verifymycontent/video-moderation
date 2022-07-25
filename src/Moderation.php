<?php namespace VerifyMyContent\VideoModeration;

define('VMC_SDK_VERSION', '3.0.0');

const VALID_API_VERSIONS = ['v1', 'v2'];
const DEFAULT_API_VERSION = 'v2';

class Moderation {
    private $hmac;

    private $http;

    /**
     * @var string
     * VMC Moderation API version. Valid values: v1, v2
     */
    private $apiVersion;

    function __construct($apiKey, $apiSecret, $apiVersion = DEFAULT_API_VERSION)
    {
        $this->hmac = new Security\Hmac($apiKey, $apiSecret);
        $this->http = new Transports\Http("https://moderation.verifymycontent.com");
        $this->apiVersion = in_array($apiVersion, VALID_API_VERSIONS) ? $apiVersion : DEFAULT_API_VERSION;
    }

    /**
     * Use the sandbox environment instead of the production one
     */
    public function useSandbox()
    {
        $this->http->setBaseURL("https://moderation.sandbox.verifymycontent.com");
    }

    /**
     * Start a new video moderation
     *
     * https://docs.verifymyage.com/docs/content/moderation/index.html
     */
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

    /**
     * Get a moderation by ID
     *
     * https://docs.verifymyage.com/docs/content/moderation/index.html
     */
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

    /**
     * Retrive uploader data
     */
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

    /**
     * Create livestream
     */
    public function createLivestream($data)
    {
        Validators\LiveStreamModeration::validate($data);
        $json = json_encode($data);
        $hmac = $this->hmac->generate($json);
        return $this->http->post(
            "/api/{$this->apiVersion}/livestream",
            $json,
            [
                "Authorization: hmac {$hmac}"
            ]
        );
    }

    /**
     * Create anonymous livestream
     */
    public function createAnonymousLivestream($data)
    {
        Validators\LiveStreamModeration::validate($data);
        $json = json_encode($data);
        $hmac = $this->hmac->generate($json);
        return $this->http->post(
            "/api/{$this->apiVersion}/livestream-anonymous",
            $json,
            [
                "Authorization: hmac {$hmac}"
            ]
        );
    }

    /**
     * Start livestream
     */
    public function startLivestream($id)
    {
        $uri = "/api/{$this->apiVersion}/livestream/{$id}/start";
        $hmac = $this->hmac->generate($uri);
        return $this->http->patch(
            $uri,
            "",
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
