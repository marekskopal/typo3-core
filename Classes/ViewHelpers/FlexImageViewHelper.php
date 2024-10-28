<?php

declare(strict_types=1);

namespace Skopal\MsCore\ViewHelpers;

use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

class FlexImageViewHelper extends AbstractViewHelper
{
    protected const ARGUMENT_ROW = 'row';
    protected const ARGUMENT_IMAGE = 'image';

    /** @var bool */
    protected $escapeOutput = false;

    public function initializeArguments(): void
    {
        parent::initializeArguments();
        $this->registerArgument(self::ARGUMENT_ROW, 'array', 'Row');
        $this->registerArgument(self::ARGUMENT_IMAGE, 'array', 'Image');
    }

    public function render(): string
    {
        /**
         * @var array{
         *     dimensions: array{
         *         width: int,
         *         height: int,
         *     },
         * } $image
         */
        $image = $this->arguments[self::ARGUMENT_IMAGE];

        $aspectRatio = ($image['dimensions']['width']) / $image['dimensions']['height'];

        return 'flex:' . $aspectRatio . ';';
    }
}
