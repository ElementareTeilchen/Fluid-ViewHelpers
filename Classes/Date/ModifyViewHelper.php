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
        $this->registerArgument('date', \DateTimeInterface::class, 'DateTimeInterface to modify', true);
        $this->registerArgument('modification', 'string', 'The modification string', true);
    }

    /**
     * @return \DateTimeInterface modified date object
     */
    public function render() {
        // Can only be \DateTime or \DateTimeImmutable, so we can ignore the warning
        /** @noinspection PhpUndefinedMethodInspection */
        return $this->arguments['date']->modify($this->arguments['modification']);
    }
}
