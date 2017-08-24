<?php
namespace ElementareTeilchen\Fluid\ViewHelpers\Date;

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * This view helper modifies the given date
 *
 * = Example =
 *
 * <code title="Inline">
 * {et:date.modify(date:someDateVariable, modification:'-1 day')}
 * </code>
 * <output>
 * {1 day before someDateVariable}
 * </output>
 */
class ModifyViewHelper extends AbstractViewHelper {
    /**
     * Initializes the arguments
     */
    public function __construct() {
        $this->registerArgument('date', \DateTime::class, 'DateTime to modify', true);
        $this->registerArgument('modification', 'string', 'The modification string', true);
    }

    /**
     * @return mixed Value at $array[$key]
     */
    public function render() {
        /** @var \DateTime $date */
        $date = clone $this->arguments['date'];
        $date->modify($this->arguments['modification']);
        return $date;
    }
}
