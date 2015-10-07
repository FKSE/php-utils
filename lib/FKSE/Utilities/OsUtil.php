<?php
namespace FKSE\Utilities;

use FKSE\Utilities\StringUtil;

/**
 * Provides advanced functions for OS related actions
 *
 * @author Fridolin Koch <info@fridokoch.de>
 */
class OsUtil {
    /**
     * @param $name
     * @param null $default
     * @return bool|float|int|null
     */
    public static function getEnv($name, $default = null) {
        $val = getenv($name);
        if ($val === false) {
            return $default;
        }
        return StringUtil::guessAndCastValue($val);
    }
}
