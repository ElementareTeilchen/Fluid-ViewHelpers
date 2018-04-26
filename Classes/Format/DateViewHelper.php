<?php
namespace ElementareTeilchen\Fluid\ViewHelpers\Format;

use Neos\FluidAdaptor\Core\ViewHelper\Exception as ViewHelperException;
use Neos\FluidAdaptor\ViewHelpers\Format\DateViewHelper as FluidDateViewHelper;

class DateViewHelper extends FluidDateViewHelper
{
    /**
     * @inheritDoc
     */
    public function initializeArguments()
    {
        parent::initializeArguments();

        $this->registerArgument('timezone', 'string', 'Timezone like "Europe/Berlin"');
    }

    /**
     * @inheritdoc
     */
    public function render() : string
    {
        $date = $this->arguments['date'];
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

        $timezone = $this->arguments['timezone'] ?? \date_default_timezone_get();

        $this->arguments['date'] = $date->setTimezone(new \DateTimeZone($timezone));

        return parent::render();
    }
}
