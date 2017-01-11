<?php
namespace ElementareTeilchen\Fluid\ViewHelpers\Arrays;

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * This view helper gets the value of the given array at the given key
 *
 * = Examples =
 *
 * <code title="Inline">
 * <f:alias map="{array:{foo:'bar'}, key:'foo'}">
 * {et:arrays.get(array:array, key:key)}
 * </f:alias>
 * </code>
 * <output>
 * bar
 * </output>
 *
 * <code title="Tag">
 * <f:alias map="{array:{foo:'bar'}, key:'foo'}">
 * <et:arrays.get array="{array}" key="{key}" />
 * </f:alias>
 * </code>
 * <output>
 * bar
 * </output>
 */
class GetViewHelper extends AbstractViewHelper {
    /**
     * Initializes the arguments
     */
    public function __construct() {
        $this->registerArgument('array', 'array', 'Array to access.', true);
        $this->registerArgument('key', 'string', 'Key where the array should be accessed.', true);
    }

    /**
     * @return mixed Value at $array[$key]
     */
    public function render() {
        if (empty($this->arguments['array'])) {
            return '';
        } else {
            return $this->arguments['array'][$this->arguments['key']];
        }
    }
}
