<?php
declare(strict_types = 1);

namespace JosefGlatz\BeuserFastswitch\Service;

use TYPO3\CMS\Core\Utility\VersionNumberUtility;

class VersionService
{
    public static function isVersion8(): bool
    {
        $constraintVersionMax = 8999999;
        $constraintVersionMin = 8000000;

        return static::evaluateCondition($constraintVersionMin, $constraintVersionMax);

    }

    public static function isVersion10(): bool
    {
        $constraintVersionMax = 10999999;
        $constraintVersionMin = 10000000;

        return static::evaluateCondition($constraintVersionMin, $constraintVersionMax);

    }

    /**
     * @param int $min
     * @param int $max
     * @return bool
     */
    protected static function evaluateCondition(int $min, int $max): bool
    {
        return VersionNumberUtility::convertVersionNumberToInteger(TYPO3_version) <= $max
            && VersionNumberUtility::convertVersionNumberToInteger(TYPO3_version) >= $min;
    }
}
