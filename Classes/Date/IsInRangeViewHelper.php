<?php
namespace ElementareTeilchen\Fluid\ViewHelpers\Date;

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3\Fluid\Core\ViewHelper\Exception as ViewHelperException;

/**
 * This view helper checks if a date is in a range of dates
 *
 * = Examples =
 *
 * <code>
 * <f:if condition="{et:date.isInRange(date:{date}, from:fromDate, until:untilDate)}">
 *     date is in given range
 * </f:if>
 * </code>
 * <output>
 * "date is in given range" (if it is so)
 * </output>
 */
class IsInRangeViewHelper extends AbstractViewHelper {
    /**
     * Initializes the arguments
     */
    public function __construct() {
        $this->registerArgument('date', \DateTimeInterface::class, 'DateTimeInterface to check (defaults to NOW)', false, new \DateTimeImmutable());
        $this->registerArgument('from', \DateTimeInterface::class, 'DateTimeInterface representing the start of the range. If NULL no check is performed.', false);
        $this->registerArgument('until', \DateTimeInterface::class, 'DateTimeInterface representing the end of the range. If NULL no check is performed.', false);
    }

    /**
     * @return boolean
     *
     * @throws ViewHelperException
     */
    public function render() {
        $date = $this->arguments['date'];
        if (!$date instanceof \DateTimeInterface) {
            if (is_string($date)) {
                try {
                    $date = new \DateTimeImmutable($date);
                } catch (\Exception $exception) {
                    throw new ViewHelperException('"' . $date . '" could not be parsed by \DateTime constructor for "date" parameter.', 1508834309084, $exception);
                }
            } elseif (null === $date) {
                $date = new \DateTimeImmutable();
            } else {
                throw new ViewHelperException(
                    '"Date" must be an object implementing \DateTimeInterface or of type string. It was of type ' . gettype($date) . '.',
                    1508834323731
                );
            }
        }

        $from = $this->arguments['from'];
        if (null !== $from && !$from instanceof \DateTimeInterface) {
            if (is_string($from)) {
                try {
                    $from = new \DateTimeImmutable($from);
                } catch (\Exception $exception) {
                    throw new ViewHelperException('"' . $from . '" could not be parsed by \DateTime constructor for "from" parameter.', 1508834391056, $exception);
                }
            } else {
                throw new ViewHelperException(
                    '"From" must be an object implementing \DateTimeInterface or of type string. It was of type ' . gettype($from) . '.',
                    1508834398819
                );
            }
        }

        $until = $this->arguments['until'];
        if (null !== $until && !$until instanceof \DateTimeInterface) {
            if (is_string($until)) {
                try {
                    $until = new \DateTimeImmutable($until);
                } catch (\Exception $exception) {
                    throw new ViewHelperException('"' . $until . '" could not be parsed by \DateTime constructor for "until" parameter.', 1508834393996, $exception);
                }
            } else {
                throw new ViewHelperException(
                    '"Until" must be an object implementing \DateTimeInterface or of type string. It was of type ' . gettype($until) . '.',
                    1508834401041
                );
            }
        }

        if (null !== $from && $date < $from) {
            return false;
        }

        if (null !== $until && $until < $date) {
            return false;
        }

        return true;
    }
}
