<?php

declare(strict_types=1);

namespace Skopal\MsCore\ViewHelpers;

use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2019 Marek Skopal <skopal.marek@gmail.com>
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

class FlexImageViewHelper extends AbstractViewHelper
{

    protected const ARGUMENT_ROW = 'row';
    protected const ARGUMENT_IMAGE = 'image';

    /** @var bool */
    protected $escapeOutput = false;

    public function initializeArguments()
    {
        parent::initializeArguments();
        $this->registerArgument(self::ARGUMENT_ROW, 'array', 'Row');
        $this->registerArgument(self::ARGUMENT_IMAGE, 'array', 'Image');
    }

    /**
     * @return string
     */
    public function render(): string
    {
        $image = $this->arguments[self::ARGUMENT_IMAGE];

        $aspectRatio = ($image['dimensions']['width']) / $image['dimensions']['height'];

        return 'flex:' . $aspectRatio . ';';
    }

}