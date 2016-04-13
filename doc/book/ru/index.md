Описание
------------
Компонент создания и управления таблицами. Позволяет создавать модель таблицы с действиями над записями.


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
    $this->addActions();
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
