<?php
use FKSE\Utilities\OsUtil;

/**
 * Tests OsUtil
 *
 * @author Fridolin Koch <info@fridokoch.de>
 */
class OsUtilTest extends PHPUnit_Framework_TestCase
{
    public function testGetEnv()
    {
        $this->assertNull(OsUtil::getEnv('UT_TEST1', false));
        $this->assertEquals(123, OsUtil::getEnv('UT_TEST2'));
        $this->assertEquals('testingiscool', OsUtil::getEnv('UT_TEST3'));
        $this->assertTrue(OsUtil::getEnv('UT_TEST4'));
        $this->assertNull(OsUtil::getEnv('UT_TEST5'));
    }
}
