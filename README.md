# VerifyMyContent Video Moderation PHP SDK

PHP SDK to use the VerifyMyContent Video Moderation service.

## Installation

```bash
composer require verifymycontent/video-moderation
```

## Get Started

The main class to handle the moderation integration process is the `VerifyMyContent\VideoModeration\Moderation`. It will abstract the HMAC generation for the API calls.

### Start a Moderation

Use the `start` method to create a moderation, like the example below:

```php
<?php

require(__DIR__ . "/vendor/autoload.php");

$moderation = new VerifyMyContent\VideoModeration\Moderation(getenv('VMC_API_KEY'), getenv('VMC_API_SECRET'));
//$moderation->useSandbox();

$response = $moderation->start(new \VerifyMyContent\SDK\ContentModeration\Entity\Requests\CreateStaticContentModerationRequest([
  "content" => [
    "type" => "video",
    "external_id" => "YOUR-VIDEO-ID",
    "url" => "https://example.com/video.mp4",
    "title" => "Uploaded video title",
    "description" => "Uploaded video description",
  ],
  "webhook" => "https://example.com/webhook",
  "customer" => [
    "id" => "YOUR-CUSTOMER-UNIQUE-ID",
    "email" => "person@example.com",
    "phone" => "+4412345678"
  ]
]));

// save $response->id if you want to call the moderation status endpoint later

// redirect uploader to check identity
header("Location: {$response->redirect_url}");
```

### Retrieve Moderation by ID

Retrieves a specific moderation to get current status. Example:

```php
<?php

require(__DIR__ . "/vendor/autoload.php");

$moderation = new VerifyMyContent\VideoModeration\Moderation(getenv('VMC_API_KEY'), getenv('VMC_API_SECRET'));
//$moderation->useSandbox();

// Printing current status
echo "Status: {$response->status}";
```

### Get Uploader Data

It will return uploader's identity check information if the moderation status is `Approved`. Example:

```php
<?php

require(__DIR__ . "/vendor/autoload.php");

$moderation = new VerifyMyContent\VideoModeration\Moderation(getenv('VMC_API_KEY'), getenv('VMC_API_SECRET'));
//$moderation->useSandbox();

$response = $moderation->participants($moderationID);

var_dump($response);
```

## Live Stream

To moderate a live stream broadcast you'll need to use different APIs as described below.

### Create a Live Stream Moderation

Use the `createLivestream` method to create a live stream moderation, like the example below:

```php
<?php

require(__DIR__ . "/vendor/autoload.php");

$moderation = new VerifyMyContent\VideoModeration\Moderation(getenv('VMC_API_KEY'), getenv('VMC_API_SECRET'));
//$moderation->useSandbox();

$response = $moderation->createLivestream(new \VerifyMyContent\SDK\ContentModeration\Entity\Requests\CreateLiveContentModerationRequest([
  "external_id" => "YOUR-LIVESTREAM-ID",
  "embed_url" => "https://example.com/live/",
  "title" => "Live stream title",
  "description" => "Live stream description",
  "webhook" => "https://example.com/webhook",
  "stream" => [
      "protocol" => "webrtc",
      "url" => "https://example.com/live/",
  ],
  "customer" => [
      "id" => "YOUR-CUSTOMER-UNIQUE-ID",
      "email" => "person@example.com",
      "phone" => "+4412345678"
  ]
]));

// save $response->id to start live stream later

// redirect uploader to check identity
header("Location: {$response->login_url");
```

### Start a created Live Stream Moderation

When you receive the webhook with the status `Authorised`, it means you can now start to broadcast a live stream, you can then use the `startLivestream` method to trigger the moderation:

```php
<?php

require(__DIR__ . "/vendor/autoload.php");
    
$moderation = new VerifyMyContent\VideoModeration\Moderation(getenv('VMC_API_KEY'), getenv('VMC_API_SECRET'));
//$moderation->useSandbox();

$success = $moderation->startLivestream($_GET['id']);
var_dump($success === true);
```

**Note:** You'll have a limit of time to send this request after you received the webhook notifying the user was authorised to start the broadcast.


### Updating Live Stream moderation rules
This endpoint allows you to update the moderation rules for a specific live stream

```php
<?php

require(__DIR__ . "/vendor/autoload.php");
    
$moderation = new VerifyMyContent\VideoModeration\Moderation(getenv('VMC_API_KEY'), getenv('VMC_API_SECRET'));
//$moderation->useSandbox();

$success = $moderation->changeLivestreamRule($_GET['id'], new \VerifyMyContent\SDK\ContentModeration\Entity\Requests\ChangeLiveContentRuleRequest([
  "rule" => "no-nudity"
]));
var_dump($success === true);
```


## Complaint Resolution

To start a complaint for previously uploaded content. You need to send the original content and the violations raised by the user.

### Create a Complaint Moderation

Use the `createComplaintModeration` method to create a complaint moderation, like the example below:

```php
<?php

require(__DIR__ . "/vendor/autoload.php");

$moderation = new VerifyMyContent\VideoModeration\Moderation(getenv('VMC_API_KEY'), getenv('VMC_API_SECRET'));
//$moderation->useSandbox();

$response = $moderation->createComplaintModeration(new \VerifyMyContent\SDK\Complaint\Entity\Requests\CreateStaticContentComplaintRequest([
   "content" => [
      "description" => "Your description", 
      "external_id" => "YOUR-VIDEO-ID", 
      "tags" => [
          "VIOLATION_1" 
      ], 
      "title" => "Your title", 
      "type" => "video", 
      "url" => "https://example.com/video.mp4" 
   ], 
   "customer" => [
      "id" => "YOUR-USER-ID" 
   ], 
   "webhook" => "https://example.com/webhook" 
]));

var_dump($response);
```

### Create a Live Stream Complaint Moderation

Use the `createComplaintLivestream` method to create a live stream complaint moderation, like the example below:

```php
<?php

require(__DIR__ . "/vendor/autoload.php");

$moderation = new VerifyMyContent\VideoModeration\Moderation(getenv('VMC_API_KEY'), getenv('VMC_API_SECRET'));
//$moderation->useSandbox();

$response = $moderation->createComplaintLivestream(new \VerifyMyContent\SDK\Complaint\Entity\Requests\CreateLiveContentComplaintRequest([
   "complained_at" => "2022-11-04T12:04:08.658Z", 
   "customer" => [
      "id" => "YOUR-USER-ID" 
   ], 
   "stream" => [
      "external_id" => "YOUR-LIVESTREAM-ID", 
      "tags" => [
        "VIOLATION_1" 
      ]
   ], 
   "webhook" => "https://example.com/webhook" 
]));

var_dump($response);
```

### Create a Consent Complaint

Use the `createComplaintConsent` method to create a complaint consent moderation, like the example below:

```php
<?php

require(__DIR__ . "/vendor/autoload.php");

$moderation = new VerifyMyContent\VideoModeration\Moderation(getenv('VMC_API_KEY'), getenv('VMC_API_SECRET'));
//$moderation->useSandbox();

$response = $moderation->createComplaintConsent(new \VerifyMyContent\SDK\Complaint\Entity\Requests\CreateConsentComplaintRequest([
   "content" => [
      "external_id" => "YOUR-VIDEO-ID" 
   ], 
   "customer" => [
      "id" => "YOUR-USER-ID" 
   ], 
   "webhook" => "https://example.com/webhook"
]));

var_dump($response);
```

# Webhook Security

In order to confirm that a webhook POST was sent from VerifyMyContent, we provide a helper class to validate that the Authorization header was sent correctly. Example:

```php
<?php
require(__DIR__ . "/vendor/autoload.php");

// get request body
$body = file_get_contents('php://input');
// get headers
$headers = getallheaders();
// instantiate VerifyMyContent helper class
$hmac = new VerifyMyContent\Commons\Security\HMAC(getenv('VMC_API_KEY'), getenv('VMC_API_SECRET'));
// validate hmac Authorization
if(!array_key_exists('Authorization', $headers) || !$hmac->validate($headers['Authorization'], $body)) {
  die("This request did not come from VerifyMyContent");
}
// you can do your logic now, the webhook was called from VerifyMyContent.
var_dump($body);
```
