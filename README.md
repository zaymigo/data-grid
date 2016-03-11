Модуль работы с таблицами
===================================================

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/mte-grid/grid/badges/quality-score.png?b=develop)](https://scrutinizer-ci.com/g/mte-grid/grid/?branch=develop)
[![Build Status](https://scrutinizer-ci.com/g/mte-grid/grid/badges/build.png?b=develop)](https://scrutinizer-ci.com/g/mte-grid/grid/build-status/develop)

Описание
------------
Компонент создания и управления таблицами. Позволяет создавать модель таблицы с действиями над записями.

Конфигурация таблицы
----------------------
Для работы с таблицами реализована фабрика. Для добавления таблицы необходимо в конфиг добавить
    'grid_manager' => [
        'factories' => [
            Factory::class => Factory::class
        ],
    ],
    'grid_columns' => [

    ],
    'grids' => [
        'class' => SimpleGrid::class,
        'adapter' => [
            'class' => SimpleAdapter::class,
            'options' => []
        ],
        'options' => []
    ]
];


Конфигурация колонок
----------------------
Для работы с колонками реализован GridColumnManager посредством которого можно получать колонки, добавлять новые 
посредством конфигурации модуля. Для добавления колонки необходимо чтобы класс Vendor\ModuleName\Module либо реализовывал 
GridColumnProviderInterface, либо в конфигурации модуля должен быть массив с ключом grid_columns.