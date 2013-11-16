<?php
use FKSE\Utilities\ArrayUtil;

/**
 * Tests ArrayUtil
 *
 * @author Fridolin Koch <fridolin.koch@airmotion.de>
 */
class ArrayUtilTest extends PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function testGetValueByPath()
    {
        $array = [
            'amazon' => [
                'key'       => 'asd',
                'secret'    => 'secret',
                'region'    => 'eu-west-1'
            ],
            'my_application' => [
                'some_string'   => 'asdf',
                'some_int'      => 12435635,
                'abc' => [
                    'def' => [
                        'ghi' => [
                            'jkl' => 'mno'
                        ]
                    ]
                ]
            ]
        ];
        //this with existent value
        $this->assertEquals('asd', ArrayUtil::getValueByPath('amazon', $array)['key']);
        //this with invalid name
        $this->assertNull(ArrayUtil::getValueByPath('asdajsfd', $array));
        $this->assertFalse(ArrayUtil::getValueByPath('asdajsfd', $array, false));
        $this->assertEquals('n/a', ArrayUtil::getValueByPath('asdajsfd', $array, 'n/a'));
        //test without path
        $this->assertEquals('asd', ArrayUtil::getValueByPath('amazon/key', $array));
        //invalid
        $this->assertNull(ArrayUtil::getValueByPath('amazon/bla', $array));
        //test deep
        $this->assertEquals('mno', ArrayUtil::getValueByPath('my_application/abc/def/ghi/jkl', $array));
    }
}
 