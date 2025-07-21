<?php

declare(strict_types=1);

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

defined('TYPO3') or die();

$llPath = 'LLL:EXT:ms_core/Resources/Private/Language/locallang_db.xlf';

$fields = [
    'section_hash' => [
        'exclude' => true,
        'label' => $llPath . ':tt_content.section_hash',
        'config' => [
            'type' => 'input',
            'size' => 30,
        ]
    ],
    'section_title' => [
        'exclude' => true,
        'label' => $llPath . ':tt_content.section_title',
        'config' => [
            'type' => 'input',
            'size' => 30,
        ]
    ],
    'section_sorting' => [
        'exclude' => true,
        'label' => $llPath . ':tt_content.section_sorting',
        'config' => [
            'type' => 'number',
            'size' => 10,
            'format' => 'integer',
        ]
    ],
    'frame_class' => [
        'exclude' => true,
        'label' => $llPath . ':tt_content.frame_class',
        'config' => [
            'type' => 'input',
            'size' => 30,
        ]
    ],
];

ExtensionManagementUtility::addTCAcolumns('tt_content', $fields);
ExtensionManagementUtility::addFieldsToPalette('tt_content', 'section_pallete', 'section_hash,section_title,section_sorting');
ExtensionManagementUtility::addToAllTCAtypes('tt_content', '--palette--;' . $llPath . ':tt_content.section_pallete;section_pallete', '', 'after:sectionIndex');
ExtensionManagementUtility::addFieldsToPalette('tt_content', 'frames', 'frame_class');

$GLOBALS['TCA']['tt_content']['columns']['CType']['config']['default'] = 'text';

$GLOBALS['TCA']['tt_content']['columns']['imagecols']['config']['default'] = 4;
unset($GLOBALS['TCA']['tt_content']['columns']['imagecols']['config']['items'][4]);
unset($GLOBALS['TCA']['tt_content']['columns']['imagecols']['config']['items'][6]);
unset($GLOBALS['TCA']['tt_content']['columns']['imagecols']['config']['items'][7]);

unset($GLOBALS['TCA']['tt_content']['columns']['layout']['config']['items'][1]);
unset($GLOBALS['TCA']['tt_content']['columns']['layout']['config']['items'][2]);
unset($GLOBALS['TCA']['tt_content']['columns']['layout']['config']['items'][3]);

$GLOBALS['TCA']['tt_content']['columns']['layout']['config']['items'][] = [
    'label' => 'container',
    'value' => 'container',
];
$GLOBALS['TCA']['tt_content']['columns']['layout']['config']['items'][] = [
    'label' => 'container-sm',
    'value' => 'container-sm',
];
$GLOBALS['TCA']['tt_content']['columns']['layout']['config']['items'][] = [
    'label' => 'container-md',
    'value' => 'container-md',
];
$GLOBALS['TCA']['tt_content']['columns']['layout']['config']['items'][] = [
    'label' => 'container-lg',
    'value' => 'container-lg',
];
$GLOBALS['TCA']['tt_content']['columns']['layout']['config']['items'][] = [
    'label' => 'container-xl',
    'value' => 'container-xl',
];
$GLOBALS['TCA']['tt_content']['columns']['layout']['config']['items'][] = [
    'label' => 'container-xxl',
    'value' => 'container-xxl',
];
$GLOBALS['TCA']['tt_content']['columns']['layout']['config']['items'][] = [
    'label' => 'container-fluid',
    'value' => 'container-fluid',
];

$GLOBALS['TCA']['tt_content']['columns']['frame_class']['config']['items'] = [
    [
        'label' => 'No style',
        'value' => 'default',
    ],
    [
        'label' => 'Style 1',
        'value' => 'style-1',
    ],
    [
        'label' => 'Style 2',
        'value' => 'style-2',
    ],
    [
        'label' => 'Style 3',
        'value' => 'style-3',
    ],
];


$GLOBALS['TCA']['tt_content']['columns']['space_before_class']['config']['items'][] = [
    'label' => 'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:frame_class.none',
    'value' => 'none',
];
$GLOBALS['TCA']['tt_content']['columns']['space_after_class']['config']['items'][] = [
    'label' => 'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:frame_class.none',
    'value' => 'none',
];

$GLOBALS['TCA']['tt_content']['types']['image']['showitem'] = '
--div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,--palette--;;general,--palette--;;headers,
--div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.images,image,--palette--;;gallerySettings,--palette--;;imagelinks,
--div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.appearance,--palette--;;frames,--palette--;;appearanceLinks,
--div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:language,--palette--;;language,
--div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,--palette--;;hidden,--palette--;;access,
--div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:categories,
--div--;LLL:EXT:core/Resources/Private/Language/locallang_tca.xlf:sys_category.tabs.category,categories,
--div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:notes,rowDescription,
--div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:extended
';

ExtensionManagementUtility::addToAllTCAtypes('tt_content', '--palette--;;headers', 'form_formframework', 'replace:--palette--;;header');
