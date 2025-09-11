<?php

declare(strict_types=1);

namespace Skopal\MsCore\RecordList;

use TYPO3\CMS\Backend\Template\Components\Buttons\ButtonInterface;
use TYPO3\CMS\Backend\Template\Components\Buttons\GenericButton;
use TYPO3\CMS\Core\Imaging\IconSize;
use TYPO3\CMS\Core\Type\Bitmask\Permission;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class DatabaseRecordList extends \TYPO3\CMS\Backend\RecordList\DatabaseRecordList
{
    protected function createActionButtonNewRecord(string $table): ?ButtonInterface
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

        $tag = 'a';
        $iconIdentifier = 'actions-plus';
        $label = sprintf(
            $this->getLanguageService()->sL('LLL:EXT:core/Resources/Private/Language/locallang_mod_web_list.xlf:newRecordOfType'),
            $this->getLanguageService()->sL($GLOBALS['TCA'][$table]['ctrl']['title'])
        );
        $attributes = [
            'data-recordlist-action' => 'new',
        ];

        if ($table === 'pages') {
            $iconIdentifier = 'actions-page-new';
            $attributes['data-new'] = 'page';
            $attributes['href'] = (string)$this->uriBuilder->buildUriFromRoute(
                'db_new_pages',
                ['id' => $this->id, 'returnUrl' => $this->listURL()]
            );
        } else {
            $attributes['href'] = $this->uriBuilder->buildUriFromRoute(
                'record_edit',
                [
                    'edit' => [
                        $table => [
                            $this->id => 'new',
                        ],
                    ],
                    'returnUrl' => $this->listURL(),
                ]
            );
        }

        $button = GeneralUtility::makeInstance(GenericButton::class);
        $button->setTag($tag);
        $button->setLabel($label);
        $button->setShowLabelText(true);
        $button->setIcon($this->iconFactory->getIcon($iconIdentifier, IconSize::SMALL));
        $button->setAttributes($attributes);

        return $button;
    }
}
