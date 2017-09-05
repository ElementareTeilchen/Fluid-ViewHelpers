<?php
namespace ElementareTeilchen\Fluid\ViewHelpers\Uri;

use Neos\FluidAdaptor\Core\ViewHelper\Exception\InvalidVariableException;
use Neos\FluidAdaptor\ViewHelpers\Uri\ResourceViewHelper as FluidUriResourceViewHelper;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;

/**
 * A view helper for creating URIs to resources appending ``?md5=Md5OfResource`.
 *
 * = Examples =
 *
 * <code title="Defaults">
 * <link href="{et:uri.resource(path: 'CSS/Stylesheet.css')}" rel="stylesheet" />
 * </code>
 * <output>
 * <link href="http://yourdomain.tld/_Resources/Static/YourPackage/CSS/Stylesheet.css?md5=Md5OfResource" rel="stylesheet" />
 * (depending on current package)
 * </output>
 *
 * <code title="Other package resource">
 * {et:uri.resource(path: 'gfx/SomeImage.png', package: 'DifferentPackage')}
 * </code>
 * <output>
 * http://yourdomain.tld/_Resources/Static/DifferentPackage/gfx/SomeImage.png?md5=Md5OfResource
 * (depending on domain)
 * </output>
 *
 * <code title="Resource URI">
 * {et:uri.resource(path: 'resource://DifferentPackage/Public/gfx/SomeImage.png')}
 * </code>
 * <output>
 * http://yourdomain.tld/_Resources/Static/DifferentPackage/gfx/SomeImage.png?md5=Md5OfResource
 * (depending on domain)
 * </output>
 *
 * <code title="Resource object">
 * <img src="{et:uri.resource(resource: myImage.resource)}" />
 * </code>
 * <output>
 * <img src="http://yourdomain.tld/_Resources/Persistent/69e73da3ce0ad08c717b7b9f1c759182d6650944.jpg?md5=Md5OfResource" />
 * (depending on your resource object)
 * </output>
 *
 * @api
 */
class ResourceViewHelper extends FluidUriResourceViewHelper
{
    /**
     * @param array $arguments
     * @param \Closure $renderChildrenClosure
     * @param RenderingContextInterface $renderingContext
     *
     * @return string
     *
     * @throws InvalidVariableException
     */
    public static function renderStatic(array $arguments, \Closure $renderChildrenClosure, RenderingContextInterface $renderingContext)
    {
        $resourceUri = parent::renderStatic($arguments, $renderChildrenClosure, $renderingContext);

        return $resourceUri . '?md5=' . md5_file($resourceUri);
    }
}
