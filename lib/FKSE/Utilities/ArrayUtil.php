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
            if (!array_key_exists($keys, $search)) {
                return false;
            }
        }

        return true;
    }
} 