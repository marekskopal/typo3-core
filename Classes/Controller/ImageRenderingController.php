<?php


namespace Skopal\MsCore\Controller;

use TYPO3\CMS\Core\Log\LogManager;
use \TYPO3\CMS\Core\Resource;
use \TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Controller to render the image tag in frontend
 *
 */
class ImageRenderingController extends \Netresearch\RteCKEditorImage\Controller\ImageRenderingController
{
    /**
     * Returns a processed image to be displayed on the Frontend.
     *
     * @param string $content Content input (not used).
     * @param array $conf TypoScript configuration
     * @return string HTML output
     */
    public function renderImageAttributes($content = '', $conf)
    {
        $imageAttributes = $this->getImageAttributes();

        // It is pretty rare to be in presence of an external image as the default behaviour
        // of the RTE is to download the external image and create a local image.
        // However, it may happen if the RTE has the flag "disable"
        if (!$this->isExternalImage()) {

            $fileUid = (int)$imageAttributes['data-htmlarea-file-uid'];
            if ($fileUid) {
                try {
                    $resourceFactory = GeneralUtility::makeInstance(Resource\ResourceFactory::class);
                    $file = $resourceFactory->getFileObject($fileUid);
                    if ($imageAttributes['src'] !== $file->getPublicUrl()) {
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
                        $processedFile = $this->getMagicImageService()->createMagicImage($file, $imageConfiguration);
                        $additionalAttributes = [
                            'src' => $processedFile->getPublicUrl(),
                            'title' => ($imageAttributes['title']) ? $imageAttributes['title'] : $file->getProperty('title'),
                            'alt' => ($imageAttributes['alt']) ? $imageAttributes['alt'] : $file->getProperty('alternative'),
                            'width' => ($processedFile->getProperty('width')) ? $processedFile->getProperty('width') : $imageConfiguration['width'],
                            'height' => ($processedFile->getProperty('height')) ? $processedFile->getProperty('height') : $imageConfiguration['height'],
                        ];
                        $imageAttributes = array_merge($imageAttributes, $additionalAttributes);
                    }
                } catch (Resource\Exception\FileDoesNotExistException $fileDoesNotExistException) {
                    // Log in fact the file could not be retrieved.
                    $message = sprintf('I could not find file with uid "%s"', $fileUid);
                    $this->getLogger()->error($message);
                }
            }
        }

        $imageAttributes['class'] = (!empty($imageAttributes['class']) ? $imageAttributes['class'] . ' ' : '') . 'img-fluid';

        $imageAttributes['loading'] = 'lazy';

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

        // Image template; empty attributes are removed by 3nd param 'false'
        $img = '<figure>';
        $img .= '<img ' . GeneralUtility::implodeAttributes($imageAttributes, true, false) . ' />';

        if (!empty($imageAttributes['title'])) {
            $img .= '<figcaption>' . $imageAttributes['title'] . '</figcaption>';
        }

        $img .= '</figure>';

        // Popup rendering (support new `zoom` and legacy `clickenlarge` attributes)
        if (($imageAttributes['data-htmlarea-zoom'] || $imageAttributes['data-htmlarea-clickenlarge']) && isset($file) && $file) {
            $config = $GLOBALS['TSFE']->tmpl->setup['lib.']['contentElement.']['settings.']['media.']['popup.'];
            $config['enable'] = 1;
            $file->updateProperties(array('title'=>($imageAttributes['title']) ? $imageAttributes['title'] : $file->getProperty('title')));
            $this->cObj->setCurrentFile($file);

            // Use $this->cObject to have access to all parameters from the image tag
            return $this->cObj->imageLinkWrap(
                $img,
                $file,
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
    protected function isExternalImage()
    {
        $fileUid = $this->cObj->parameters['data-htmlarea-file-uid'] ?? null;
        return $fileUid === null;
    }

    /**
     * @return \TYPO3\CMS\Core\Log\Logger
     */
    private function getLogger()
    {
        /** @var $logManager LogManager */
        $logManager = GeneralUtility::makeInstance(LogManager::class);
        return $logManager->getLogger(get_class($this));
    }
}
