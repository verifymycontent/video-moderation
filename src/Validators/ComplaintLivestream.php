<?php
namespace VerifyMyContent\VideoModeration\Validators;

class ComplaintLivestream extends Validator
{
    public static function validate($input)
    {
        self::validateArray($input);
        self::validateArray($input, "stream");

        self::validateString($input["stream"], "external_id");
        self::validateArray($input["stream"], "tags", 1);
        self::validateString($input["stream"], "livestream_id");
        self::validateDate($input["complained_at"]);
        self::validateString($input, "webhook");

        if (is_array($input["customer"]) && array_key_exists("id", $input["customer"]))
        {
            self::validateString($input["customer"], "id");
        }
    }
}
