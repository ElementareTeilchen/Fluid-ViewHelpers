<?php
namespace ElementareTeilchen\Fluid\ViewHelpers\Format;

use Neos\FluidAdaptor\Core\ViewHelper\Exception as ViewHelperException;
use Neos\FluidAdaptor\ViewHelpers\Format\DateViewHelper as FluidDateViewHelper;

class DateViewHelper extends FluidDateViewHelper
{
    /**
     * Render the supplied DateTime object as a formatted date.
     *
     * @param mixed $date either a \DateTime object or a string that is accepted by \DateTime constructor
     * @param string $format Format String which is taken to format the Date/Time if none of the locale options are
     *     set.
     * @param string $localeFormatType Whether to format (according to locale set in $forceLocale) date, time or
     *     datetime. Must be one of Neos\Flow\I18n\Cldr\Reader\DatesReader::FORMAT_TYPE_*'s constants.
     * @param string $localeFormatLength Format length if locale set in $forceLocale. Must be one of
     *     Neos\Flow\I18n\Cldr\Reader\DatesReader::FORMAT_LENGTH_*'s constants.
     * @param string $cldrFormat Format string in CLDR format (see http://cldr.unicode.org/translation/date-time)
     * @param string $timezone
     *
     * @return string
     *
     * @throws ViewHelperException
     *
     * @api
     */
    public function render(
        $date = null,
        $format = 'Y-m-d',
        $localeFormatType = null,
        $localeFormatLength = null,
        $cldrFormat = null,
        $timezone = null
    ) : string {
        if ($date === null) {
            $date = $this->renderChildren();
            if ($date === null) {
                return '';
            }
        }

        if (!$date instanceof \DateTimeInterface) {
            try {
                $date = new \DateTime($date);
            } catch (\Exception $exception) {
                throw new ViewHelperException(
                    '"' . $date . '" could not be parsed by \DateTime constructor.',
                    1241722579,
                    $exception
                );
            }
        }

        if ($timezone === null) {
            $timezone = \date_default_timezone_get();
        }

        return parent::render(
            $date->setTimezone(new \DateTimeZone($timezone)),
            $format,
            $localeFormatType,
            $localeFormatLength,
            $cldrFormat
        );
    }
}
