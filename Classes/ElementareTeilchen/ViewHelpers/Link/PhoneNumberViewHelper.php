<?php
namespace ElementareTeilchen\ViewHelpers\Link;

/*																					*
 * This script belongs to the TYPO3 Flow package "ElementareTeilchen.ViewHelpers".	*
 *																					*/

use TYPO3\Fluid\Core\ViewHelper\AbstractTagBasedViewHelper;

/**
 * PhoneNumberViewHelper
 *
 * Converts a given phone number to the correctly linked HTML tag.
 * Defaults to the country calling code of Germany (+49).
 *
 * Usage:
 * {et:link.phoneNumber(number: '1234', text: 'call me')}
 *
 * Output:
 * <a href='tel:+491234'>call me</a>
 *
 */
class PhoneNumberViewHelper extends AbstractTagBasedViewHelper {

    /**
     * @param string $phoneNumber The phone number to link
     * @return string The linked phone number
     */
    public function render($phoneNumber) {
        $strippedNumber = preg_replace('/([^\d\+])/', '', $phoneNumber);
        $strippedNumber = preg_replace('/.*(?=\+)/', '', $strippedNumber);
        $strippedNumber = preg_replace('/^00/', '+', $strippedNumber);

        if(strpos($strippedNumber,'+') === false) {
            if(strpos($strippedNumber,'0') === 0){
                $strippedNumber = substr($strippedNumber,1);
            }
            $internationalNumber = '+49' . $strippedNumber;
        } else {
            if(strpos($strippedNumber,'0') === 3){
                $internationalNumber = substr($strippedNumber,0,3) . substr($strippedNumber,4);
            } else {
                $internationalNumber = $strippedNumber;
            }
        }

        $this->tag->setTagName('a');
        $this->tag->addAttribute('href','tel:' . $internationalNumber);
        $this->tag->setContent($this->renderChildren());

		return $this->tag->render();

    }

}