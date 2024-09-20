<?php

declare(strict_types=1);

namespace Skopal\MsCore\Plugins;

/**
 * Class ResultlistPlugin
 * @package Skopal\MsCore\Plugins
 */
class ResultlistPlugin extends \Tpwd\KeSearch\Plugins\ResultlistPlugin
{

    /**
     * inits the standalone fluid template
     */
    public function initFluidTemplate()
    {
        parent::initFluidTemplate();

        $this->getSearchboxContent();
    }
}
