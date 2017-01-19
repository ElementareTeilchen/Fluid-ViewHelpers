<?php
namespace ElementareTeilchen\Fluid\ViewHelpers\Link;

use Neos\FluidAdaptor\Core\ViewHelper\AbstractTagBasedViewHelper;

/**
 * PhoneNumberViewHelper
 *
 * Converts a given phone number to the correctly linked HTML tag.
 * Defaults to the country calling code of Germany (+49).
 *
 * = Examples =
 *
 * <code title="Tag">
 * <et:link.phoneNumber phoneNumber='1234' countryCode='49'>call me</et:link.phoneNumber>
 * </code>
 * or
 * <code title="Inline">
 * {et:link.phoneNumber(phoneNumber:'1234', countryCode:'49', content:'call me')}
 * </code>
 *
 * <output>
 * <a href='tel:+491234'>call me</a>
 * </output>
 */
class PhoneNumberViewHelper extends AbstractTagBasedViewHelper
{
    /**
     * Initializes the "string" argument
     */
    public function __construct()
    {
        parent::__construct();
        $this->registerUniversalTagAttributes();
        $this->registerArgument('phoneNumber', 'string', 'The phone number to link', true);
        $this->registerArgument('countryCode', 'string', 'The country calling code to use (defaults to "49")', false, '49');
        $this->registerArgument('content', 'string', 'The content of the resulting tag');
    }

    /**
     * @return string The linked phone number
     */
    public function render()
    {
        // strip everything but numbers and "+"
        $strippedNumber = preg_replace('/[^\d\+]/', '', $this->arguments['phoneNumber']);
        // strip every "+" not at the beginning
        $strippedNumber = preg_replace('/(?<=.)\+/', '', $strippedNumber);
        // leading "00" to "+"
        $strippedNumber = preg_replace('/^00/', '+', $strippedNumber);
        /* now $strippedNumber should look like
         * 123456789 or
         * 0123456789 or
         * +49123456789 or
         * +490123456789
         * the last one is invalid, but possible due to input like
         * +49 (0) 123 / 456 789
         */

        if (strpos($strippedNumber, '+') === false) {
            // no country calling code
            if (strpos($strippedNumber, '0') === 0) {
                // remove leading "0"
                $strippedNumber = substr($strippedNumber, 1);
            }
            $internationalNumber = '+' . $this->arguments['countryCode'] . $strippedNumber;
        } else {
            // country calling code already present
            if (strpos($strippedNumber, '0') === 3) {
                // somehow the leading "0" for national numbers got mixed in
                $internationalNumber = substr($strippedNumber, 0, 3) . substr($strippedNumber, 4);
            } else {
                $internationalNumber = $strippedNumber;
            }
        }

        $this->tag->setTagName('a');
        $this->tag->addAttribute('href', 'tel:' . $internationalNumber);
        if ($this->arguments['content'] === null) {
            $this->tag->setContent($this->renderChildren());
        } else {
            $this->tag->setContent($this->arguments['content']);
        }

        return $this->tag->render();
    }
}
