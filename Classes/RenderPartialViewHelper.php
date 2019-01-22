<?php
namespace ElementareTeilchen\Fluid\ViewHelpers;

use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\ViewHelpers\RenderViewHelper as FluidRenderViewHelper;

/**
 * A ViewHelper to render a specified section in a partial (defaulting to "Main") or a delegate ParsedTemplateInterface
 * implementation.
 *
 * If the partial contains long Fluid tags, PHPs PCRE JIT compiler must be disabled via the "disableJIT" argument.
 * @see https://bugs.php.net/bug.php?id=70110
 *
 * = Examples =
 *
 * <code title="Rendering partials">
 * <et:render partial="SomePartial" arguments="{foo: someVariable}"/>
 * </code>
 * <output>
 * The content of the section "Main" in partial "SomePartial".
 * The content of the variable {someVariable} will be available in the partial as {foo}
 * </output>
 */
class RenderPartialViewHelper extends FluidRenderViewHelper
{
    /**
     * @inheritdoc
     */
    public function initializeArguments() : void
    {
        parent::initializeArguments();

        $sectionArgumentDefinition = $this->argumentDefinitions['section'];
        $this->overrideArgument(
            'section',
            $sectionArgumentDefinition->getType(),
            'Section in partial to render',
            false,
            'Main'
        );
        $partialArgumentDefinition = $this->argumentDefinitions['partial'];
        $this->overrideArgument(
            'partial',
            $partialArgumentDefinition->getType(),
            $partialArgumentDefinition->getDescription(),
            true
        );
        $this->registerArgument(
            'disableJIT',
            'bool',
            'If TRUE, PCRE JIT compiler will be disabled to allow long Fluid tags. '
                . 'See https://bugs.php.net/bug.php?id=70110 for bug report.',
            false,
            false
        );
    }

    /**
     * @param array $arguments
     * @param \Closure $renderChildrenClosure
     * @param RenderingContextInterface $renderingContext
     *
     * @return mixed
     */
    public static function renderStatic(
        array $arguments,
        \Closure $renderChildrenClosure,
        RenderingContextInterface $renderingContext
    ) {
        if ($arguments['disableJIT']) {
            $jitBefore = \ini_get('pcre.jit');
            \ini_set('pcre.jit', 0);
        }
        $content = parent::renderStatic($arguments, $renderChildrenClosure, $renderingContext);
        if (isset($jitBefore)) {
            \ini_set('pcre.jit', $jitBefore);
        }
        return $content;
    }
}
