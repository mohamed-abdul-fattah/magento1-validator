<?php

/**
 * Softxpert_Validator extension
 *
 * @category    Softxpert
 * @package     Softxpert_Validator
 * @copyright   Copyright (c) 2019
 * @author      Mohamed Abdul-Fattah <csmohamed8@gmail.com>
 */

class Softxpert_Validator_Helper_Api extends Mage_Core_Helper_Abstract
{
    /**
     * HTTP Status Codes.
     */
    const HTTP_OK                   = 200;
    const HTTP_CREATED              = 201;
    const HTTP_ACCEPTED             = 202;
    const HTTP_NO_CONTENT           = 204;
    const HTTP_MOVED_PERMANENTLY    = 301;
    const HTTP_FOUND                = 302;
    const HTTP_SEE_OTHER            = 303;
    const HTTP_TEMPORARY_REDIRECT   = 307;
    const HTTP_PERMANENTLY_REDIRECT = 308;
    const HTTP_BAD_REQUEST          = 400;
    const HTTP_UNAUTHORIZED         = 401;
    const HTTP_PAYMENT_REQUIRED     = 402;
    const HTTP_FORBIDDEN            = 403;
    const HTTP_NOT_FOUND            = 404;
    const HTTP_METHOD_NOT_ALLOWED   = 405;
    const HTTP_UNPROCESSABLE_ENTITY = 422;
    const HTTP_INTERNAL_SERVER_ERROR= 500;

    /**
     * Return JSON response.
     *
     * @param array $data
     * @param int   $code
     */
    public function jsonResponse($data = [], $code = 200)
    {
        Mage::app()->getResponse()->setHeader('Content-type', 'application/json');
        Mage::app()->getResponse()->setBody(is_string($data) ? $data : json_encode($data));
        Mage::app()->getResponse()->setHttpResponseCode($code);
    }
}
