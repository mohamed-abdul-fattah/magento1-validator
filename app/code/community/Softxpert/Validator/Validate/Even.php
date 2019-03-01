<?php

class Softxpert_Validator_Validate_Even extends Zend_Validate_Digits
{
    const NOT_EVEN   = 'notEven';

    /**
     * Message template for invalidation.
     *
     * @var array
     */
    protected $_messageTemplates = [
        self::NOT_EVEN     => "'%value%' is not even",
        self::NOT_DIGITS   => "'%value%' must contain only digits",
        self::STRING_EMPTY => "Value cannot be empty",
        self::INVALID      => "Invalid type given. String, integer or float expected",
    ];

    /**
     * Returns true if and only if $value is an even integer.
     *
     * @param  integer $value
     * @return bool
     */
    public function isValid($value)
    {
        if ( ! parent::isValid($value) ) return false;

        if ( $value % 2 !== 0 ) {
            $this->_error(self::NOT_EVEN);
            return false;
        }

        return true;
    }
}