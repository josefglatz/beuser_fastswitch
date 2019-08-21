<?php
declare(strict_types = 1);

namespace JosefGlatz\BeuserFastswitch\ViewHelpers\Condition;

use TYPO3\CMS\Core\Utility\VersionNumberUtility;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractConditionViewHelper;

/**
 * Class IsCoreVersionViewHelper
 *
 * @TODO: TYPO3_8-7 support removal: ViewHelper can be removed
 */
class IsCoreVersion8ViewHelper extends AbstractConditionViewHelper
{
    /**
     * Main method of this ViewHelper to check if current TYPO3 version matches 8.7
     *
     * @param null $arguments
     * @return bool
     */
    protected static function evaluateCondition($arguments = null): bool
    {
        $constraintVersionMax = 8999999;
        $constraintVersionMin = 8000000;

        return VersionNumberUtility::convertVersionNumberToInteger(TYPO3_version) < $constraintVersionMax
            && VersionNumberUtility::convertVersionNumberToInteger(TYPO3_version) > $constraintVersionMin;
    }

    /**
     * @return mixed
     */
    public function render()
    {
        if (static::evaluateCondition($this->arguments)) {
            return $this->renderThenChild();
        }
        return $this->renderElseChild();
    }
}
