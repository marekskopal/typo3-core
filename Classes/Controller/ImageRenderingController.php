<?php

declare(strict_types=1);

namespace Skopal\MsCore\Controller;

use Psr\Http\Message\ServerRequestInterface;

class ImageRenderingController extends \Netresearch\RteCKEditorImage\Controller\ImageRenderingController
{
    public function renderImageAttributes(?string $content, array $conf, ServerRequestInterface $request): string
    {
        $imageAttributes = $this->getImageAttributes();

        $img = '<figure>';

        $img .= parent::renderImageAttributes($content, $conf, $request);

        if (!empty($imageAttributes['title'])) {
            $img .= '<figcaption>' . $imageAttributes['title'] . '</figcaption>';
        }

        $img .= '</figure>';

        return $img;
    }

    protected function isExternalImage(string $imageSource): bool
    {
        $fileUid = $this->cObj->parameters['data-htmlarea-file-uid'] ?? null;
        return $fileUid === null;
    }
}
