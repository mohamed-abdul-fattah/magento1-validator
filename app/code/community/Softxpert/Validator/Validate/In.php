<?php

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
    private $_haystack = [];

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

        $this->_haystack = $values;
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

        if ( ! in_array($value, $this->_haystack) ) {
            $this->_error(self::NOT_IN);
            return false;
        }

        return true;
    }
}