# Magento Validator
>Laravel like Magento 1.x validator.

Magento validator is a validation service to validate inputs for requests.
It depends on Zend validation classes, for more information about these validation rules visit [Zend Documentation](https://framework.zend.com/manual/1.12/en/zend.validate.set.html)
## Getting Started
### Installation
### How To
To validate a request you have 2 ways:
#### Inline Validation
```php
// Create an instance of the validator model
/** @var Softxpert_Validator_Model_Validator $validator */
$vaidator = Mage::getModel('softxpert_validator/validator');
// Preparing validator with data and rules arrays
$validator->validate($data, $rules);

// Check whether the data fails or passes
// returns true|false
if ( $validator->fails() ) {
    // Do some stuff
}
```

It supports nested inputs.
```php
$data = [
    'banner' => [
        'url'       => 'some value',
        'link_type' => 'cutom',
        'roles'     => ['admin', 'user'],
        'words'  => [
            [
                'letters' => 789
            ],
            [
                'letters' => 'string'
            ]
        ]
    ]
];

$rules = [
    'banner.url'     => 'string',
    'banner.url'     => 'string|in:valid,custom',
    'banner.roles.*' => 'string',
    'banner.words.*.letters' => 'string',
];

/** @var Softxpert_Validator_Model_Validator $validator */
$vaidator = Mage::getModel('softxpert_validator/validator');
$validator->validate($data, $rules);
```
#### Observer Validator
Create an observer for that particular route and handle all your request validations.
### Available Validation Rules
##### alpha
String under alpha rule must consist of alphabetic characters.
##### alpha_num
String under alpha_num rule must consist of alphabetic and numeric characters.
##### between:min,max
Validates a given value to be between `min` and `max` values.
##### break
Break chain of rules on the first failure.
##### date:format
Validates the given value to equal to a given date format. 
If format is not given, then the default format `(yyyy-mm-dd)` will be used.
##### digits
Validates a given value to be numeric.
##### email
Validates a given value to be an email.
##### even
Validate a given value to be an even number.
##### extensions:jpg,png,txt,...
Allowed extensions for a particular file input field.
##### float
Validates a given value to be float.
##### gt:min
Validates the given value to be greater that a `min` value.
##### integer
Validates a given value to be an integer.
##### image
The file under validation must be an image (jpeg, png, bmp, gif, ...)
##### in:foo,bar...
Validates that a given value is one of the provided options.
##### ip
Validates the given value to be an IP address.
##### lt:max
Validates the given value to be less than a `max` value.
##### nullable
Field under validation is allowed to be null/empty.
##### odd
Validates a given value to be odd number.
##### regex:pattern
Validates the given string to match a given pattern.
Eg: `regex:/^Test/`
##### required
Field under validation is required.
##### string
Validates that the given value is a string.
##### str_size:min,max
String size validates a string size with `min` and `max` values.
If `min` and `max` are not provided, then the rule validates the value as a string.
If only `min` is provided, then the rule validates the minimum string length with unlimited maximum length.
##### zip_code:locale
Validates the value to be a postal zip code. Locale must be provided.
Eg: `zip_code:ar_EG`
### Available Methods
##### validate
Prepare the validator with data and rules.
```php
/**
 * @param  array $data
 * @param  array $rules
 * @return Softxpert_Validator_Model_Validator
 */
public function validate($data, $rules)
```
##### isValid
Checks whether the data are valid or not.
If `$fieldName` is provided, then the validation would be applied only on this field.
```php
/**
 * @param  string|null $fieldName
 * @return bool
 */
public function isValid($fieldName = null)
```
##### fails
Checks whether the data fails the validation or not.
If `$fieldName` is provided, then the validation would be applied only on this field.
```php
/**
 * @param  string|null $fieldName
 * @return bool
 */
public function fails($fieldName = null)
```
##### getMessages
Return validation error messages array under `errors` error bag.
```php
/**
 * @return array
 */
public function getMessages()
```
##### getMessagesTemplate
Return validation error messages in an HTML un-ordered list block.
```php
/**
 * @return string
 */
public function getMessagesTemplate()
```