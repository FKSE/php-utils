<?php
namespace FKSE\Utilities;

/**
 * Class StringUtil
 *
 * @author Fridolin Koch <info@fridokoch.de>
 */
class StringUtil
{
    /**
     * @param int $length
     *
     * @return string
     */
    public static function generateRandomAlphanumericString($length = 8)
    {
        $string = '';
        for ($i=0; $i < $length; $i++) {
            $char = '';
            switch (rand(0, 2)) {
                //A-Z
                case 0:
                    $char = chr(rand(65, 90));
                    break;
                //a-z
                case 1:
                    $char = chr(rand(97, 122));
                    break;
                //0-9
                case 2:
                    $char = (string) rand(0, 9);
                    break;

            }
            $string .= $char;
        }

        return $string;
    }

    /**
     * Casts a string array to an int array, recursion is enabled by default
     *
     * @param string[] $array
     * @param bool     $recursion
     *
     * @param bool     $unsigned
     *
     * @return int[]
     */
    public static function castStringArrayToIntArray($array, $recursion = true, $unsigned = false)
    {
        foreach ($array as $key => $value) {
            if (is_array($value) && $recursion) {
                $array[$key] = self::castStringArrayToIntArray($value, $recursion, $unsigned);
            } elseif (!is_array($value)) {
                $array[$key] = (int) $value;

                if ($unsigned) {
                    $array[$key] = abs($array[$key]);
                }
            }
        }

        return $array;
    }

    /**
     * Casts a string array to an float array, recursion is enabled by default
     *
     * @param string[] $array
     * @param bool     $recursion
     *
     * @param bool     $unsigned
     *
     * @return int[]
     */
    public static function castArrayToFloatArray($array, $recursion = true, $unsigned = false)
    {
        foreach ($array as $key => $value) {
            if (is_array($value) && $recursion) {
                $array[$key] = self::castArrayToFloatArray($value, $recursion, $unsigned);
            } elseif (!is_array($value)) {
                $array[$key] = (float) $value;

                if ($unsigned) {
                    $array[$key] = abs($array[$key]);
                }
            }
        }

        return $array;
    }

    /**
     * Guesses and casts a string value to an actual data type
     *
     * @param string $value The value to detect and cast
     * @param string $type  The type which was detected
     *
     * @return bool|float|int|null
     */
    public static function guessAndCastValue($value, &$type = '')
    {
        // matched values array
        $matchesTrue = ['true','yes','ja'];
        $matchesFalse = ['false','no','nein'];
        $matchesNull = ['null'];

        if (in_array(strtolower($value), $matchesTrue)) {
            $type = 'boolean';

            return true;
        } elseif (in_array(strtolower($value), $matchesFalse)) {
            $type = 'boolean';

            return false;
        } elseif (in_array(strtolower($value), $matchesNull)) {
            $type = 'null';

            return null;
        } else {
            // check for numerical string and cast if necessary
            if (strstr($value, ',') !== false) {
                $checkStr = str_replace(',', '.', $value);
                if (is_numeric($checkStr)) {
                    $value = $checkStr;
                }
            }
            //numbers
            if (is_numeric($value) && strstr($value, '.') !== false && substr_count($value, '.') == 1) { //float
                $type = 'float';

                return (float) $value;
            } elseif (is_numeric($value)) { //int
                $type = 'integer';

                return (int) $value;
            } else {
                $type = 'string';

                return $value;
            }
        }
    }

    /**
     * Returns the length of the longest string in the given array
     *
     * @param array $string
     *
     * @return int
     */
    public static function maxStrlen(array $string)
    {
        $length = 0;
        foreach ($string as $str) {
            if (strlen($str) > $length) {
                $length = strlen($str);
            }
        }

        return $length;
    }

    /**
     * Trims an array of strings
     *
     * @param array $string
     * @param bool  $recursion
     *
     * @return array
     */
    public static function trimStringArray(array $string, $recursion = true)
    {
        foreach ($string as $key => $value) {
            if (is_array($value) && $recursion) {
                $string[$key] = self::trimStringArray($value);
            } elseif (!is_array($value)) {
                $string[$key] = trim($value);
            }
        }

        return $string;
    }

    /**
     * Validates an email address
     *
     * @param string $address The email address to validate
     * @return bool True if $address is a valid email address
     */
    public static function isValidEmailAddress($address)
    {
        if (preg_match("/[a-z0-9!#$%&'*+\/=?^_`{|}~-]+(?:\\.[a-z0-9!#$%&'*+\/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?/", $address) === 1) {
            return true;
        }

        return false;
    }

    /**
     * Takes an integer value and returns an formatted string. For example 1024 -> 1 KiB
     *
     * @param integer $bytes
     *
     * @throws \OutOfRangeException
     * @return string
     */
    public static function formatBytes($bytes)
    {
        $bytes = (double) $bytes;
        //units
        $units = array('B', 'KiB', 'MiB', 'GiB', 'TiB', 'PiB', 'EiB', 'ZiB', 'YiB');
        $index = (int) (log($bytes)/log(1024));
        //echo $index;
        if ($index > 8) {
            throw new \OutOfRangeException('Input is to large! Maximum supported: 1.236731113465765645724418048 * 10^27 bytes.');
        }
        if ($index === 0) {
            //format
            $output = sprintf('%.2f %s', $bytes, $units[$index]);
        } else {
            //format
            $output = sprintf('%.2f %s', ($bytes/pow(1024, $index)), $units[$index]);
        }

        return $output;
    }

    /**
     * This function works similar to the corresponding .net / Java function.
     *
     * @param string $haystack
     * @param string $needle
     * @return bool True if $haystack ends with $needle or if $needle is a empty string
     */
    public static function endsWith($haystack, $needle)
    {
        if (strlen($haystack) < strlen($needle)) {
            return false;
        }
        $end = substr($haystack, strlen($haystack) - strlen($needle), strlen($needle));

        return $needle === "" || $needle === $end;
    }

    /**
     * This function works similar to the corresponding .net / Java function.
     *
     * @param string $haystack
     * @param string $needle
     * @return bool True if $haystack starts with $needle or if $needle is a empty string
     */
    public static function startsWith($haystack, $needle)
    {
        if (strlen($haystack) < strlen($needle)) {
            return false;
        }
        $start = substr($haystack, 0, strlen($needle));

        return $needle === "" || $needle === $start;
    }
}
