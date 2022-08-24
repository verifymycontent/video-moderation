<?php namespace VerifyMyContent\VideoModeration\Validators;


class StartModeration extends Validator{

    public static function validate($input)
    {
        if (!is_array($input))
        {
            throw new ValidationException("Start moderation data must be an array");
        }

        static::validateArray($input, "content");
        static::validateArray($input, "customer");
        static::validateURL($input, "content");
    }
}
