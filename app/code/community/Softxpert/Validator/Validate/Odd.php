<?php

class Softxpert_Validator_Validate_Odd extends Zend_Validate_Digits
{
    const NOT_ODD = 'notEven';

    /**
     * Message template for invalidation.
     *
     * @var array
     */
    protected $_messageTemplates = [
        self::NOT_ODD      => "'%value%' is not odd",
        self::NOT_DIGITS   => "'%value%' must contain only digits",
        self::STRING_EMPTY => "Value cannot be empty",
        self::INVALID      => "Invalid type given. String, integer or float expected",
    ];

    /**
     * Returns true if and only if $value is an odd integer.
     *
     * @param  integer $value
     * @return bool
     */
    public function isValid($value)
    {
        if ( ! parent::isValid($value) ) return false;

        if ( $value % 2 === 0 ) {
            $this->_error(self::NOT_ODD);
            return false;
        }

        return true;
    }
}