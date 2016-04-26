<?php
namespace ElementareTeilchen\Fluid\ViewHelpers;

/**
 * Returns the md5 of a given resource
 */
class Md5ViewHelper extends \TYPO3\Fluid\Core\ViewHelper\AbstractViewHelper
{
    /**
     * @param string $resource
     *
     * @return string
     */
    public function render($resource)
    {
        return md5_file($resource);
    }
}
