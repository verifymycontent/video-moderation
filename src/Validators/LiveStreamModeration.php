<?php namespace VerifyMyContent\VideoModeration\Validators;


class LiveStreamModeration {

    public static function validate($input)
    {
        if (!is_array($input)) 
        {
            throw new ValidationException("Live stream moderation data must be an array");
        }

        static::validateArray($input, "stream");
        static::validateArray($input, "customer");
        static::validateEmbedURL($input);
    }

    private static function validateArray($input, $field)
    {
        if (!array_key_exists($field, $input) || !is_array($input[$field]) || !is_array($input[$field]))
        {
            throw new ValidationException("Missing required array field: '{$field}'");
        }
    }

    private static function validateEmbedURL($video)
    {
        if (!array_key_exists("embed_url", $video) || trim($video["embed_url"]) === "")
        {
            throw new ValidationException("Missing 'embed_url'"); 
        }

        if (!preg_match('/^https?:\/\//', $video["embed_url"]))
        {
            throw new ValidationException("Invalid video embed_url: it must be an https URL.");
        }
    }

}