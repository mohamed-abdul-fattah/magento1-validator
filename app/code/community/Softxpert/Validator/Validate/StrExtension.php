<?php

/**
 * Softxpert_Validator extension
 *
 * @category    Softxpert
 * @package     Softxpert_Validator
 * @copyright   Copyright (c) 2019
 * @author      Mohamed Abdul-Fattah <csmohamed8@gmail.com>
 */

class Softxpert_Validator_Validate_StrExtension extends Softxpert_Validator_Validate_In
{
    /**
     * File name to be used in error message template.
     */
    const FILE = 'filename';

    /**
     * Given file name.
     *
     * @var string
     */
    protected $_file;

    public function __construct()
    {
        $values = func_get_args();
        parent::__construct(...$values);

        $this->setMessage("'%filename%' must be a file of type '%options%'", self::NOT_IN);
        $this->_messageVariables[self::FILE] = '_file';
    }

    /**
     * Get the extension from file name.
     *
     * @param mixed $value
     */
    protected function _setValue($value)
    {
        $pathInfo    = pathinfo($value);
        $this->_file = $pathInfo['basename'];
        parent::_setValue($pathInfo['extension']);
    }
}
