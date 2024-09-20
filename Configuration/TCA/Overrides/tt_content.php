<?php

declare(strict_types=1);

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
            'type' => 'input',
            'size' => 10,
            'eval' => 'int',
        ]
    ],
];

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('tt_content', $fields);
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addFieldsToPalette('tt_content', 'section_pallete', 'section_hash,section_title,section_sorting');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes('tt_content', '--palette--;' . $llPath . ':tt_content.section_pallete;section_pallete', '', 'after:sectionIndex');


$GLOBALS['TCA']['tt_content']['columns']['CType']['config']['default'] = 'text';

$GLOBALS['TCA']['tt_content']['columns']['imagecols']['config']['default'] = 4;
unset($GLOBALS['TCA']['tt_content']['columns']['imagecols']['config']['items'][4]);
unset($GLOBALS['TCA']['tt_content']['columns']['imagecols']['config']['items'][6]);
unset($GLOBALS['TCA']['tt_content']['columns']['imagecols']['config']['items'][7]);

unset($GLOBALS['TCA']['tt_content']['columns']['layout']['config']['items'][1]);
unset($GLOBALS['TCA']['tt_content']['columns']['layout']['config']['items'][2]);
unset($GLOBALS['TCA']['tt_content']['columns']['layout']['config']['items'][3]);

$GLOBALS['TCA']['tt_content']['columns']['layout']['config']['items'][] = [
    'container',
    'container'
];
$GLOBALS['TCA']['tt_content']['columns']['layout']['config']['items'][] = [
    'container-sm',
    'container-sm'
];
$GLOBALS['TCA']['tt_content']['columns']['layout']['config']['items'][] = [
    'container-md',
    'container-md'
];
$GLOBALS['TCA']['tt_content']['columns']['layout']['config']['items'][] = [
    'container-lg',
    'container-lg'
];
$GLOBALS['TCA']['tt_content']['columns']['layout']['config']['items'][] = [
    'container-xl',
    'container-xl'
];
$GLOBALS['TCA']['tt_content']['columns']['layout']['config']['items'][] = [
    'container-xxl',
    'container-xxl'
];
$GLOBALS['TCA']['tt_content']['columns']['layout']['config']['items'][] = [
    'container-fluid',
    'container-fluid'
];

$GLOBALS['TCA']['tt_content']['columns']['frame_class']['config']['items'] = [
    [
        'No style',
        'default',
    ],
    [
        'Style 1',
        'style-1',
    ],
    [
        'Style 2',
        'style-2',
    ],
    [
        'Style 3',
        'style-3',
    ],
];


$GLOBALS['TCA']['tt_content']['columns']['space_before_class']['config']['items'][] = [
    'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:frame_class.none',
    'none'
];
$GLOBALS['TCA']['tt_content']['columns']['space_after_class']['config']['items'][] = [
    'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:frame_class.none',
    'none'
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

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes('tt_content', '--palette--;;headers', 'form_formframework', 'replace:--palette--;;header');