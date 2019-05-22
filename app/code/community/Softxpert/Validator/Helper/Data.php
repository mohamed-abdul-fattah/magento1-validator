<?php

/**
 * Softxpert_Validator extension
 *
 * @category    Softxpert
 * @package     Softxpert_Validator
 * @copyright   Copyright (c) 2019
 * @author      Mohamed Abdul-Fattah <csmohamed8@gmail.com>
 */

class Softxpert_Validator_Helper_Data extends Mage_Core_Helper_Abstract
{
    /**
     * Properly parse the validation errors
     *
     * @param  array|string $errors
     * @return array
     */
    public function parseErrors($errors)
    {
        if ( is_string($errors) ) {
            return [
                'status' => Softxpert_Validator_Helper_Api::HTTP_UNPROCESSABLE_ENTITY,
                'detail' => (string) $errors
            ];
        }

        return $errors;
    }
}
