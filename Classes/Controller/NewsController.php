<?php

declare(strict_types=1);

namespace Skopal\MsCore\Controller;

use GeorgRinger\News\Domain\Model\News;
use TYPO3\CMS\Core\Context\Context;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use Psr\Http\Message\ResponseInterface;

class NewsController extends \GeorgRinger\News\Controller\NewsController
{
    public function detailAction(News $news = null, $currentPage = 1): ResponseInterface
    {
        $languageAspect = GeneralUtility::makeInstance(Context::class)->getAspect('language');
        $sysLanguageUid = $languageAspect->getId();

        if ($news !== null && $news->getSysLanguageUid() !== $sysLanguageUid) {
            $query = $this->newsRepository->createQuery();
            $querySettings = $query->getQuerySettings();
            $querySettings->setRespectStoragePage(false);
            $querySettings->setRespectSysLanguage(false);
            $querySettings->setLanguageOverlayMode(false);
            $querySettings->setLanguageUid($sysLanguageUid);
            $news = $query->matching(
                $query->logicalAnd(
                    $query->equals('l10nParent', $news->getUid()),
                    $query->equals('deleted', 0)
                )
            )->execute()->getFirst();

            if ($news === null) {
                $GLOBALS['TSFE']->pageNotFoundAndExit('Entity not found.');
            }
        }

        return parent::detailAction($news, $currentPage);
    }
}
