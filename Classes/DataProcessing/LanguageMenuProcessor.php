<?php

namespace Skopal\MsCore\DataProcessing;

use GeorgRinger\News\Domain\Repository\NewsRepository;
use TYPO3\CMS\Core\Context\Context;
use \TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;

class LanguageMenuProcessor extends \TYPO3\CMS\Frontend\DataProcessing\LanguageMenuProcessor
{

    /**
     * @param ContentObjectRenderer $cObj The data of the content element or page
     * @param array $contentObjectConfiguration The configuration of Content Object
     * @param array $processorConfiguration The configuration of this processor
     * @param array $processedData Key/value store of processed data (e.g. to be passed to a Fluid View)
     * @return array the processed data as key/value store
     */
    public function process(ContentObjectRenderer $cObj, array $contentObjectConfiguration, array $processorConfiguration, array $processedData): array
    {
        $processedData = parent::process($cObj, $contentObjectConfiguration, $processorConfiguration, $processedData);

        /** @var ObjectManager $objectManager */
        $objectManager = GeneralUtility::makeInstance(ObjectManager::class);

        /** @var NewsRepository $newsRepository */
        $newsRepository = $objectManager->get(NewsRepository::class);

        $languages = [];
        foreach ($processedData[$this->menuTargetVariableName] ?? [] as $language) {
            if ($language['available'] == 1) {
                $newsParams = GeneralUtility::_GP('tx_news_pi1');
                if (!empty($newsParams) && $newsParams['action'] === 'detail' && $newsParams['news'] > 0) {
                    $query = $newsRepository->createQuery();
                    $querySettings = $query->getQuerySettings();
                    $querySettings->setRespectStoragePage(false);
                    $querySettings->setRespectSysLanguage(false);
                    $querySettings->setLanguageUid($language['languageId']);
                    $news = $query->matching(
                        $query->logicalAnd([
                            $query->equals('uid', $newsParams['news']),
                            $query->equals('deleted', 0)
                        ]))->execute()->getFirst();

                    if ($news !== null) {
                        $languages[] = $language;
                    }
                } else {
                    $languages[] = $language;
                }
            }
        }

        $processedData[$this->menuTargetVariableName] = $languages;

        return $processedData;
    }

}