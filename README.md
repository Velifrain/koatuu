![https://symfony.com/](https://camo.githubusercontent.com/3cb73b015124298ce6026be365a732157bb1cdc1/68747470733a2f2f73796d666f6e792e636f6d2f6c6f676f732f73796d666f6e795f626c61636b5f30322e737667)
# koatuu
Создание проекта по реализации хранения информации Классификатора объектов административно-территориального устройства Украины

## Что используется:
* PHP 7.4.4
* PostgreSQL
* Docker

### Этапы работы:
1. Настройка среды разработки, установка Symfony с помощью composer 
`composer create-project symfony/website-skeleton my_project_name`
1. Настройка и подключение к базе данных `branch b-1`
1. Создание `ImportCommand` `branch b-2`:
   Выполнение последующих методов буду выполняться с помощью комманды `app:import`
  * Создание метода `loadFile()` для загрузки архива с сайта `http://www.ukrstat.gov.ua/klasf/st_kls/op_koatuu_2016.htm` и извлечение с архива
  * Создание метода `getContent()`, дополнительно установлена библиотека для работы office документами <https://github.com/PHPOffice>, для получения информации с `Xls` файла
  * Создание метода `parseRegions()` разбора данных подходящих для Областей
  * Создание `Entity` и метод `writeRegions()` для запись в таблицу

### Как использовать
* `docker-compose up -d`
* для бд: `doctrine:migration:migrate`
* `bin/console app:import` 
