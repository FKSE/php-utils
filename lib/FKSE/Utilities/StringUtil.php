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
                    $char = chr(rand(97, 122));
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
     * @param string $value
     *
     * @return bool|float|int|null
     */
    public static function guessAndCastValue($value)
    {
        // matched values array
        $matchesTrue = ['true','yes','ja'];
        $matchesFalse = ['false','no','nein'];
        $matchesNull = ['null'];

        if (in_array(strtolower($value), $matchesTrue)) {
            return true;
        } elseif (in_array(strtolower($value), $matchesFalse)) {
            return false;
        } elseif (in_array(strtolower($value), $matchesNull)) {
            return null;
        } else {
            // check for numerical string and cast if necessary
            if (strstr($value, ',') !== false) {
                $checkStr = str_replace(',', '.', $value);

                if (is_numeric($checkStr)) {
                    $value = $checkStr;
                }
            }

            if (is_numeric($value) && strstr($value, '.') !== false && substr_count($value, '.') == 1) {
                return (float) $value;
            } elseif (is_numeric($value)) {
                return (int) $value;
            } else {
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
}