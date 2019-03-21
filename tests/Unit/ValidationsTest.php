<?php

/**
 * Softxpert_Validator extension
 *
 * @category    Softxpert
 * @package     Softxpert_Validator
 * @copyright   Copyright (c) 2019
 * @author      Mohamed Abdul-Fattah <csmohamed8@gmail.com>
 */

class ValidationsTest extends TestCase
{
    /**
     * @var Softxpert_Validator_Model_Validator
     */
    private $_validator;

    public function setUp()
    {
        $this->_validator = Mage::getModel('softxpert_validator/validator');
    }

    /**
     * @test
     * @var  array $data
     * @var  array $rules
     * @dataProvider dataProvider
     */
    public function validateValidRules($data, $rules)
    {
        $this->_validator->validate($data, $rules);

        $this->assertTrue($this->_validator->isValid());
        $this->assertEquals(0, count($this->_validator->getMessages()));
    }

    public function dataProvider()
    {
        return [
            'valid'              => [
                ['username' => 'username'],
                ['username' => 'alpha'],
            ],
            'no data'            => [
                [],
                ['username' => 'alpha'],
            ],
            'no rules'           => [
                ['username' => 'username'],
                [],
            ],
            'single rules valid' => [
                [
                    'email'     => 'email@company.com',
                    'username'  => 'username123',
                    'name'      => 'Mohamed',
                    'age'       => 25,
                    'bod'       => '23/09/2018',
                    'phone'     => 123456789,
                    'salary'    => 2000.5,
                    'income'    => 9000,
                    'serial'    => 5312,
                    'ip'        => '192:168:1:20',
                    'total'     => 6000,
                    'regex'     => 'Test',
                    'zip_code'  => 21539,
                    'bio'       => 'This is my bio...',
                    'string'    => 'Mohamed Abdul-Fattah',
                    'option'    => 'valid',
                    'extension' => 'image.png',
                ],
                [
                    'email'     => 'email',
                    'username'  => 'alpha_num',
                    'name'      => 'alpha',
                    'age'       => 'between:15,30',
                    'bod'       => 'date',
                    'phone'     => 'digits',
                    'salary'    => 'float',
                    'income'    => 'gt:8000',
                    'serial'    => 'integer',
                    'ip'        => 'ip',
                    'total'     => 'lt:9000',
                    'regex'     => 'regex:/^Test/',
                    'zip_code'  => 'zip_code:ar_EG',
                    'bio'       => 'str_length:5,20',
                    'string'    => 'string',
                    'option'    => 'in:invalid,valid',
                    'extension' => 'str_extension:jpg,png',
                ],
            ],
            'Even and odd rules' => [
                [
                    'even' => 2,
                    'odd'  => 3,
                ],
                [
                    'even' => 'even',
                    'odd'  => 'odd',
                ],
            ],
        ];
    }

    /** @test */
    public function invalidSingleRules()
    {
        $data = [
            'email'    => 'not email',
            'username' => '*****',
            'name'     => '123456',
            'age'      => '31',
            'bod'      => '2018-30-30',
            'phone'    => 'string',
            'salary'   => 'str',
            'income'   => 7000,
            'serial'   => 1532.5,
            'ip'       => '192.168.300.2',
            'total'    => 9500,
            'regex'    => 'test',
            'zip_code' => 2153,
            'bio'      => 'NULL',
            'string'   => 123,
            'option'   => 'invalid',
        ];
        $rules = [
            'email'    => 'email',
            'username' => 'alpha_num',
            'name'     => 'alpha',
            'age'      => 'between:15,30',
            'bod'      => 'date',
            'phone'    => 'digits',
            'salary'   => 'float',
            'income'   => 'gt:8000',
            'serial'   => 'integer',
            'ip'       => 'ip',
            'total'    => 'lt:9000',
            'regex'    => 'regex:/^Test/',
            'zip_code' => 'zip_code:ar_EG',
            'bio'      => 'str_size:5,20',
            'string'   => 'string',
            'option'   => 'in:valid,option,value',
        ];
        $errors = [
            'errors' => [
                'email'    => [ErrorMessages::invalidEmail('not email')],
                'username' => [ErrorMessages::invalidAlphaNum('*****')],
                'name'     => [ErrorMessages::invalidAlpha('123456')],
                'age'      => [ErrorMessages::notBetween(31, 15, 30)],
                'bod'      => [ErrorMessages::invalidDate('2018-30-30')],
                'phone'    => [ErrorMessages::notDigits('string')],
                'salary'   => [ErrorMessages::notFloat('str')],
                'income'   => [ErrorMessages::notGreaterThan(7000, 8000)],
                'serial'   => [ErrorMessages::fieldIsInteger(1532.5)],
                'ip'       => [ErrorMessages::invalidIpAddress('192.168.300.2')],
                'total'    => [ErrorMessages::notLessThan(9500, 9000)],
                'regex'    => [ErrorMessages::regexUnmatched('test', '/^Test/')],
                'zip_code' => [ErrorMessages::noPostalCode(2153)],
                'bio'      => [ErrorMessages::invalidMinStringSize('NULL', 5)],
                'string'   => [ErrorMessages::invalidString()],
                'option'   => [ErrorMessages::fieldIsInvalidOption('invalid')],
            ],
        ];

        $this->_validator->validate($data, $rules);

        $this->assertFalse($this->_validator->isValid());
        $this->assertTrue($this->_validator->fails());
        $this->assertArraySubset($errors, $this->_validator->getMessages());
    }

    /** @test */
    public function invalid_ValidComplexRules()
    {
        $data = [
            'username' => 'username',
            'age'      => 25,
            'name'     => 'name',
            'empty'    => '',
        ];
        $rules = [
            'username' => 'alpha|integer',
            'age'      => 'integer|str_size:3,5',
            'name'     => 'alpha|str_size:0,20',
            'empty'    => 'integer',
        ];
        $errors = [
            'errors' => [
                'username' => [ErrorMessages::fieldIsInteger('username')],
                'age'      => [ErrorMessages::invalidString()],
                'empty'    => [ErrorMessages::nonEmptyField('empty')],
            ],
        ];

        $this->_validator->validate($data, $rules);
        $this->assertFalse($this->_validator->isValid());
        $this->assertArraySubset($errors, $this->_validator->getMessages());
    }

    /** @test */
    public function validateStrSizeParams()
    {
        /** defaults test */
        $this->_validator->validate([
            'string' => 'string',
        ], [
            'string' => 'str_size',
        ]);

        $this->assertTrue($this->_validator->isValid());
        $this->assertFalse($this->_validator->fails());

        /** default max */
        $this->_validator->validate([
            'string' => 'string',
        ], [
            'string' => 'str_size:20',
        ]);

        $this->assertFalse($this->_validator->isValid());
        $this->assertArraySubset([
            'errors' => [
                'string' => [ErrorMessages::invalidMinStringSize('string', 20)],
            ],
        ], $this->_validator->getMessages());

        /** test max */
        $this->_validator->validate([
            'string' => 'string',
        ], [
            'string' => 'str_size:1,5',
        ]);

        $this->assertFalse($this->_validator->isValid());
        $this->assertArraySubset([
            'errors' => [
                'string' => [ErrorMessages::invalidMaxStringSize('string', 5)],
            ],
        ], $this->_validator->getMessages());
    }

    public function templateMessagesProvider()
    {
        return [
            [
                ['username' => 'username'],
                ['username' => 'integer'],
                "<ul><li>Username<ul><li>'username' does not appear to be an integer</li></ul></li></ul>",
            ],
            [
                ['first_name' => '_false_'],
                ['first_name' => 'alpha'],
                "<ul><li>First Name<ul><li>'_false_' contains non alphabetic characters</li></ul></li></ul>",
            ],
        ];
    }

    /**
     * @test
     * @param  array  $data
     * @param  array  $rules
     * @param  string $message
     * @dataProvider templateMessagesProvider
     */
    public function templateMessages($data, $rules, $message)
    {
        $this->_validator->validate($data, $rules);
        $this->assertEquals(
            $message,
            $this->_validator->getMessagesTemplate());
    }

    /**
     * @test
     * @expectedException Zend_Validate_Exception
     * @expectedExceptionMessage Expected a comma separated options
     */
    public function cannotUseInRuleWithoutOptions()
    {
        $data  = ['option' => 'invalid'];
        $rules = ['option' => 'in'];

        $this->_validator->validate($data, $rules);
        $this->_validator->isValid();
    }

    public function imagesProvider()
    {
        return [
            [
                [
                    'file' => NULL,
                    'ext'  => NULL,
                ],
                [
                    'file' => 'image',
                    'ext'  => 'extensions:txt,log',
                ],
                [
                    'errors' => [
                        'file' => [
                            ErrorMessages::nonEmptyField('file'),
                        ],
                        'ext'  => [
                            ErrorMessages::nonEmptyField('ext'),
                        ],
                    ],
                ],
            ],
            [
                [
                    'file' => $this->faker->image(NULL, 640, 480, NULL, false),
                    'ext'  => $this->faker->image(NULL, 640, 480, NULL, false),
                ],
                [
                    'file' => 'image',
                    'ext'  => 'extensions:txt,log',
                ],
                [
                    'errors' => [
                        'file' => [
                            ErrorMessages::notReadableFile(),
                        ],
                        'ext'  => [
                            ErrorMessages::notReadableFile(),
                        ],
                    ],
                ],
            ],
            [
                [
                    'file'      => __DIR__ . '/../fixtures/Unit/dummy.txt',
                    'ext'       => __DIR__ . '/../fixtures/Unit/magento.jpg',
                    'extension' => 'image.bmp',
                ],
                [
                    'file'      => 'image',
                    'ext'       => 'extensions:txt,log',
                    'extension' => 'str_extension:jpg,png,gif',
                ],
                [
                    'errors' => [
                        'file'      => [
                            ErrorMessages::notImage('text/plain'),
                        ],
                        'ext'       => [
                            ErrorMessages::falseExtension(),
                        ],
                        'extension' => [
                            ErrorMessages::invalidStrExtension('image.bmp', 'jpg,png,gif'),
                        ],
                    ],
                ],
            ],
            [
                [
                    'file' => __DIR__ . '/../fixtures/Unit/magento.jpg',
                    'ext'  => __DIR__ . '/../fixtures/Unit/dummy.txt',
                ],
                [
                    'file' => 'image',
                    'ext'  => 'extensions:txt,log',
                ],
                [],
            ],
        ];
    }

    /**
     * @test
     * @param array $data
     * @param array $rules
     * @param array $messages
     * @dataProvider imagesProvider
     */
    public function validateImages($data, $rules, $messages)
    {
        $this->_validator->validate($data, $rules);
        $this->assertArraySubset($messages, $this->_validator->getMessages());
    }

    public function metaCommandsProvider()
    {
        return [
            [
                [],
                ['input' => 'required'],
                [
                    'errors' => [
                        'input' => [ErrorMessages::isRequired('input')],
                    ],
                ],
                false,
            ],
            [
                ['input' => 'value'],
                ['input' => 'required'],
                [],
                true,
            ],
            [
                ['input' => ''],
                ['input' => 'required'],
                [
                    'errors' => [
                        'input' => [ErrorMessages::nonEmptyField('input')],
                    ],
                ],
                false,
            ],
            [
                ['input' => ''],
                ['input' => 'nullable'],
                [],
                true,
            ],
            [
                [],
                ['input' => 'required|nullable|alpha'],
                [
                    'errors' => [
                        'input' => [ErrorMessages::isRequired('input')],
                    ],
                ],
                false,
            ],
            [
                ['input' => ''],
                ['input' => 'alpha|nullable'],
                [],
                true,
            ],
            [
                ['input' => ''],
                ['input' => 'image|nullable'],
                [],
                true,
            ],
            [
                [],
                ['input' => 'required|alpha'],
                [
                    'errors' => [
                        'input' => [ErrorMessages::isRequired('input')],
                    ],
                ],
                false,
            ],
            [
                ['input' => ''],
                ['input' => 'string|alpha|break'],
                [
                    'errors' => [
                        'input' => [ErrorMessages::nonEmptyField('input')],
                    ],
                ],
                false,
            ],
            [
                ['input' => '***'],
                ['input' => 'string|alpha|email|break'],
                [
                    'errors' => [
                        'input' => [ErrorMessages::invalidAlpha('***')],
                    ],
                ],
                false,
            ],
        ];
    }

    /**
     * @test
     * @param array $data
     * @param array $rules
     * @param array $messages
     * @param bool  $isValid
     * @dataProvider metaCommandsProvider
     */
    public function validateMetaCommands($data, $rules, $messages, $isValid)
    {
        $this->_validator->validate($data, $rules);
        $this->assertArraySubset($messages, $this->_validator->getMessages());
        $this->assertEquals($isValid, $this->_validator->isValid());
    }

    public function evenOddProvider()
    {
        return [
            [
                [
                    'even' => 'string',
                    'odd'  => 'string',
                ],
                [
                    'even' => 'even',
                    'odd'  => 'odd',
                ],
                [
                    'errors' => [
                        'even' => [ErrorMessages::notDigits('string')],
                        'odd'  => [ErrorMessages::notDigits('string')],
                    ],
                ],
            ],
            [
                [
                    'even' => '',
                    'odd'  => '',
                ],
                [
                    'even' => 'even',
                    'odd'  => 'odd',
                ],
                [
                    'errors' => [
                        'even' => [ErrorMessages::nonEmptyField('even')],
                        'odd'  => [ErrorMessages::nonEmptyField('odd')],
                    ],
                ],
            ],
            [
                [
                    'even' => 3,
                    'odd'  => 2,
                ],
                [
                    'even' => 'even',
                    'odd'  => 'odd',
                ],
                [
                    'errors' => [
                        'even' => [ErrorMessages::notEven(3)],
                        'odd'  => [ErrorMessages::notOdd(2)],
                    ],
                ],
            ],
            [
                [
                    'even' => [['arr']],
                    'odd'  => [['arr']],
                ],
                [
                    'even' => 'integer|even',
                    'odd'  => 'odd',
                ],
                [
                    'errors' => [
                        'even' => [
                            ErrorMessages::invalidIntegerType(),
                            ErrorMessages::invalidDigitType(),
                        ],
                        'odd'  => [ErrorMessages::invalidDigitType()],
                    ],
                ],
            ],
        ];
    }

    /**
     * @test
     * @param array $data
     * @param array $rules
     * @param array $messages
     * @dataProvider evenOddProvider
     */
    public function invalidEvenOddRules($data, $rules, $messages)
    {
        $this->_validator->validate($data, $rules);
        $this->assertArraySubset($messages, $this->_validator->getMessages());
        $this->assertTrue($this->_validator->fails());
    }

    /** @test */
    public function validateNestedArrays()
    {
        $data = [
            'banner' => [
                'url'       => 123,
                'link_type' => 'invalid',
                'role'      => 'admin',
                'images'    => [
                    'banner' => __DIR__ . '/../fixtures/Unit/magento.jpg',
                    'thumb'  => __DIR__ . '/../fixtures/Unit/dummy.txt',
                ],
                'words'     => [
                    'word1', 'word2', 123,
                ],
            ],
        ];
        $rules = [
            'banner.url'           => 'string',
            'banner.link_type'     => 'in:valid,custom',
            'banner.role'          => 'in:user,admin',
            'banner.images.banner' => 'image',
            'banner.images.thumb'  => 'image',
            'banner.words.*'       => 'string',
        ];

        $this->_validator->validate($data, $rules);

        $this->assertTrue($this->_validator->fails());
        $this->assertArraySubset([
            'errors' => [
                'banner.url'          => [ErrorMessages::invalidString()],
                'banner.link_type'    => [ErrorMessages::fieldIsInvalidOption('invalid')],
                'banner.images.thumb' => [ErrorMessages::notImage('text/plain')],
                'banner.words.*'      => [ErrorMessages::invalidString()],
            ],
        ], $this->_validator->getMessages());
        $this->assertEquals(
            "<ul>" .
            "<li>Banner >> Url" .
            "<ul><li>Invalid type given. String expected</li></ul></li>" .
            "<li>Banner >> Link Type" .
            "<ul><li>'invalid' is an invalid option</li></ul></li>" .
            "<li>Banner >> Images >> Thumb" .
            "<ul><li>The given file is not an image, 'text/plain' detected</li></ul></li>" .
            "<li>Banner >> Words >> All" .
            "<ul><li>Invalid type given. String expected</li></ul></li>" .
            "</ul>",
            $this->_validator->getMessagesTemplate()
        );
    }

    /** @test */
    public function validateNestedArrayOfArrays()
    {
        $rules = [
            'list.*.number'          => 'integer',
            'list.*.label'           => 'string',
            'list.*.words.*.letters' => 'string',
        ];
        $data = [
            'list' => [
                [
                    'number' => 123,
                    'label'  => 'label',
                    'words'  => [
                        [
                            'letters' => 789,
                        ],
                        [
                            'letters' => 'string',
                        ],
                    ],
                ],
                [
                    'number' => 'string',
                    'label'  => 456,
                ],
                [],
            ],
        ];

        /**
         * Parsed array should look like
         * $data[
         *  'list.*.number' => [
         *      123, 'string', null
         *  ],
         *  'list.*.label' => [
         *      'label', 456, null
         *  ],
         *  'list.*.words.*.letters' => [
         *      789, 'string', null, null
         * ]
         *
         */

        $this->_validator->validate($data, $rules);
        $this->assertArraySubset([
            'errors' => [
                'list.*.number'          => [
                    ErrorMessages::fieldIsInteger('string'),
                    ErrorMessages::nonEmptyField('list.*.number'),
                ],
                'list.*.label'           => [
                    ErrorMessages::invalidString(),
                    ErrorMessages::nonEmptyField('list.*.label'),
                ],
                'list.*.words.*.letters' => [
                    ErrorMessages::invalidString(),
                    ErrorMessages::nonEmptyField('list.*.words.*.letters'),
                    ErrorMessages::nonEmptyField('list.*.words.*.letters'),
                ],
            ],
        ], $this->_validator->getMessages());
    }
}
