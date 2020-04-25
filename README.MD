##Cписок зарегистрированных пользователей
Компонент списка - public/local/components/public/users.list
Вызов компонента списка пользователей - index.php
Компонент пагинации - public/local/components/public/pagination
События отчистки паки кеша пользователей - public/local/php_interface/events.php
Обработчик События отчистки паки кеша - app/Core/Events/UserList.php

##Список имён отпрвителя
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