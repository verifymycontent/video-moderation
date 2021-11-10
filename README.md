# VerifyMyContent Video Moderation PHP SDK

PHP SDK to use VerifyMyContent Video Moderation service. 

## Installation

```bash
composer require verifymycontent/video-moderation
```

## Get Started

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

// redirect uploader to check identity
header("Location: {$response['redirect_url']}");
```