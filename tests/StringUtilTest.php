<?php

use FKSE\Utilities\StringUtil;

/**
 * Class StringUtilTest
 *
 * @author Fridolin Koch <fridolin.koch@airmotion.de>
 */
class StringUtilTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test StringUtil::generateRandomAlphanumericString
     */
    public function testGenerateRandomAlphanumericString()
    {
        for ($i = 0; $i < 10; $i++) {
            $length = ($i+1)*5*rand(1, 4);
            //generate random string
            $data = StringUtil::generateRandomAlphanumericString($length);
            //test length
            $this->assertEquals($length, strlen($data));
            //test content
            $this->assertRegExp('/[a-zA-z0-9]+/', $data);
        }
    }

    /**
     * @return array
     */
    public static function guessAndCastValueDataProvider()
    {
        return [
            //bool:true
            ['true', true, 'boolean'],
            ['TrUe', true, 'boolean'],
            ['yes', true, 'boolean'],
            ['YES', true, 'boolean'],
            ['ja', true, 'boolean'],
            ['Ja', true, 'boolean'],
            //bool:false
            ['false', false, 'boolean'],
            ['FaLsE', false, 'boolean'],
            ['no', false, 'boolean'],
            ['NO', false, 'boolean'],
            ['No', false, 'boolean'],
            ['Nein', false, 'boolean'],
            ['NEIN', false, 'boolean'],
            ['NeIn', false, 'boolean'],
            //null
            ['null', null, 'null'],
            ['NULL', null, 'null'],
            ['NuLl', null, 'null'],
            //float
            ['12.13', 12.13, 'float'],
            ['12,12', 12.12, 'float'],
            ['12,312334456789765434567876543234567', 12.312334456789765434567876543234567, 'float'],
            ['1123456787654345678765434567892,312334456789765434567876543234567', 1123456787654345678765434567892.312334456789765434567876543234567, 'float'],
            //int
            ['11', 11, 'integer'],
            //string
            ['12,12,12', '12,12,12', 'string'],
            ['12.12.12', '12.12.12', 'string'],
            ['testString', 'testString', 'string']
        ];
    }

    /**
     * @param string $value
     * @param mixed  $expectedValue
     * @param string $expectedType
     *
     * @internal     param mixed $expected
     *
     * @dataProvider guessAndCastValueDataProvider
     */
    public function testGuessAndCastValue($value, $expectedValue, $expectedType)
    {
        $this->assertSame($expectedValue, StringUtil::guessAndCastValue($value, $type));
        $this->assertSame($expectedType, $type);
    }

    /**
     * Test StringUtil::maxStrlen
     */
    public function testMaxStrlen()
    {
        $result = StringUtil::maxStrlen([
            'a',
            'ab',
            'abc',
            'abcd',
            'abcde',
            'abcdef',
            'abcdefg',
            'abcdefgh',
            'abcdefghi',
            'abcdefghij',
        ]);
        //test with normal array
        $this->assertEquals(10, $result);
        //test with empty array
        $this->assertEquals(0, StringUtil::maxStrlen([]));
    }

    /**
     * StringUtil::trimStringArray
     */
    public function testTrimStringArray()
    {
        //the test array
        $testData = [
            'sdfsd',
            '  sdfs    d      ',
            's  sdfs    d      sdfsd',
            '  s  sdfs    d      sdfsd',
            '  s  sdfs    d      sdfsd ',
            [
                'as ',
                'as asdf    ',
                '4358934    ',
                '   4358934fd ',
                '   4358934',
                [
                    'adsf . ',
                    ' sdfsd ',
                ]
            ]
        ];
        //the expected result for a call with recursion enabled
        $expectedRecursion = [
            'sdfsd',
            'sdfs    d',
            's  sdfs    d      sdfsd',
            's  sdfs    d      sdfsd',
            's  sdfs    d      sdfsd',
            [
                'as',
                'as asdf',
                '4358934',
                '4358934fd',
                '4358934',
                [
                    'adsf .',
                    'sdfsd',
                ]
            ]
        ];
        //the expected result for a linear call
        $expectedLinear = [
            'sdfsd',
            'sdfs    d',
            's  sdfs    d      sdfsd',
            's  sdfs    d      sdfsd',
            's  sdfs    d      sdfsd',
            [
                'as ',
                'as asdf    ',
                '4358934    ',
                '   4358934fd ',
                '   4358934',
                [
                    'adsf . ',
                    ' sdfsd ',
                ]
            ]
        ];
        //trimStringArray with recursion enabled
        $this->assertEquals($expectedRecursion, StringUtil::trimStringArray($testData, true));
        //trimStringArray with recursion disabled
        $this->assertEquals($expectedLinear, StringUtil::trimStringArray($testData, false));
    }

    /**
     * StringUtil::castArrayToFloatArray
     */
    public function testCastArrayToFloatArray()
    {
        $testData = [
            '32',
            '8,9',
            '-9.10',
            '1111.111323534',
            '-234.435',
            [
                'asdf',
                '234',
                '4358934',
                [
                    '1.3',
                    '-3.4',
                ]
            ]
        ];
        //the expected result for a call with recursion enabled
        $expectedRecursion = [
            32,
            8,
            -9.10,
            1111.111323534,
            -234.435,
            [
                0,
                234,
                4358934,
                [
                    1.3,
                    -3.4,
                ]
            ]
        ];
        //the expected result for a linear call
        $expectedLinear = [
            32,
            8,
            -9.10,
            1111.111323534,
            -234.435,
            [
                'asdf',
                '234',
                '4358934',
                [
                    '1.3',
                    '-3.4',
                ]
            ]
        ];
        //the expected result for a call with recursion and unsigned enabled
        $expectedRecursionAbs = [
            32,
            8,
            9.10,
            1111.111323534,
            234.435,
            [
                0,
                234,
                4358934,
                [
                    1.3,
                    3.4,
                ]
            ]
        ];
        //the expected result for a linear call
        $expectedLinearAbs = [
            32,
            8,
            9.10,
            1111.111323534,
            234.435,
            [
                'asdf',
                '234',
                '4358934',
                [
                    '1.3',
                    '-3.4',
                ]
            ]
        ];

        //trimStringArray with recursion enabled
        $this->assertEquals($expectedRecursion, StringUtil::castArrayToFloatArray($testData, true));
        //trimStringArray with recursion disabled
        $this->assertEquals($expectedLinear, StringUtil::castArrayToFloatArray($testData, false));
        //trimStringArray with recursion and unsigned enabled
        $this->assertEquals($expectedRecursionAbs, StringUtil::castArrayToFloatArray($testData, true, true));
        //trimStringArray with recursion disabled
        $this->assertEquals($expectedLinearAbs, StringUtil::castArrayToFloatArray($testData, false, true));
    }

    /**
     * StringUtil::castStringArrayToIntArray
     */
    public function testCastStringArrayToIntArray()
    {
        $testData = [
            '32',
            '8,9',
            '-9.10',
            '1111.111323534',
            '-234.435',
            [
                'asdf',
                '234',
                '4358934',
                [
                    '1.3',
                    '-3.4',
                ]
            ]
        ];
        //the expected result for a call with recursion enabled
        $expectedRecursion = [
            32,
            8,
            -9,
            1111,
            -234,
            [
                0,
                234,
                4358934,
                [
                    1,
                    -3,
                ]
            ]
        ];
        //the expected result for a linear call
        $expectedLinear = [
            32,
            8,
            -9,
            1111,
            -234,
            [
                'asdf',
                '234',
                '4358934',
                [
                    '1.3',
                    '-3.4',
                ]
            ]
        ];
        //the expected result for a call with recursion and unsigned enabled
        $expectedRecursionAbs = [
            32,
            8,
            9,
            1111,
            234,
            [
                0,
                234,
                4358934,
                [
                    1,
                    3,
                ]
            ]
        ];
        //the expected result for a linear call
        $expectedLinearAbs = [
            32,
            8,
            9,
            1111,
            234,
            [
                'asdf',
                '234',
                '4358934',
                [
                    '1.3',
                    '-3.4',
                ]
            ]
        ];

        //trimStringArray with recursion enabled
        $this->assertEquals($expectedRecursion, StringUtil::castStringArrayToIntArray($testData, true));
        //trimStringArray with recursion disabled
        $this->assertEquals($expectedLinear, StringUtil::castStringArrayToIntArray($testData, false));
        //trimStringArray with recursion and unsigned enabled
        $this->assertEquals($expectedRecursionAbs, StringUtil::castStringArrayToIntArray($testData, true, true));
        //trimStringArray with recursion disabled
        $this->assertEquals($expectedLinearAbs, StringUtil::castStringArrayToIntArray($testData, false, true));
    }

    /**
     * @return array
     */
    public static function isEmailAddressDataProvider()
    {
        return [
            ['test@arof.de', true],
            ['gan$ster@aasedfrof.gov', true],
            ['üüüü@aasedfrof.gov', false],
            ['asd@aasedfrof', false],
            ['asd?=@aasedfrof.org', true],
        ];
    }

    /**
     * @param string $address
     * @param bool   $expected
     *
     * @dataProvider isEmailAddressDataProvider
     */
    public function testIsValidEmailAddress($address, $expected)
    {
        $this->assertSame($expected, StringUtil::isValidEmailAddress($address), sprintf('Test failed for address %s', $address));
    }
}
