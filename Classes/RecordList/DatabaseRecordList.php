<?php

declare(strict_types=1);

namespace Skopal\MsCore\RecordList;

use TYPO3\CMS\Backend\Template\Components\Buttons\ButtonInterface;
use TYPO3\CMS\Core\Imaging\IconSize;
use TYPO3\CMS\Core\Type\Bitmask\Permission;

class DatabaseRecordList extends \TYPO3\CMS\Backend\RecordList\DatabaseRecordList
{
    public function createActionButtonNewRecord(string $table): ?ButtonInterface
    {
        if (!$this->isEditable($table)) {
            return null;
        }
        if (!$this->showNewRecLink($table)) {
            return null;
        }
        $permsAdditional = ($table === 'pages' ? Permission::PAGE_NEW : Permission::CONTENT_EDIT);
        if (!$this->calcPerms->isGranted($permsAdditional)) {
            return null;
        }

        $label = sprintf(
            $this->getLanguageService()->sL('LLL:EXT:core/Resources/Private/Language/locallang_mod_web_list.xlf:newRecordOfType'),
            $this->tcaSchemaFactory->get($table)->getTitle($this->getLanguageService()->sL(...)),
        );
        $dataAttributes = [
            'recordlist-action' => 'new',
        ];

        $button = $this->componentFactory->createLinkButton()
            ->setTitle($label)
            ->setShowLabelText(true);

        if ($table === 'pages') {
            $button->setIcon($this->iconFactory->getIcon('actions-page-new', IconSize::SMALL));
            $button->setHref((string)$this->uriBuilder->buildUriFromRoute(
                'db_new_pages',
                ['id' => $this->id, 'returnUrl' => $this->listURL()]
            ));
            $dataAttributes['new'] = 'page';
        } else {
            $button->setIcon($this->iconFactory->getIcon('actions-plus', IconSize::SMALL));
            $button->setHref((string)$this->uriBuilder->buildUriFromRoute(
                'record_edit',
                [
                    'edit' => [
                        $table => [
                            $this->id => 'new',
                        ],
                    ],
                    'module' => $this->request->getAttribute('module')?->getIdentifier() ?? '',
                    'returnUrl' => $this->listURL(),
                ]
            ));
        }

        return $button->setDataAttributes($dataAttributes);
    }
}
