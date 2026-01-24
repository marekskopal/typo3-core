<?php

declare(strict_types=1);

namespace Skopal\MsCore\DataProcessing;

use GeorgRinger\News\Domain\Repository\NewsRepository;
use TYPO3\CMS\Core\Context\LanguageAspect;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;

class LanguageMenuProcessor extends \TYPO3\CMS\Frontend\DataProcessing\LanguageMenuProcessor
{
    /**
     * @param ContentObjectRenderer $cObj The data of the content element or page
     * @param array<mixed> $contentObjectConfiguration The configuration of Content Object
     * @param array<mixed> $processorConfiguration The configuration of this processor
     * @param array<mixed> $processedData Key/value store of processed data (e.g. to be passed to a Fluid View)
     * @return array<mixed> the processed data as key/value store
     */
    public function process(ContentObjectRenderer $cObj, array $contentObjectConfiguration, array $processorConfiguration, array $processedData): array
    {
        /**
         * @var array<string, array{
         *     available: int,
         *     languageId: int,
         * }> $parentProcessedData
         */
        $parentProcessedData = parent::process($cObj, $contentObjectConfiguration, $processorConfiguration, $processedData);

        $menuTargetVariableName = $this->getConfigurationValue('as');

        $newsRepository = GeneralUtility::makeInstance(NewsRepository::class);

        $languages = [];

        /**
         * @var array{
         *     available: int,
         *     languageId: int,
         * } $language
         */
        foreach ($parentProcessedData[$menuTargetVariableName] ?? [] as $language) {
            if ($language['available'] != 1) {
                continue;
            }

            /** @var \TYPO3\CMS\Core\Http\ServerRequest|null $request */
            $request = $GLOBALS['TYPO3_REQUEST'] ?? null;
            if ($request === null) {
                continue;
            }

            /** @var array{action?: string, news?: int}|null $newsParams */
            $newsParams = $request->getQueryParams()['tx_news_pi1'] ?? null;
            if (
                $newsParams !== null
                && ($newsParams['action'] ?? null) === 'detail'
                && ($newsParams['news'] ?? null) > 0
            ) {
                $query = $newsRepository->createQuery();
                $querySettings = $query->getQuerySettings();
                $querySettings->setRespectStoragePage(false);
                $querySettings->setLanguageAspect(new LanguageAspect(
                    id: $language['languageId'],
                    overlayType: LanguageAspect::OVERLAYS_OFF,
                ));
                $news = $query->matching(
                    $query->logicalAnd(
                        $query->logicalOr(
                            $query->equals('uid', $newsParams['news']),
                            $query->equals('l10n_parent', $newsParams['news']),
                        ),
                        $query->equals('deleted', 0)
                    )
                )->execute()->getFirst();

                if ($news === null) {
                    continue;
                }
            }

            $languages[] = $language;
        }

        $parentProcessedData[$menuTargetVariableName] = $languages;

        return $parentProcessedData;
    }
}
