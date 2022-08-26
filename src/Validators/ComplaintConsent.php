<?php

namespace VerifyMyContent\VideoModeration\Validators;

class ComplaintConsent extends Validator
{
    public static function validate($input)
    {
        self::validateArray($input);
        self::validateArray($input, "content");

        self::validateString($input["content"], "external_id");
        self::validateString($input, "webhook");
        self::validateString($input["customer"], "id");
    }
}
