<?php
namespace ElementareTeilchen\Fluid\ViewHelpers\Uri;

use TYPO3\Flow\Resource\Resource;
use TYPO3\Fluid\Core\ViewHelper\Exception\InvalidVariableException;
use TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper as FluidUriResourceViewHelper;

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
     * Render the URI to the resource. The filename is used from child content.
     *
     * @param string $path The location of the resource, can be either a path relative to the Public resource directory of the package or a resource://... URI
     * @param string $package Target package key. If not set, the current package key will be used
     * @param Resource $resource If specified, this resource object is used instead of the path and package information
     * @param boolean $localize Whether resource localization should be attempted or not
     *
     * @return string The absolute URI to the resource appending `?md5=Md5OfResource`
     * @throws InvalidVariableException
     * @api
     */
    public function render($path = null, $package = null, Resource $resource = null, $localize = true)
    {
        $resourceUri = parent::render($path, $package, $resource, $localize);
        return $resourceUri . '?md5=' . md5_file($resourceUri);
    }
}
