<?php
namespace ElementareTeilchen\Fluid\ViewHelpers\Arrays;

use Neos\Flow\Annotations as Flow;
use Neos\FluidAdaptor\Core\ViewHelper\AbstractViewHelper;
use Neos\FluidAdaptor\Core\ViewHelper\Exception as ViewHelperException;

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
class GetViewHelper extends AbstractViewHelper
{
    /**
     * @var boolean
     */
    protected $escapeOutput = false;

    /**
     * Initializes the arguments
     *
     * @throws ViewHelperException
     */
    public function __construct()
    {
        $this->registerArgument('array', 'array', 'Array to access.', true);
        $this->registerArgument('key', 'string', 'Key where the array should be accessed.', true);
    }

    /**
     * @return mixed Value at $array[$key]
     */
    public function render()
    {
        if (empty($this->arguments['array'])) {
            return '';
        }

        return $this->arguments['array'][$this->arguments['key']];
    }
}
