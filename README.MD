##От пользователя поступают xlsx файлы с структурой 
id | Название товара | Цена* | Штрих код | Оценочная стоимость
```
Видео работы - https://www.youtube.com/watch?v=l9Ta3evctI4
Вызов компонента - public/import/index.php
Компонент - public/local/components/public/import
Классы импорта - app/Core/Import
Для импорта была использована библиотека https://phpspreadsheet.readthedocs.io/en/latest/
```

##Список зарегистрированных пользователей
```
Видео работы - https://www.youtube.com/watch?v=mGsLG10B6LU
Компонент списка - public/local/components/public/users.list
Вызов компонента списка пользователей - index.php
Компонент пагинации - public/local/components/public/pagination
События отчистки паки кеша пользователей - public/local/php_interface/events.php
Обработчик События отчистки паки кеша - app/Core/Events/UserList.php
```

##Консольная команда импорта пользователей csv и xml
```
Видео работы - https://www.youtube.com/watch?v=VkPVM4mjJ4E
Информация о консольной команде bin/console help export:users
Запуск команды для xml bin/console export:users xml
Запуск команды для csv bin/console export:users csv
Файл xml - public/upload/export/users/users.xml
Файл csv - public/upload/export/users/users.csv
Класс команды - app/Core/Commands/Export/UsersExportCommand.php
Классы сохранения - app/Core/Export/UsersExport
Bash консоли - bin/console
Run консоли - console/run.php
```

##Список имён отпрвителя
```
Видео работы - https://www.youtube.com/watch?v=OPiaekVECNU
Комплексный компонент - public/local/components/public/application.for.letter.name
Список имён отпрвителя (отображаются только для текущего и авторизованного пользователя)
Адрес - /application-for-letter-name/ - фильтруется по id пользователя
Компонент - public/local/components/public/application.for.letter.name.list

Форма заказа буквенного имени
Адрес - /application-for-letter-name/send/
Компонент - public/local/components/public/application.for.letter.name.send
Инфоблок заявок - https://i.imgur.com/9ZKl3ZK.png
Почтовый шаблон - https://i.imgur.com/RxBT0Vv.png
Событие отправки сообщения при изменении элемента - public/local/php_interface/events.php
Дамп базы данных - bitrix.sql
```