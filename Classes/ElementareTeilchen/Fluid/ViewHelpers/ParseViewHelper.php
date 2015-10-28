<?php
namespace ElementareTeilchen\Fluid\ViewHelpers;

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * This view helper parses the given Fluid string.
 *
 * = Example =
 * == Inline ==
 * #Input
 * {et:parse(string:'text containing a {variable} variable')}
 * #Output
 * text containing a value variable
 * == Tag ==
 * #Input
 * <et:parse>text containing a {variable} variable</et:parse>
 * #Output
 * text containing a value variable
 */
class ParseViewHelper extends AbstractViewHelper {

	/**
	 * @var \TYPO3\Fluid\Core\Parser\TemplateParser
	 * @Flow\Inject
	 */
	protected $templateParser;

	/**
	 * Initializes the "string" argument
	 */
	public function __construct() {
		$this->registerArgument('string', 'string', 'String to parse.');
	}

	/**
	 * @param string $string string to parse
	 *
	 * @return string parsed string
	 */
	public function render($string = NULL) {
		if ($string === NULL) {
			$string = $this->renderChildren();
		}
		return $this->templateParser->parse($string)->render($this->renderingContext);
	}
}
