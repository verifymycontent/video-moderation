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

$response = $moderation->start([
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
]);

if (!isset($response['redirect_url'])) {
    die("Could not get a response from the VMC API");
}

// save $response['id'] if you want to call the moderation status endpoint later

// redirect uploader to check identity
header("Location: {$response['redirect_url']}");
```

### Retrieve Moderation by ID

Retrieves a specific moderation to get current status. Example:

```php
<?php

require(__DIR__ . "/vendor/autoload.php");

$moderation = new VerifyMyContent\VideoModeration\Moderation(getenv('VMC_API_KEY'), getenv('VMC_API_SECRET'));
//$moderation->useSandbox();

$response = $moderation->get($moderationID);

var_dump($response['status']);
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

$response = $moderation->createLivestream([
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
]);

if (!isset($response['login_url'])) {
    die("Could not get a response from the VMC API");
}

// save $response['id'] to start live stream later

// redirect uploader to check identity
header("Location: {$response['login_url']}");
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

# Webhook Security

In order to confirm that a webhook POST was sent from VerifyMyContent, we provide a helper class to validate that the Authorization header was sent correctly. Example:

```php
<?php
require(__DIR__ . "/vendor/autoload.php");

// get request body
$body = file_get_contents('php://input');
// get headers
$headers = getallheaders();
// instatiate VerifyMyContent helper class
$hmac = new VerifyMyContent\VideoModeration\Security\Hmac(getenv('VMC_API_KEY'), getenv('VMC_API_SECRET'));
// validate hmac Authorization
if(!array_key_exists('Authorization', $headers) || !$hmac->validate($headers['Authorization'], $body)) {
  die("This request did not come from VerifyMyContent");
}
// you can do your logic now, the webhook was called from VerifyMyContent.
var_dump($body);
```
