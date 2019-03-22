<?php

/**
 * Softxpert_Validator extension
 *
 * @category    Softxpert
 * @package     Softxpert_Validator
 * @copyright   Copyright (c) 2019
 * @author      Mohamed Abdul-Fattah <csmohamed8@gmail.com>
 */

class Softxpert_Validator_Model_Validator
{
    /**
     * Rules mapper to the Zend validators classes
     */
    const RULES_MAPPER = [
        'alpha'         => 'Alpha',
        'alpha_num'     => 'Alnum',
        'between'       => 'Between',
        'str_size'      => 'StringLength',
        'date'          => 'Date',
        'digits'        => 'Digits',
        'integer'       => 'Int',
        'float'         => 'Float',
        'email'         => 'EmailAddress',
        'gt'            => 'GreaterThan',
        'ipv4'          => 'Ip',
        'lt'            => 'LessThan',
        'zip_code'      => 'PostCode',
        'regex'         => 'Regex',
        'in'            => 'In',
        'string'        => 'StringLength',
        'image'         => 'IsImage',
        'extensions'    => 'Extension',
        'even'          => 'Even',
        'odd'           => 'Odd',
        'str_extension' => 'StrExtension',
    ];

    /**
     * Meta commands mapper.
     */
    const META_COMMANDS = [
        'required'  => [
            'key'   => 'presence',
            'value' => 'required',
        ],
        'nullable'  => [
            'key'   => 'allowEmpty',
            'value' => true,
        ],
        'break' => [
            'key'   => 'breakChainOnFailure',
            'value' => true,
        ]
    ];

    /**
     * @var Zend_Filter_Input
     */
    private $_validator;

    /**
     * Prepare the validator with rules.
     *
     * @param  array $data
     * @param  array $rules
     * @return $this
     */
    public function validate($data, $rules)
    {
        $this->_validator = new Zend_Filter_Input(null, $this->_prepareRules($rules),
            $this->_supportNestedValidations($rules, $data), [
                'validatorNamespace' => 'Softxpert_Validator_Validate',
                'missingMessage'     => 'The %field% field is required',
            ]);

        return $this;
    }

    /**
     * Check whether the input data are valid or not.
     * If a field name is provided, then the method
     * would check validity for the given field.
     *
     * @param  string|null $fieldName
     * @return bool
     */
    public function isValid($fieldName = null)
    {
        return $this->_validator->isValid($fieldName);
    }

    /**
     * Check whether the input data fail or not. If
     * a field name is provided, then the method
     * would check validity for the given field.
     *
     * @param  string|null $fieldName
     * @return bool
     */
    public function fails($fieldName = null)
    {
        return ! $this->_validator->isValid($fieldName);
    }

    /**
     * Get validation error messages.
     *
     * @return array
     */
    public function getMessages()
    {
        $errorsBag = [];

        foreach ( $this->_validator->getMessages() as $field => $messages ) {
            $errorsBag['errors'][$field] = array_values($messages);
        }

        return $errorsBag;
    }

    /**
     * Create an HTML error messages list.
     *
     * @return string
     */
    public function getMessagesTemplate()
    {
        $viewErrors = "<ul>";
        $validationErrors = $this->getMessages();

        foreach ( $validationErrors['errors'] as $field => $messages ) {
            $viewErrors .= "<li>". $this->_beautifyFieldName($field) ."<ul>";
            foreach ( $messages as $message ) {
                $viewErrors .= "<li>{$message}</li>";
            }
            $viewErrors .= "</ul></li>";
        }

        $viewErrors .= "</ul>";
        return $viewErrors;
    }

    /**
     * beautify field name to view in HTML templates.
     *
     * @param  string $fieldName
     * @return string
     */
    private function _beautifyFieldName($fieldName)
    {
        $escaped_      = str_replace('_', ' ', $fieldName);
        $escapedDashes = str_replace('-', ' ', $escaped_);
        $escapedDots   = str_replace('.', ' >> ', $escapedDashes);
        $escapedAstr   = str_replace('*', 'All', $escapedDots);
        return ucwords($escapedAstr);
    }

    /**
     * Parse the given rules and map them to Zend validation classes.
     *
     * @param  array $rules
     * @return array
     */
    private function _prepareRules($rules)
    {
        $zendRules = [];

        foreach ( $rules as $field => $rule ) {
            $pipedRules = explode('|', $rule);

            foreach ( $pipedRules as $index => $pipedRule ) {
                list($pipedRule, $args) = $this->_getRuleArguments($pipedRule);

                if ( ! is_null(self::RULES_MAPPER[$pipedRule]) ) {
                    $zendRules = $this->_lookUpValidationRules($zendRules, $pipedRule, $args, $field, $index);
                    continue;
                }

                /** Lookup meta commands */
                if ( ! is_null($metaCommand = self::META_COMMANDS[$pipedRule]) ) {
                    $zendRules[$field][$metaCommand['key']] = $metaCommand['value'];
                }
            }
        }

        return $zendRules;
    }

    /**
     * Extract rule arguments.
     *
     * @param  string $pipedRule
     * @return array
     */
    private function _getRuleArguments($pipedRule)
    {
        $ruleWithArgs = explode(':', $pipedRule);
        $args = null;

        if ( isset($ruleWithArgs[1]) ) {
            $args = explode(',', $ruleWithArgs[1]);
        }
        return array($ruleWithArgs[0], $args);
    }

    /**
     * Add rule and its arguments (if any).
     *
     * @param  array  $zendRules
     * @param  string $pipedRule
     * @param  array  $args
     * @param  string $field
     * @param  int    $index
     * @return array
     */
    private function _lookUpValidationRules($zendRules, $pipedRule, $args, $field, $index)
    {
        if ( $args ) {
            $zendRules[$field][$index][] = self::RULES_MAPPER[$pipedRule];
            foreach ( $args as $arg ) {
                $zendRules[$field][$index][] = $arg;
            }
        } else {
            $zendRules[$field][] = self::RULES_MAPPER[$pipedRule];
        }

        return $zendRules;
    }

    /**
     * Support nested array inputs.
     *
     * @param  array $rules
     * @param  array $data
     * @return array
     */
    private function _supportNestedValidations($rules, $data)
    {
        foreach ($rules as $field => $rule) {
            if (strpos($field, '.') !== false) {
                $data[$field] = $this->_getNestedInput($field, $data);
            }
        }

        return $data;
    }

    /**
     * Get the nested input value.
     *
     * @param  string $rule
     * @param  array  $data
     * @return null|mixed
     */
    private function _getNestedInput($rule, $data)
    {
        $keys         = explode('.', $rule);
        $desiredValue = $data;

        foreach ($keys as $key) {
            /** All nested array */
            if ( $key === '*' ) {
                $desiredValue = $this->_flattenArrayOfArrays($desiredValue);
                continue;
            }
            $desiredValue = $desiredValue[$key];
        }

        return $desiredValue;
    }

    /**
     * Convert array of arrays to one flat array
     * with array of values for each key.
     *
     * @param  array $arrays
     * @return array
     */
    private function _flattenArrayOfArrays($arrays)
    {
        $flat = [];

        foreach ($arrays as $array) {
            if ( ! is_array($array) ) {
                $flat[] = $array;
                continue;
            }

            foreach ($array as $key => $value) {
                if ( is_array($value) ) {
                    $flat[$key] = $value;
                    continue;
                }
                $flat[$key][] = $value;
            }
        }

        /** Check for null values */
        foreach (array_keys($flat) as $key) {
            foreach ($arrays as $array) {
                if ( is_null($array) ) {
                    $flat[$key][] = null;
                } elseif ( is_array($array) && is_null($array[$key]) ) {
                    $flat[$key][] = null;
                }
            }
        }

        return $flat;
    }
}
