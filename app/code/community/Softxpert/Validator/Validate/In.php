<?php

/**
 * Softxpert_Validator extension
 *
 * @category    Softxpert
 * @package     Softxpert_Validator
 * @copyright   Copyright (c) 2019
 * @author      Mohamed Abdul-Fattah <csmohamed8@gmail.com>
 */

class Softxpert_Validator_Validate_In extends Zend_Validate_Abstract
{
    const NOT_IN = 'invalidOption';

    /**
     * Message template for invalidation.
     *
     * @var array
     */
    protected $_messageTemplates = [
        self::NOT_IN => "'%value%' is an invalid option"
    ];

    /**
     * Valid options haystack
     *
     * @var array
     */
    protected $haystack = [];

    /**
     * Set validator values to be the valid options.
     *
     * @throws Zend_Validate_Exception
     */
    public function __construct()
    {
        $values = func_get_args();
        if ( ! isset($values) || empty($values) ) {
            throw new Zend_Validate_Exception('Expected a comma separated options');
        }

        $this->haystack = $values;
    }

    /**
     * Returns true if and only if $value is contained in the haystack option.
     *
     * @param  mixed $value
     * @return bool
     */
    public function isValid($value)
    {
        $this->_setValue($value);

        if ( ! in_array($this->value, $this->haystack) ) {
            $this->_error(self::NOT_IN);
            return false;
        }

        return true;
    }
}