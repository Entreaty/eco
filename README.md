Для разворачивания приложения необходимо:

    Установить и настроить:
    PHP 5.6
    MySQL 5.6
    Apache 2.4

    После того, как все будет установлено файлы необходимо клонировать в главную дирректорию с сайтами

    1)   После загрузки файлов скачать и установить composer (https://getcomposer.org/)
            Команда для терминала: php -r "readfile('https://getcomposer.org/installer');" | php

    2)   Используя composer:

           php composer.phar require doctrine/doctrine-fixtures-bundle
            (все ответы стандартные (just press enter), кроме:
                database_name: test_project
                ...
                locale: ru
            )

    3)    И выполнить следующие команды также в терминале:

           php app/console doctrine:database:create

           php app/console doctrine:generate:entities AcmeEcoBundle

           php app/console doctrine:schema:update --force

    4)  Очистите кэш:

           php app/console cache:clear --env=dev
           php app/console cache:clear --env=prod

    Приложение доступно по адресу "Ваш путь"/web/index.php
