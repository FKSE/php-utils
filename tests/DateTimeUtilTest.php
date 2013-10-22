<?php

use FKSE\Utilities\DateTimeUtil;
/**
 * Class DateTimeUtilTest
 *
 * @author Fridolin Koch <fridolin.koch@airmotion.de>
 */
class DateTimeUtilTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @param string $dateA
     * @param string $dateB
     * @param int    $expected
     *
     * @dataProvider getSecondsBetweenDatesDataProvider
     */
    public function testGetSecondsBetweenDates($dateA, $dateB, $expected)
    {
        $this->assertEquals($expected, DateTimeUtil::getSecondsBetweenDates(
            \DateTime::createFromFormat('d.m.Y - H:i:s', $dateA),
            \DateTime::createFromFormat('d.m.Y - H:i:s', $dateB)
        ));
    }

    /**
     * Provides data for the testGetSecondsBetweenDates method
     *
     * @return array
     */
    public static function getSecondsBetweenDatesDataProvider()
    {
        return [
            ['01.04.2013 - 12:00:00', '01.04.2013 - 12:00:00', 0],
            ['26.01.1992 - 00:21:10', '25.04.2013 - 10:52:53', 670501903],
            ['13.07.1967 - 08:47:11', '25.04.2013 - 10:57:51', 1444875040],
        ];
    }

    /**
     * @param int    $seconds
     * @param string $expected
     *
     * @dataProvider testFormatSecondsDataProvider
     */
    public function testFormatSeconds($seconds, $expected)
    {
        $this->assertEquals($expected, DateTimeUtil::formatSeconds($seconds));
    }

    /**
     * @return array
     */
    public static function testFormatSecondsDataProvider()
    {
        return [
            [59, '59sec'],
            [60, '1min'],
            [3600, '1h'],
            [3620, '1h 20sec'],
            [3670, '1h 1min 10sec'],
            [86741, '1d 5min 41sec'],
            [90341, '1d 1h 5min 41sec']
        ];
    }

    /**
     * tests DateTimeUtil::getWeekdays
     */
    public function testGetWeekdays()
    {
        $this->assertEquals([
                'Monday',
                'Tuesday',
                'Wednesday',
                'Thursday',
                'Friday',
                'Saturday',
                'Sunday',
            ], DateTimeUtil::getWeekdays());
    }

}