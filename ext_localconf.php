<?php

declare(strict_types=1);

defined('TYPO3') or die();

$GLOBALS['TYPO3_CONF_VARS']['RTE']['Presets']['ms_core'] = 'EXT:ms_core/Configuration/RTE/Default.yaml';

$GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects']['TYPO3\\CMS\\Frontend\\DataProcessing\\LanguageMenuProcessor'] = [
    'className' => 'Skopal\\MsCore\\DataProcessing\\LanguageMenuProcessor'
];
$GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects']['GeorgRinger\\News\\Controller\\NewsController'] = [
    'className' => 'Skopal\\MsCore\\Controller\\NewsController'
];
$GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects']['Tpwd\\KeSearch\\Plugins\\ResultlistPlugin'] = [
    'className' => 'Skopal\\MsCore\\Plugins\\ResultlistPlugin'
];
$GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects']['TYPO3\\CMS\\Backend\\RecordList\\DatabaseRecordList'] = [
    'className' => 'Skopal\\MsCore\\RecordList\\DatabaseRecordList'
];
