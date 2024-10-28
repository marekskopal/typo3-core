<?php

declare(strict_types=1);

namespace Skopal\MsCore\Plugins;

class ResultlistPlugin extends \Tpwd\KeSearch\Plugins\ResultlistPlugin
{
    public function initFluidTemplate(): void
    {
        parent::initFluidTemplate();

        $this->getSearchboxContent();
    }
}
