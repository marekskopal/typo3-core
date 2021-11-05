<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'Marek Skopal - Core',
    'description' => 'Core settings extension.',
    'category' => 'misc',
    'shy' => '',
    'version' => '1.0.0',
    'dependencies' => '',
    'conflicts' => '',
    'priority' => '',
    'loadOrder' => '',
    'module' => '',
    'state' => 'stable',
    'uploadfolder' => 0,
    'createDirs' => '',
    'modify_tables' => '',
    'clearCacheOnLoad' => 0,
    'lockType' => '',
    'author' => 'Marek Skopal',
    'author_email' => 'skopal.marek@gmail.com',
    'CGLcompliance' => '',
    'CGLcompliance_note' => '',
    'constraints' => [
        'depends' => [
            'typo3' => '9.5.0',
            'fluid_styled_content' => '9.5.0',
            'rte_ckeditor_image' => '9.0.0'
        ],
        'conflicts' =>[
        ],
        'suggests' => [
        ],
    ]
];
