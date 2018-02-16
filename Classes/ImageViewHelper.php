<?php
namespace ElementareTeilchen\Fluid\ViewHelpers;

use Neos\Flow\Annotations as Flow;
use Neos\Media\Domain\Model\ImageInterface;
use Neos\Media\Domain\Model\ThumbnailConfiguration;
use Neos\Media\ViewHelpers\ImageViewHelper as MediaImageViewHelper;

/**
 * Renders an <img> HTML tag from a given Neos.Media image instance
 *
 * = Examples =
 *
 * <code title="Rendering an image as-is">
 * <et:image image="{imageObject}" alt="a sample image without scaling" />
 * </code>
 * <output>
 * (depending on the image, no scaling applied)
 * <img src="_Resources/Persistent/b29[...]95d.jpeg" width="120" height="180" alt="a sample image without scaling" />
 * </output>
 *
 *
 * <code title="Rendering an image with scaling at a given width only">
 * <et:image image="{imageObject}" maximumWidth="80" alt="sample" />
 * </code>
 * <output>
 * (depending on the image; scaled down to a maximum width of 80 pixels, keeping the aspect ratio)
 * <img src="_Resources/Persistent/b29[...]95d.jpeg" width="80" height="120" alt="sample" />
 * </output>
 *
 *
 * <code title="Rendering an image with scaling at given width and height, keeping aspect ratio">
 * <et:image image="{imageObject}" maximumWidth="80" maximumHeight="80" alt="sample" />
 * </code>
 * <output>
 * (depending on the image; scaled down to a maximum width and height of 80 pixels, keeping the aspect ratio)
 * <img src="_Resources/Persistent/b29[...]95d.jpeg" width="53" height="80" alt="sample" />
 * </output>
 *
 *
 * <code title="Rendering an image with crop-scaling at given width and height">
 * <et:image image="{imageObject}" maximumWidth="80" maximumHeight="80" allowCropping="true" alt="sample" />
 * </code>
 * <output>
 * (depending on the image; scaled down to a width and height of 80 pixels, possibly changing aspect ratio)
 * <img src="_Resources/Persistent/b29[...]95d.jpeg" width="80" height="80" alt="sample" />
 * </output>
 *
 * <code title="Rendering an image with allowed up-scaling at given width and height">
 * <et:image image="{imageObject}" maximumWidth="5000" allowUpScaling="true" alt="sample" />
 * </code>
 * <output>
 * (depending on the image; scaled up or down to a width 5000 pixels, keeping aspect ratio)
 * <img src="_Resources/Persistent/b29[...]95d.jpeg" width="80" height="80" alt="sample" />
 * </output>
 *
 */
class ImageViewHelper extends MediaImageViewHelper
{
    /**
     * @Flow\InjectConfiguration("Image")
     * @var array
     */
    protected $settings;

    /**
     * Renders an HTML img tag with a thumbnail image, created from a given image.
     *
     * @param ImageInterface $image The image to be rendered as an image
     * @param int $width Desired width of the image
     * @param int $maximumWidth Desired maximum width of the image
     * @param int $height Desired height of the image
     * @param int $maximumHeight Desired maximum height of the image
     * @param bool $allowCropping Whether the image should be cropped if the given sizes would hurt the aspect ratio
     * @param bool $allowUpScaling Whether the resulting image size might exceed the size of the original image
     * @param bool $async Return asynchronous image URI in case the requested image does not exist already
     * @param string $preset Preset used to determine image configuration
     * @param array $srcsetWidths The width of the images referenced in the srcset attribute
     * @param string $srcsetAttribute This string is prepended to the srcset attribute
     *
     * @return string an <img...> html tag
     */
    public function render(ImageInterface $image = null, $width = null, $maximumWidth = null, $height = null, $maximumHeight = null, $allowCropping = false, $allowUpScaling = false, $async = false, $preset = null, $srcsetWidths = [], $srcsetAttribute = 'data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw== 1w, ')
    {
        parent::render($image, $width, $maximumWidth, $height, $maximumHeight, $allowCropping, $allowUpScaling, $async, $preset);

        $widthsInSrcset = [];
        foreach (array_merge($this->settings['widths'], $srcsetWidths, [$image->getWidth()]) as $srcsetWidth) {
            if ($srcsetWidth <= $image->getWidth() && !in_array($srcsetWidth, $widthsInSrcset, true)) {
                $srcsetHeight = null;
                $srcsetMaxHeight = null;
                if ($allowCropping) {
                    if ($width !== null) {
                        if ($height !== null) {
                            $srcsetHeight = round($height / $width * $srcsetWidth);
                        }
                        if ($maximumHeight !== null) {
                            $srcsetMaxHeight = round($maximumHeight / $width * $srcsetWidth);
                        }
                    }
                    if ($maximumWidth !== null && $maximumWidth < $srcsetWidth && $height !== null) {
                        $srcsetHeight = round($height / $maximumWidth * $srcsetWidth);
                    }
                }
                $thumbnailData = $this->assetService->getThumbnailUriAndSizeForAsset($image, new ThumbnailConfiguration($srcsetWidth, null, $srcsetHeight, $srcsetMaxHeight, $allowCropping, $allowUpScaling, true), $this->controllerContext->getRequest());
                $srcsetAttribute .= $thumbnailData['src'] . ' ' . $srcsetWidth . 'w, ';
                $widthsInSrcset[] = $srcsetWidth;
            }
        }
        $this->tag->addAttribute('srcset', rtrim($srcsetAttribute, ', '));

        return $this->tag->render();
    }
}
