<?php namespace VerifyMyContent\VideoModeration\Validators;


class LiveStreamModeration extends Validator{

    public static function validate($input)
    {
        if (!is_array($input))
        {
            throw new ValidationException("Live stream moderation data must be an array");
        }

        static::validateArray($input, "stream");
        static::validateArray($input, "customer");
        static::validateURL($input);
    }
}
