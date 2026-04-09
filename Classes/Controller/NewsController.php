<?php

declare(strict_types=1);

namespace Skopal\MsCore\Controller;

use GeorgRinger\News\Domain\Model\News;
use GeorgRinger\News\Domain\Repository\CategoryRepository;
use GeorgRinger\News\Domain\Repository\NewsRepository;
use GeorgRinger\News\Domain\Repository\TagRepository;
use TYPO3\CMS\Core\Context\Context;
use TYPO3\CMS\Core\Context\LanguageAspect;
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Frontend\Controller\ErrorController;
use TYPO3\CMS\Frontend\Page\PageAccessFailureReasons;

class NewsController extends \GeorgRinger\News\Controller\NewsController
{
    public function __construct(
        NewsRepository $newsRepository,
        CategoryRepository $categoryRepository,
        TagRepository $tagRepository,
        private readonly Context $context,
        private readonly ErrorController $errorController,
    ) {
        parent::__construct($newsRepository, $categoryRepository, $tagRepository);
    }

    /**
     * Single view of a news record
     *
     * @param News $news news item
     * @param int $currentPage current page for optional pagination
     */
    public function detailAction(?News $news = null, $currentPage = 1): ResponseInterface
    {
        /** @var LanguageAspect $languageAspect */
        $languageAspect = $this->context->getAspect('language');
        $sysLanguageUid = $languageAspect->getId();

        if ($news !== null && $news->getSysLanguageUid() !== $sysLanguageUid) {
            $query = $this->newsRepository->createQuery();
            $querySettings = $query->getQuerySettings();
            $querySettings->setRespectStoragePage(false);
            $querySettings->setRespectSysLanguage(false);
            $querySettings->setLanguageAspect(new LanguageAspect(
                id: $sysLanguageUid,
                overlayType: LanguageAspect::OVERLAYS_OFF,
            ));
            /** @var News|null $news */
            $news = $query->matching(
                $query->logicalAnd(
                    $query->equals('l10nParent', $news->getUid()),
                    $query->equals('deleted', 0)
                )
            )->execute()->getFirst();

            if ($news === null) {
                return $this->errorController->pageNotFoundAction(
                    $this->request,
                    'The requested page does not exist',
                    ['code' => PageAccessFailureReasons::PAGE_NOT_FOUND]
                );
            }
        }

        return parent::detailAction($news, $currentPage);
    }
}
