<?php
namespace FKSE\Utilities;

/**
 * Class ArrayUtil
 *
 * @author Fridolin Koch <info@fridokoch.de>
 */
class ArrayUtil
{
    /**
     * @param array $keys
     * @param array $search
     * @return bool
     */
    public static function arrayKeysExist(array $keys, array $search)
    {
        foreach ($keys as $key) {
            if (!array_key_exists($key, $search)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Allows accessing an array with a path like construct: /elm1/elm2/elm3 == [elm1][elm2][elm3]
     *
     * @param string $path
     * @param array $array
     * @param null $default
     * @param string $operator
     *
     * @return null
     */
    public static function getValueByPath($path, array $array, $default = null, $operator = '/')
    {
        //handle indexes containing a /
        if (isset($array[$path])) {
            return $array[$path];
        }
        //check if $path contains a /
        $pos = strpos($path, $operator);
        //exit condition
        if ($pos === false) {
            return array_key_exists($path, $array) ? $array[$path] : $default;
        }
        $current = substr($path, 0, $pos);

        if (!array_key_exists($current, $array)) {
            return null;
        }

        //recursion
        return self::getValueByPath(substr($path, $pos+1), $array[$current], $default, $operator);
    }
}
