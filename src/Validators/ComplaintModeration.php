<?php

namespace VerifyMyContent\VideoModeration\Validators;

class ComplaintModeration extends Validator
{
    public static function validate($input)
    {
        self::validateArray($input);
        self::validateArray($input, "content");

        self::validateString($input["content"], "external_id");
        self::validateArray($input["content"], "tags", 1);
        self::validateString($input["content"], "type");
        self::validateString($input["content"], "url");
        self::validateString($input, "webhook");

        if (is_array($input["customer"]) && array_key_exists("id", $input["customer"]))
        {
            self::validateString($input["customer"], "id");
        }
    }
}
