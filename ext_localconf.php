<?php

declare(strict_types=1);

defined('TYPO3') or die();

$GLOBALS['TYPO3_CONF_VARS']['RTE']['Presets']['ms_core'] = 'EXT:ms_core/Configuration/RTE/Default.yaml';

$GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects'][TYPO3\CMS\Frontend\DataProcessing\LanguageMenuProcessor::class] = [
    'className' => Skopal\MsCore\DataProcessing\LanguageMenuProcessor::class
];
$GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects'][GeorgRinger\News\Controller\NewsController::class] = [
    'className' => Skopal\MsCore\Controller\NewsController::class
];
$GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects'][Tpwd\KeSearch\Plugins\ResultlistPlugin::class] = [
    'className' => Skopal\MsCore\Plugins\ResultlistPlugin::class
];
$GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects'][TYPO3\CMS\Backend\RecordList\DatabaseRecordList::class] = [
    'className' => Skopal\MsCore\RecordList\DatabaseRecordList::class
];
