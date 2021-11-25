# VerifyMyContent Video Moderation PHP SDK

PHP SDK to use VerifyMyContent Video Moderation service. 

## Installation

```bash
composer require verifymycontent/video-moderation
```

## Get Started

The main class to handle the moderation integration process is the `VerifyMyContent\VideoModeration\Moderation`. It will abstract the HMAC generation for the API calls.

### Start a moderation

Use the `start` method to create a moderation, like the example bellow:

```php
<?php

require(__DIR__ . "/vendor/autoload.php");

$moderation = new VerifyMyContent\VideoModeration\Moderation(getenv('VMC_API_KEY'), getenv('VMC_API_SECRET'));
//$moderation->useSandbox();

$response = $moderation->start([
  "video" => [
    "external_id" => "YOUR-VIDEO-ID",
    "url" => "https://example.com/video.mp4"
  ],
  "webhook" => "https://example.com/webhook",
  "customer" => [
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

### Get a moderation by id

Retrieves a specific moderation to get current status. Example:

```php
<?php

require(__DIR__ . "/vendor/autoload.php");

$moderation = new VerifyMyContent\VideoModeration\Moderation(getenv('VMC_API_KEY'), getenv('VMC_API_SECRET'));
//$moderation->useSandbox();

$response = $moderation->get($moderationID);

var_dump($response['status']);
```

### Get uploader data

It will return uploader's identity check information if the moderation status is `Approved`. Example:

```php
<?php

require(__DIR__ . "/vendor/autoload.php");

$moderation = new VerifyMyContent\VideoModeration\Moderation(getenv('VMC_API_KEY'), getenv('VMC_API_SECRET'));
//$moderation->useSandbox();

$response = $moderation->participants($moderationID);

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
// instatiate VerifyMyContent helper class
$hmac = new VerifyMyContent\VideoModeration\Security\Hmac(getenv('VMC_API_KEY'), getenv('VMC_API_SECRET'));
// validate hmac Authorization
if(!array_key_exists('Authorization', $headers) || !$hmac->validate($headers['Authorization'], $body)) {
  die("This request did not come from VerifyMyContent");
}
// you can do your logic now, the webhook was called from VerifyMyContent.
var_dump($body);
```