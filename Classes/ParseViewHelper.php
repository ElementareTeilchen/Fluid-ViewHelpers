<?php
namespace ElementareTeilchen\Fluid\ViewHelpers;

use Neos\Flow\Annotations as Flow;
use Neos\FluidAdaptor\Core\ViewHelper\AbstractViewHelper;

/**
 * This view helper parses the given Fluid string.
 *
 * = Examples =
 *
 * <code title="Inline">
 * {et:parse(string:'text containing a {variable} variable')}
 * </code>
 * <output>
 * text containing a value variable
 * </output>
 *
 * <code title="Tag">
 * <et:parse>text containing a {variable} variable</et:parse>
 * </code>
 * <output>
 * text containing a value variable
 * </output>
 */
class ParseViewHelper extends AbstractViewHelper
{
    /**
     * @var boolean
     */
    protected $escapeOutput = false;

    /**
     * Initializes the "string" argument
     */
    public function __construct()
    {
        $this->registerArgument('string', 'string', 'String to parse.');
    }

    /**
     * @return mixed parsed string
     */
    public function render()
    {
        $string = $this->hasArgument('string') ? $this->arguments['string'] : $this->renderChildren();

        if ($string === null) {
            return '';
        }

        if (!\is_string($string)) {
            $string = (string)$string;
        }

        $templateParser = $this->renderingContext->getTemplateParser();

        return $templateParser->parse('{escapingEnabled=false}' . $string)->render($this->renderingContext);
    }
}
