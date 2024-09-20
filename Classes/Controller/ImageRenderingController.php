<?php

declare(strict_types=1);

namespace Skopal\MsCore\Controller;

use TYPO3\CMS\Core\Log\LogLevel;
use TYPO3\CMS\Core\Resource\Exception\FileDoesNotExistException;
use TYPO3\CMS\Core\Resource\ResourceFactory;
use \TYPO3\CMS\Core\Utility\GeneralUtility;

class ImageRenderingController extends \Netresearch\RteCKEditorImage\Controller\ImageRenderingController
{
    /**
     * Returns a processed image to be displayed on the Frontend.
     *
     * @param string $content Content input (not used).
     * @param array $conf TypoScript configuration
     * @return string HTML output
     */
    public function renderImageAttributes(?string $content, array $conf = []): string
    {
        $imageAttributes = $this->getImageAttributes();
        $imageSource     = $imageAttributes['src'] ?? '';

        // It is pretty rare to be in presence of an external image as the default behaviour
        // of the RTE is to download the external image and create a local image.
        // However, it may happen if the RTE has the flag "disable"
        if (!$this->isExternalImage($imageSource)) {
            $fileUid = (int) ($imageAttributes['data-htmlarea-file-uid'] ?? 0);

            if ($fileUid > 0) {
                try {
                    $systemImage = GeneralUtility::makeInstance(ResourceFactory::class)->getFileObject($fileUid);

                    if ($imageAttributes['src'] !== $systemImage->getPublicUrl()) {
                        // Source file is a processed image
                        $imageConfiguration = [
                            'width' => (int)$imageAttributes['width'],
                            'height' => (int)$imageAttributes['height']
                        ];

                        $magicService = $this->getMagicImageService();
                        $magicService->setMagicImageMaximumDimensions([
                            'buttons.' => [
                                'image.' => [
                                    'options.' => [
                                        'magic.' => [
                                            'maxWidth' => $imageConfiguration['width'],
                                            'maxHeight' => $imageConfiguration['height'],
                                        ]
                                    ]
                                ]
                            ]
                        ]);
                        $processedFile = $this->getMagicImageService()->createMagicImage($systemImage, $imageConfiguration);

                        $additionalAttributes = [
                            'src' => $processedFile->getPublicUrl(),
                            'title' => self::getAttributeValue('title', $imageAttributes, $systemImage),
                            'alt' => self::getAttributeValue('alt', $imageAttributes, $systemImage),
                            'width' => ($processedFile->getProperty('width')) ? $processedFile->getProperty('width') : $imageConfiguration['width'],
                            'height' => ($processedFile->getProperty('height')) ? $processedFile->getProperty('height') : $imageConfiguration['height'],
                        ];

                        if (!empty($GLOBALS['TSFE']->tmpl->setup['lib.']['contentElement.']['settings.']['media.']['lazyLoading'])) {
                            $additionalAttributes['loading'] = $GLOBALS['TSFE']->tmpl->setup['lib.']['contentElement.']['settings.']['media.']['lazyLoading'];
                        }

                        // Remove internal attributes
                        unset($imageAttributes['data-title-override']);
                        unset($imageAttributes['data-alt-override']);

                        $imageAttributes = array_merge($imageAttributes, $additionalAttributes);
                    }
                } catch (FileDoesNotExistException $fileDoesNotExistException) {
                    // Log in fact the file could not be retrieved.
                    $message = sprintf('I could not find file with uid "%s"', $fileUid);
                    $this->getLogger()->log(LogLevel::ERROR, $message);
                }
            }
        }

        $imageAttributes['class'] = (!empty($imageAttributes['class']) ? $imageAttributes['class'] . ' ' : '') . 'img-fluid';

        // Cleanup attributes
        if (!isset($imageAttributes['data-htmlarea-zoom']) && !isset($imageAttributes['data-htmlarea-clickenlarge'])) {
            $unsetParams = [
                'allParams',
                'data-htmlarea-file-uid',
                'data-htmlarea-file-table',
                'data-htmlarea-zoom',
                'data-htmlarea-clickenlarge' // Legacy zoom property
            ];
            $imageAttributes = array_diff_key($imageAttributes, array_flip($unsetParams));
        }

        // Image template; empty attributes are removed by 3rd param 'false'
        $img = '<figure>';
        $img .= '<img ' . GeneralUtility::implodeAttributes($imageAttributes, true, false) . ' />';

        if (!empty($imageAttributes['title'])) {
            $img .= '<figcaption>' . $imageAttributes['title'] . '</figcaption>';
        }

        $img .= '</figure>';

        // Popup rendering (support new `zoom` and legacy `clickenlarge` attributes)
        if ((($imageAttributes['data-htmlarea-zoom'] ?? false) || ($imageAttributes['data-htmlarea-clickenlarge'] ?? false)) && isset($systemImage)) {
            $config = $GLOBALS['TSFE']->tmpl->setup['lib.']['contentElement.']['settings.']['media.']['popup.'];
            $config['enable'] = 1;
            $systemImage->updateProperties(array('title'=>($imageAttributes['title']) ? $imageAttributes['title'] : $systemImage->getProperty('title')));
            $this->cObj->setCurrentFile($systemImage);

            // Use $this->cObject to have access to all parameters from the image tag
            return $this->cObj->imageLinkWrap(
                $img,
                $systemImage,
                $config
            );
        }
        return $img;
    }

    /**
     * Tells whether the image URL is found to be "external".
     *
     * @return bool
     */
    protected function isExternalImage(string $imageSource): bool
    {
        $fileUid = $this->cObj->parameters['data-htmlarea-file-uid'] ?? null;
        return $fileUid === null;
    }
}
