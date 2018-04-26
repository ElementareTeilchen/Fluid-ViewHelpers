<?php
namespace ElementareTeilchen\Fluid\ViewHelpers\Uri;

use Neos\Flow\ResourceManagement\PersistentResource;
use Neos\FluidAdaptor\Core\Rendering\RenderingContext;
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
     * @inheritDoc
     */
    public function initializeArguments()
    {
        parent::initializeArguments();
        /** @noinspection PhpUnhandledExceptionInspection */
        $this->registerArgument(
            'absolute',
            'bool',
            'Whether the Uri should be absolute or not.',
            false,
            true
        );
    }

    /**
     * Render the URI to the resource. The filename is used from child content.
     *
     * @return string The absolute URI to the resource appended with its md5 value
     *
     * @api
     */
    public function render() : string
    {
        return static::renderStatic($this->arguments, $this->buildRenderChildrenClosure(), $this->renderingContext);
    }

    /**
     * @param array $arguments
     * @param \Closure $renderChildrenClosure
     * @param RenderingContextInterface $renderingContext
     *
     * @return string
     */
    public static function renderStatic(
        array $arguments,
        \Closure $renderChildrenClosure,
        RenderingContextInterface $renderingContext
    ) : string {
        /** @var RenderingContext $renderingContext */
        $absoluteResourceUri = parent::renderStatic($arguments, $renderChildrenClosure, $renderingContext);

        $resourceUri = $arguments['absolute']
            ? $absoluteResourceUri
            : \substr($absoluteResourceUri, \strpos($absoluteResourceUri, '/', 8))
        ;

        $resource = $arguments['resource'] ?? null;
        if ($resource === null) {
            $path = $arguments['path'];
            if (\strpos($path, 'resource://') === false) {
                $package = $arguments['package']
                    ?? $renderingContext->getControllerContext()->getRequest()->getControllerPackageKey()
                ;
                $path = 'resource://' . $package . '/Public/' . $path;
            }
            $md5 = \md5_file($path);
        } else {
            /** @var PersistentResource $md5 */
            $md5 = \md5_file($resource->md5);
        }

        return $resourceUri . '?md5=' . $md5;
    }
}
