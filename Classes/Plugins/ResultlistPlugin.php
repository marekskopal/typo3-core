<?php

declare(strict_types=1);

namespace Skopal\MsCore\Plugins;

class ResultlistPlugin extends \Tpwd\KeSearch\Plugins\ResultlistPlugin
{
    public function initFluidTemplate()
    {
        parent::initFluidTemplate();

        $this->getSearchboxContent();
    }
}
