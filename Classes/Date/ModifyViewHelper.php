<?php
namespace ElementareTeilchen\Fluid\ViewHelpers\Date;

use Neos\Flow\Annotations as Flow;
use Neos\FluidAdaptor\Core\ViewHelper\AbstractViewHelper;
use Neos\FluidAdaptor\Core\ViewHelper\Exception as ViewHelperException;

/**
 * This view helper modifies the given date, defaults to now
 *
 * = Examples =
 *
 * <code title="Default">
 * <et:date.modify modification="next month" />
 * </code>
 * <output>
 * {new \DateTimeImmutable()->modify('next month')}
 * </output>
 *
 * <code title="String">
 * <et:date.modify date="midnight" modification="next wednesday" />
 * </code>
 * <output>
 * {new \DateTimeImmutable('midnight')->modify('next wednesday')}
 * </output>
 *
 * <code title="Inline">
 * {et:date.modify(date:someDateVariable, modification:'-1 day')}
 * </code>
 * <output>
 * {$someDateVariable->modify('-1 day')}
 * </output>
 */
class ModifyViewHelper extends AbstractViewHelper
{
    /**
     * Initializes the arguments
     *
     * @throws ViewHelperException
     */
    public function __construct()
    {
        $this->registerArgument('date', \DateTimeInterface::class, 'DateTimeInterface to modify');
        $this->registerArgument('modification', 'string', 'The modification string', true);
    }

    /**
     * @return \DateTimeInterface modified date object
     *
     * @throws ViewHelperException
     */
    public function render() : \DateTimeInterface
    {
        $date = $this->arguments['date'];
        if (!$date instanceof \DateTimeInterface) {
            if (\is_string($date)) {
                try {
                    $date = new \DateTimeImmutable($date);
                } catch (\Exception $exception) {
                    throw new ViewHelperException(
                        '"' . $date . '" could not be parsed by \DateTime constructor.',
                        1506085806989,
                        $exception
                    );
                }
            } elseif ($date === null) {
                /** @noinspection PhpUnhandledExceptionInspection */
                /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
                $date = new \DateTimeImmutable();
            } else {
                throw new ViewHelperException(
                    'Date must be an object implementing \DateTimeInterface or of type string. It was of type '
                    . \gettype($date)
                    . '.',
                    1506085810917
                );
            }
        } elseif ($date instanceof \DateTime) {
            $date = clone $date;
        }

        return $date->modify($this->arguments['modification']);
    }
}
