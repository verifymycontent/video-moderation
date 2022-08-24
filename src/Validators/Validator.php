<?php

namespace VerifyMyContent\VideoModeration\Validators;

abstract class Validator
{

    /**
     * @param $input
     * @throws ValidationException
     */
    abstract public static function validate($input);

    /**
     * @param $field
     * @param $input array
     * @throws ValidationException
     */
    protected static function validateArray($input, $field = null){
        if ($field === null && !is_array($input)) {
            throw new ValidationException("Data must be an array");
        }

        if ($field !== null){
            if (!array_key_exists($field, $input) || !is_array($input[$field])){
                throw new ValidationException("Missing required array field: '{$field}'");
            }
        }
    }

    /**
     * @param $field
     * @param $input array|string
     * @throws ValidationException
     */
    protected static function validateString($input, $field = null)
    {
        if ($field === null && !is_string($input)) {
            throw new ValidationException("Data must be a string");
        }

        if ($field !== null) {
            if (!array_key_exists($field, $input) || !is_string($input[$field])) {
                throw new ValidationException("Missing required string field: '{$field}'");
            }
        }
    }

    /**
     * @param $field
     * @param $input array|string
     * @throws ValidationException
     */
    protected static function validateURL($input, $field = null){
        if ($field === null && !is_string($input)) {
            throw new ValidationException("Data must be a string");
        }

        if ($field !== null) {
            if (!array_key_exists($field, $input) || !is_string($input[$field])) {
                throw new ValidationException("Missing required string field: '{$field}'");
            }

            if (!preg_match('/^https?:\/\//', $input[$field])) {
                throw new ValidationException("Invalid '{$field}' url");
            }
        }
    }

    /**
     * @param $field
     * @param $input array|string
     * @throws ValidationException
     */
    protected static function validateInteger($input, $field = null){
        if ($field === null && !is_int($input)) {
            throw new ValidationException("Data must be an integer");
        }

        if ($field !== null) {
            if (!array_key_exists($field, $input) || !is_int($input[$field])) {
                throw new ValidationException("Missing required integer field: '{$field}'");
            }
        }
    }
}
