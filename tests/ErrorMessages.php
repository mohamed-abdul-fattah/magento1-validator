<?php

/**
 * Softxpert_Validator extension
 *
 * @category    Softxpert
 * @package     Softxpert_Validator
 * @copyright   Copyright (c) 2019
 * @author      Mohamed Abdul-Fattah <csmohamed8@gmail.com>
 */

class ErrorMessages
{
    /**
     * Field must have a non empty value error message.
     *
     * @param  string $field
     * @return string
     */
    public static function nonEmptyField($field)
    {
        return "You must give a non-empty value for field '{$field}'";
    }

    /**
     * Field is required error message.
     *
     * @param  string $field
     * @return string
     */
    public static function isRequired($field)
    {
        return "The {$field} field is required";
    }

    /**
     * Field is not an integer error message.
     *
     * @param  mixed $value
     * @return string
     */
    public static function fieldIsInteger($value)
    {
        return "'{$value}' does not appear to be an integer";
    }

    /**
     * Invalid option error message.
     *
     * @param  string $option
     * @return string
     */
    public static function fieldIsInvalidOption($option)
    {
        return "'{$option}' is an invalid option";
    }

    /**
     * Invalid email validation error message.
     *
     * @param  string $email
     * @return string
     */
    public static function invalidEmail($email)
    {
        return "'{$email}' is not a valid email address in the basic format local-part@hostname";
    }

    /**
     * Invalid alphanumeric value.
     *
     * @param  string $value
     * @return string
     */
    public static function invalidAlphaNum($value)
    {
        return "'{$value}' contains characters which are non alphabetic and no digits";
    }

    /**
     * Invalid alphabetical character.
     *
     * @param  string $value
     * @return string
     */
    public static function invalidAlpha($value)
    {
        return "'{$value}' contains non alphabetic characters";
    }

    /**
     * Value is not between the given min and max validation error message.
     *
     * @param  float $value
     * @param  float $min
     * @param  float $max
     * @return string
     */
    public static function notBetween($value, $min, $max)
    {
        return "'{$value}' is not between '{$min}' and '{$max}', inclusively";
    }

    /**
     * Invalid date format validation error message.
     *
     * @param  string $date
     * @return string
     */
    public static function invalidDate($date)
    {
        return "'{$date}' does not appear to be a valid date";
    }

    /**
     * Invalid digits value validation error message.
     *
     * @param  string $value
     * @return string
     */
    public static function notDigits($value)
    {
        return "'{$value}' must contain only digits";
    }

    /**
     * Value is not float validation error message.
     *
     * @param  string $value
     * @return string
     */
    public static function notFloat($value)
    {
        return "'{$value}' does not appear to be a float";
    }

    /**
     * Value is not greater than the min validation error message.
     *
     * @param  float $value
     * @param  float $min
     * @return string
     */
    public static function notGreaterThan($value, $min)
    {
        return "'{$value}' is not greater than '{$min}'";
    }

    /**
     * Invalid IP address validation error message.
     *
     * @param  string $ip
     * @return string
     */
    public static function invalidIpAddress($ip)
    {
        return "'{$ip}' does not appear to be a valid IP address";
    }

    /**
     * Value is not less than the max validation error message.
     *
     * @param  float $value
     * @param  float $max
     * @return string
     */
    public static function notLessThan($value, $max)
    {
        return "'{$value}' is not less than '{$max}'";
    }

    /**
     * Value doesn't match the given regex pattern validation error message.
     *
     * @param  string $value
     * @param  string $regex
     * @return string
     */
    public static function regexUnmatched($value, $regex)
    {
        return "'{$value}' does not match against pattern '{$regex}'";
    }

    /**
     * Given code is not a postal code validation error message.
     *
     * @param  integer $code
     * @return string
     */
    public static function noPostalCode($code)
    {
        return "'{$code}' does not appear to be a postal code";
    }

    /**
     * Invalid string validation error message.
     *
     * @return string
     */
    public static function invalidString()
    {
        return "Invalid type given. String expected";
    }

    /**
     * String size doesn't match validation error message.
     *
     * @param  string $value
     * @param  integer $min
     * @return string
     */
    public static function invalidMinStringSize($value, $min)
    {
        return "'{$value}' is less than {$min} characters long";
    }

    /**
     * String size is greater than the allowed size.
     *
     * @param  string $value
     * @param  int $max
     * @return string
     */
    public static function invalidMaxStringSize($value, $max)
    {
        return "'{$value}' is more than {$max} characters long";
    }

    /**
     * Invalid file extension validation error message.
     *
     * @param  string $value
     * @param  string $extensions
     * @return string
     */
    public static function invalidStrExtension($value, $extensions)
    {
        $file = pathinfo($value);
        return "'{$file['basename']}' must be a file of type '{$extensions}'";
    }

    /**
     * The given file is not readable validation error message.
     *
     * @return string
     */
    public static function notReadableFile()
    {
        return "The given file is not readable or does not exist";
    }

    /**
     * The given file is not an image validation error message.
     *
     * @param  string $mime
     * @return string
     */
    public static function notImage($mime)
    {
        return "The given file is not an image, '{$mime}' detected";
    }

    /**
     * The given file has a false extension validation error message.
     *
     * @return string
     */
    public static function falseExtension()
    {
        return "The given file has a false extension";
    }

    /**
     * @param  float $value
     * @return string
     */
    public static function notEven($value)
    {
        return "'{$value}' is not even";
    }

    /**
     * @param  float $value
     * @return string
     */
    public static function notOdd($value)
    {
        return "'{$value}' is not odd";
    }

    /**
     * Digit validator expects a string, integer or float value.
     *
     * @return string
     */
    public static function invalidDigitType()
    {
        return "Invalid type given. String, integer or float expected";
    }

    /**
     * Integer validator expects a string or integer value.
     *
     * @return string
     */
    public static function invalidIntegerType()
    {
        return "Invalid type given. String or integer expected";
    }
}