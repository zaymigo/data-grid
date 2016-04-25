Работа с мутаторами
======================

Мутаторы используются для изменения прдеставления данных при выводе из БД пользователю. Фактически они изменяют данные, 
которые пришли из БД перед тем как таблица будет отображена на странице. Мутатор может изменять определенный столбец 
таблицы или всю строку. Для возможности добавления новых кастомных мутаторов в модуле создан GridMutatorPluginManager
в котором регистрируются мутаторы. 

Давайте же разберемся как использовать мутаторы в таблицах. Для этого допустим, что
у нас есть некая таблица в которой находятся данные по денежным средствам в формате, где целые могут быть до 20 знаков,
а дробные до 2-х знаков. Т.е. в БД тип этих данных NUMERIC(20,2), но по требованиям бизнеса при выводе должен выводиться
лишь 1 знак после зпт, разделитель дробных должен быть точкой, а миллионов - пробел. Для решения данной задачи воспользуемся
мутатором Money.

Для вывода данного столбца мы будем использовать тип колонки money. В классе таблицы добавим колонку:
```php
$this->add([
    'type' => 'money',
    'name' => 'number_column',
    'header' => [
        'title' => 'Денежная колонка'
    ],
    'options' => [
        'mutatorsOptions' => [
            [
                'decimals' => 1, 
                'decPoint' => '.',
                'thousandSeparator' => ' '
            ]
        ]
    ]
]);
```
Обратим внимание на секцию mutatorsOptions в ней описаны настройки для предустановленных мутаторов колонки money.
Под предустановленными мутаторами понимаются те мутаторы которые по умолчанию работают с колонкой, они описываются в свойстве
класса колонки $invokableMutators и более подробно это описано в документации по столбцам. 
В данном же случае описаны свойства для мутатора money который имеет следующие параметры:
```php
protected $decimals = 2;
protected $decPoint = '.';
protected $thousandSeparator = ' ';
```
Т.е. для числа мы можем указать любой из этих параметров, аналогично стандартной php функции number_format. 

В случае, если столбец имеет несколько мутаторов описанных в invokableMutators в массиве mutatorsOptions для данной колонки 
для каждого из этих мутаторов будет свои массив настроек, причем надо учитывать что последовательность настроек должна быть 
та же, что и последовательность мутаторов описанных в invokableMutators.

Теперь разберемся как можно написать собственный мутатор, зарегистрировать его в системе и использовать.
Для этого создадим свой класс мутатор.

    class CustomMutator extends AbstractMutator 
    {
        /**
         * @var string
        **/
        protected $customOption;
        
        public function __construct(array $options = [])
        {
            parent::__construct($options);
            if(array_key_exists('customOption', $options)) {
                $this->setCustomOption($options['customOption']);
            }
        }
        /**
         * Изменяет данные
         * @param mixed $value
         * @return mixed
         */
        public function change($value)
        {
            if($this->getCustomOption() === 'test' && $value) {
                return 1;
            } else {
                return 0;
            }
        }
        /**
         * @return string
         */
        public function getCustomOption()
        {
            return $this->customOption;
        }
        /**
         * @param string $customOption
         * @return $this
         */
        public function setCustomOption($customOption)
        {
            $this->customOption = $customOption;
            return $this;
        }
    }
В методе change должна быть некая бизнес логика по преобразованию данных для данного мутатора.
Теперь регистрируем в конфигурационном файле модуля данный мутатор:
```php
'grid_mutators' => [
    'invokables' => [
        'CustomMutator' => Custom|Mutator::class
    ]
]
```
Теперь мы можем вызывать мутатор с именем CustomMutator. Давайте попробуем сделать это.
```php
        $this->add([
            'type' => 'text',
            'name' => 'field',
            'header' => [
                'title' => 'Колонка проверки мутатора'
            ],
            'mutators' => [
                [
                    'type' => 'CustomMutator',
                    'options' => [
                        'customOption' => 'test'
                    ]
                ]
            ]
        ]);
```
Т.о. для колонки с именем field  будет применяться мутатор CustomMutator. В зависимости от значения и параметра customOption 
будет выводиться 0 или 1. 

