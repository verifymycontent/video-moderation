<?php namespace VerifyMyContent\VideoModeration;

use VerifyMyContent\Commons\Transport\InvalidStatusCodeException;
use VerifyMyContent\SDK\Complaint\Entity\Requests\CreateConsentComplaintRequest;
use VerifyMyContent\SDK\Complaint\Entity\Requests\CreateLiveContentComplaintRequest;
use VerifyMyContent\SDK\Complaint\Entity\Requests\CreateStaticContentComplaintRequest;
use VerifyMyContent\SDK\Complaint\Entity\Responses\CreateConsentComplaintResponse;
use VerifyMyContent\SDK\Complaint\Entity\Responses\CreateLiveContentComplaintResponse;
use VerifyMyContent\SDK\Complaint\Entity\Responses\CreateStaticContentComplaintResponse;
use VerifyMyContent\SDK\ContentModeration\Entity\Requests\CreateAnonymousLiveContentModerationRequest;
use VerifyMyContent\SDK\ContentModeration\Entity\Requests\CreateLiveContentModerationRequest;
use VerifyMyContent\SDK\ContentModeration\Entity\Requests\CreateStaticContentModerationRequest;
use VerifyMyContent\SDK\ContentModeration\Entity\Responses\CreateLiveContentModerationResponse;
use VerifyMyContent\SDK\ContentModeration\Entity\Responses\CreateStaticContentModerationResponse;
use VerifyMyContent\SDK\ContentModeration\Entity\Responses\GetLiveContentModerationResponse;
use VerifyMyContent\SDK\ContentModeration\Entity\Responses\GetStaticContentModerationParticipantsResponse;
use VerifyMyContent\SDK\ContentModeration\Entity\Responses\GetStaticContentModerationResponse;
use VerifyMyContent\SDK\Core\Validator\ValidationException;
use VerifyMyContent\SDK\VerifyMyContent;

define('VMC_SDK_VERSION', '2.0.7');

class Moderation {

    private $contentModerationClient;
    private $complaintClient;

    function __construct($clientID, $clientSecret)
    {
      $this->contentModerationClient = (new VerifyMyContent($clientID, $clientSecret))->contentModeration();
      $this->complaintClient = (new VerifyMyContent($clientID, $clientSecret))->complaint();
    }

    /**
     * Use the sandbox environment instead of the production one
     */
    public function useSandbox()
    {
        $this->contentModerationClient->useSandbox();
        $this->complaintClient->useSandbox();
    }

    /**
     * * Start a new video moderation
     *
     * https://docs.verifymyage.com/docs/content/moderation/index.html
     *
     * @param CreateStaticContentModerationRequest $data
     * @return CreateStaticContentModerationResponse
     * @throws InvalidStatusCodeException
     * @throws ValidationException
     */
    public function start($data)
    {
        return $this->contentModerationClient->createStaticContentModeration($data);
    }

    /**
     * * Get a moderation by ID
     *
     * https://docs.verifymyage.com/docs/content/moderation-v2/index.html
     *
     * @param string $id
     * @return GetStaticContentModerationResponse
     * @throws InvalidStatusCodeException
     * @throws ValidationException
     */
    public function get($id)
    {
        return $this->contentModerationClient->getStaticContentModeration($id);
    }

    /**
     * Retrieve uploader data
     *
     * @param string $id
     * @return GetStaticContentModerationParticipantsResponse
     * @throws InvalidStatusCodeException
     * @throws ValidationException
     */
    public function participants($id)
    {
        return $this->contentModerationClient->getStaticContentModerationParticipants($id);
    }

  /**
   * Create livestream
   *
   * @param CreateLiveContentModerationRequest $data
   * @return CreateLiveContentModerationResponse
   * @throws InvalidStatusCodeException
   * @throws ValidationException
   */
    public function createLivestream($data)
    {
        return $this->contentModerationClient->createLiveContentModeration($data);
    }

    /**
     * Create anonymous livestream
     *
     * @param CreateAnonymousLiveContentModerationRequest $data
     * @return CreateLiveContentModerationResponse
     * @throws InvalidStatusCodeException
     * @throws ValidationException
     */
    public function createAnonymousLivestream($data)
    {
        return $this->contentModerationClient->createAnonymousLiveContentModeration($data);
    }

    /**
     * * Start livestream
     *
     * @param string $id
     * @return void
     * @throws InvalidStatusCodeException
     */
    public function startLivestream($id)
    {
      $this->contentModerationClient->startLiveContentModeration($id);
    }

    /**
     * * Change livestream rule
     *
     * @param string $id
     * @param ChangeLiveContentRuleRequest $data
     * @return void
     * @throws InvalidStatusCodeException
     */
    public function changeLivestreamRule($id, $data)
    {
      $this->contentModerationClient->changeLiveContentRule($id, $data);
    }

    /**
     * * Get livestream
     *
     * @param string $id
     * @return GetLiveContentModerationResponse
     * @throws InvalidStatusCodeException
     * @throws ValidationException
     */
    public function getLivestream($id)
    {
      return $this->contentModerationClient->getLiveContentModeration($id);
    }

    /**
     * * Create consent complaint
     *
     * @param CreateConsentComplaintRequest $data
     * @return CreateConsentComplaintResponse
     * @throws InvalidStatusCodeException
     * @throws ValidationException
     */
    public function createComplaintConsent($data){
        return $this->complaintClient->createConsentComplaint($data);
    }

    /**
     * * Create moderation complaint
     *
     * @param CreateStaticContentComplaintRequest $data
     * @return CreateStaticContentComplaintResponse
     * @throws InvalidStatusCodeException
     * @throws ValidationException
     */
    public function createComplaintModeration($data){
        return $this->complaintClient->createStaticContentComplaint($data);
    }

    /**
     * * Create livestream complaint
     *
     * @param CreateLiveContentComplaintRequest $data
     * @return CreateLiveContentComplaintResponse
     * @throws InvalidStatusCodeException
     * @throws ValidationException
     */
    public function createComplaintLivestream($data){
        return $this->complaintClient->createLiveContentComplaint($data);
    }
}
