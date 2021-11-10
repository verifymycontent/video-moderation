<?php namespace VerifyMyContent\VideoModeration\Validators;

use ValidationException;

class StartModeration {

    public static function validate($input)
    {
        if (!is_array($input)) 
        {
            throw new ValidationException("Start moderation data must be an array");
        }

        static::validateArray($input, "video");
        static::validateArray($input, "customer");
        static::validateURL($input["video"]);
    }

    private static function validateArray($input, $field)
    {
        if (!array_key_exists($field, $input) || !is_array($input[$field]) || !is_array($input[$field]))
        {
            throw new ValidationException("Missing required array field: '{$field}'");
        }
    }

    private static function validateURL($video)
    {
        if (!array_key_exists("url", $video) || trim($video["url"]) === "")
        {
            throw new ValidationException("Missing 'url' on 'video' field"); 
        }

        if (!preg_match('/^https?:\/\//', $video["url"]))
        {
            throw new ValidationException("Invalid video url");
        }
    }

}