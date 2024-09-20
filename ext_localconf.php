<?php

declare(strict_types=1);

if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

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