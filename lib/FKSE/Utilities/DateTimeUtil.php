<?php
namespace FKSE\Utilities;

/**
 * Provides advanced functions for date and time stuff
 *
 * @author Fridolin Koch <info@fridokoch.de>
 */
class DateTimeUtil
{
    /**
     * Calculates the seconds between two dates.
     *
     * @param \DateTime $dateA
     * @param \DateTime $dateB
     *
     * @return mixed
     */
    public static function getSecondsBetweenDates(\DateTime $dateA, \DateTime $dateB)
    {
        $diff = $dateA->diff($dateB);

        $sec = $diff->s;
        //add minutes
        $sec += $diff->i * 60;
        //add hours
        $sec += $diff->h * 3600;
        //add days
        $sec += $diff->days * 86400;

        return $sec;
    }

    /**
     * @param int $seconds
     *
     * @return string
     */
    public static function formatSeconds($seconds)
    {
        $format = '';
        $rest = $seconds;
        if ($seconds >= 86400) {
            $format .= floor($seconds/86400).'d ';
            $rest = $seconds%86400;
        }

        if ($rest >= 3600) {
            $format .= floor($rest/3600).'h ';
            $rest = $rest%3600;
        }

        if ($rest >= 60) {
            $format .= floor($rest/60) . 'min ';
            $rest = $rest%60;
        }

        if ($rest > 0) {
            $format .= $rest . 'sec ';
        }

        return trim($format);
    }

    /**
     * Returns an array with english weekdays starting by Monday
     *
     * @return array
     */
    public static function getWeekdays()
    {
        return [
            'Monday',
            'Tuesday',
            'Wednesday',
            'Thursday',
            'Friday',
            'Saturday',
            'Sunday',
        ];
    }

}