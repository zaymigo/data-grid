Модуль работы с таблицами
===================================================


[![Build Status](https://travis-ci.org/nnx-framework/data-grid.svg?branch=master)](https://travis-ci.org/nnx-framework/data-grid)
[![Coverage Status](https://coveralls.io/repos/github/nnx-framework/data-grid/badge.svg?branch=master)](https://coveralls.io/github/nnx-framework/data-grid?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/nnx-framework/data-grid/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/nnx-framework/data-grid/?branch=master)
[![Documentation Status](https://readthedocs.org/projects/data-grid/badge/?version=master)](http://data-grid.readthedocs.org/ru/latest/?badge=master)

Develop:

[![Build Status](https://travis-ci.org/nnx-framework/data-grid.svg?branch=develop)](https://travis-ci.org/nnx-framework/data-grid)
[![Coverage Status](https://coveralls.io/repos/github/nnx-framework/data-grid/badge.svg?branch=develop)](https://coveralls.io/github/nnx-framework/data-grid?branch=develop)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/nnx-framework/data-grid/badges/quality-score.png?b=develop)](https://scrutinizer-ci.com/g/nnx-framework/data-grid/?branch=develop)
[![Documentation Status](https://readthedocs.org/projects/data-grid/badge/?version=develop)](http://data-grid.readthedocs.org/ru/latest/?badge=develop)


Описание
------------
Компонент создания и управления таблицами. Позволяет создавать модель таблицы с действиями над записями.

Установка модуля
-----------------
Для установки через composer

```json
composer require nnx/data-grid
```


Создание первой таблицы
--------------------------
Представим что у вас есть проект написанный с использованием ZF 2, есть некий модуль, которому необходимо 
отображение таблиц с информацией. Для создания таблицы прежде всего создадим папку Grid в модуле. В ней реализуем класс
наследуемый от SimpleGrid в котором опишем колонки в методе init. Это делается примерно так:

```php
public function init()
{
    $this->add([
        'type' => 'text',
        'name' => 'title',
        'header' => [
            'title' => 'Наименование'
        ],
        'attributes' => [
            'width' => '275px'
        ]
    ]);
    $this->add([
        'type' => 'link',
        'name' => 'url',
        'header' => [
            'title' => 'Ссылка'
        ],
        'options' => [
            'mutatorsOptions' => [
                [
                    'routeName' => 'your-route',
                    'routeParams' => [
                        'action' => 'view',
                        'id' => ':id'
                    ]
                ]
            ]
        ]
    ]);
    $this->add([
        'type' => 'action',
        'name' => 'actions',
        'header' => [
            'title' => 'Действия над строкой'
        ],
    ]);
 }
 public function addActions()
 {
     $editConfAction = [
         'type' => 'simple',
         'name' => 'edit',
         'title' => 'Редактировать',
         'route' => [
             'routeName' => 'your-route',
             'routeParams' => [
                 'action' => 'edit',
                 'id' => ':id'
             ]
         ],
         'validate' => function ($row) {
             $res = false;
             if ($row['editable']) {
                 $res = true;
             }
             return $res;
         }
     ];
     $actionColumn = $this->get('actions');
     $actionColumn->addAction($editConfAction);
 }
```
Метод add добавляет колонку в таблицу. Фактически в массиве передаваемом первым аргументом для данного метода описываются
характеристики колонок. Элемент массива с ключом type говорит фабрике колнок какую в данном случае необходимо создавать.

Но этого недостаточно для работы таблицы. Мы лишь описали модель. Теперь нам надо сказать таблице откуда брать данные. 
Для этого необходимо реализовать класс adapter. Для примера сделаем DoctrineDBAL адаптер, который выберет данные из базы.
Для этого создадим класса адаптера, унаследуем его от DoctrineDBAL адаптера в модуле и реализуем метод init

```php
public function init()
{
    $em = $this->getEntityManager();
    $query = $em->getConnection()->createQueryBuilder();
    $query->select([
        'ta.id',
        'ta.title',
    ])->from('table_name', 'ta');
    $this->setRootAlias('ta');
    $this->setQuery($query);
}
```
Теперь осталось зарегистрировать данную таблицу в GridPluginManager.
Для этого пропишем в конфигу модуля
```php
'mteGrid' => [
    'grids' => [
        'OurGrid' => [
            'class' => OurGrid::class,
            'options' => [
                'adapter' => [
                    'class' => OurAdapter::class
                ]
            ]
    ]
]
```
После этого из любого места где доступен сервис локатор можно вызвать нашу созданную таблицу.
```php
  $grid = $this->getServiceLocator()->get('GridManager')->get('grids.OurGrid');
```


# Документация
- [Online documentation](http://data-grids.readthedocs.org/ru/dev/)
- [Documentation source files](doc/book/ru/)
