<?php

use Contao\DataContainer;
use Contao\DC_Table;
use Plakart\InternalNewsBundle\Category;

$GLOBALS['TL_DCA'][Category::TABLE] = [
    'config' => [
        'dataContainer' => DC_Table::class,
        'sql' => [
            'keys' => [
                'id' => 'primary',
            ],
        ],
    ],
    'list' => [
        'sorting' => [
            'mode' => DataContainer::MODE_SORTABLE,
            'fields' => ['date'],
            'flag' => DataContainer::SORT_DAY_DESC,
            'disableGrouping' => true,
            'panelLayout' => 'filter;limit',
        ],
        'label' => [
            'fields' => ['date', 'category', 'headline', 'text'],
            'showColumns' => true,
        ],
        'global_operations' => [
            'all' => [
                'href' => 'act=select',
                'class' => 'header_edit_all',
                'attributes' => 'onclick="Backend.getScrollOffset()" accesskey="e"',
            ],
        ],
        'operations' => [
            'edit',
            'delete',
            'show',
        ],
    ],
    'palettes' => [
        'default' => 'date,category,headline,text',
    ],
    'fields' => [
        'id' => [
            'sql' => 'int(10) unsigned NOT NULL auto_increment',
        ],
        'tstamp' => [
            'sql' => "int(10) unsigned NOT NULL default 0",
        ],
        'date' => [
            'default' => (int) strtotime('today'),
            'sorting' => true,
            'flag' => DataContainer::SORT_DAY_DESC,
            'inputType' => 'text',
            'eval' => ['rgxp' => 'date', 'mandatory' => true, 'datepicker' => true, 'tl_class' => 'w50 wizard'],
            'sql' => "int(10) unsigned NOT NULL default 0",
        ],
        'category' => [
            'filter' => true,
            'inputType' => 'select',
            'options' => Category::keys(),
            'reference' => &$GLOBALS['TL_LANG'][Category::TABLE]['categories'],
            'eval' => ['mandatory' => true, 'includeBlankOption' => true, 'tl_class' => 'w50'],
            'sql' => "varchar(32) NOT NULL default ''",
        ],
        'headline' => [
            'inputType' => 'text',
            'eval' => ['mandatory' => true, 'maxlength' => 255, 'tl_class' => 'clr'],
            'sql' => "varchar(255) NOT NULL default ''",
        ],
        'text' => [
            'inputType' => 'textarea',
            'eval' => ['mandatory' => true, 'rte' => 'tinyInternalNews', 'basicEntities' => true, 'tl_class' => 'clr'],
            'sql' => 'mediumtext NULL',
        ],
    ],
];
