<?php

class Softxpert_Validator_Validate_IsImage extends Zend_Validate_File_IsImage
{
    /**
     * @var array Error message templates
     */
    protected $_messageTemplates = array(
        self::FALSE_TYPE   => "The given file is not an image, '%type%' detected",
        self::NOT_DETECTED => "The mimetype of the given file could not be detected",
        self::NOT_READABLE => "The given file is not readable or does not exist",
    );
}
