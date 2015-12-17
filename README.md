Для разворачивания приложения необходимо:

    Установить и настроить:
    PHP 5.6
    MySQL 5.6
    Apache 2.4
        (или Вы можете скачать почти готовое решение http://open-server.ru/)

    После того, как все будет установлено файлы необходимо клонировать в главную дирректорию с сайтами

    1)   После загрузки файлов скачать и установить composer (https://getcomposer.org/)
            Команда для терминала: php -r "readfile('https://getcomposer.org/installer');" | php

    2)   Используяя composer:

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

    Приложение доступно по адресу "ваш путь"/web/index.php

Symfony Standard Edition
========================

Welcome to the Symfony Standard Edition - a fully-functional Symfony2
application that you can use as the skeleton for your new applications.

For details on how to download and get started with Symfony, see the
[Installation][1] chapter of the Symfony Documentation.

What's inside?
--------------

The Symfony Standard Edition is configured with the following defaults:

  * An AppBundle you can use to start coding;

  * Twig as the only configured template engine;

  * Doctrine ORM/DBAL;

  * Swiftmailer;

  * Annotations enabled for everything.

It comes pre-configured with the following bundles:

  * **FrameworkBundle** - The core Symfony framework bundle

  * [**SensioFrameworkExtraBundle**][6] - Adds several enhancements, including
    template and routing annotation capability

  * [**DoctrineBundle**][7] - Adds support for the Doctrine ORM

  * [**TwigBundle**][8] - Adds support for the Twig templating engine

  * [**SecurityBundle**][9] - Adds security by integrating Symfony's security
    component

  * [**SwiftmailerBundle**][10] - Adds support for Swiftmailer, a library for
    sending emails

  * [**MonologBundle**][11] - Adds support for Monolog, a logging library

  * [**AsseticBundle**][12] - Adds support for Assetic, an asset processing
    library

  * **WebProfilerBundle** (in dev/test env) - Adds profiling functionality and
    the web debug toolbar

  * **SensioDistributionBundle** (in dev/test env) - Adds functionality for
    configuring and working with Symfony distributions

  * [**SensioGeneratorBundle**][13] (in dev/test env) - Adds code generation
    capabilities

All libraries and bundles included in the Symfony Standard Edition are
released under the MIT or BSD license.

Enjoy!

[1]:  http://symfony.com/doc/2.5/book/installation.html
[6]:  http://symfony.com/doc/2.5/bundles/SensioFrameworkExtraBundle/index.html
[7]:  http://symfony.com/doc/2.5/book/doctrine.html
[8]:  http://symfony.com/doc/2.5/book/templating.html
[9]:  http://symfony.com/doc/2.5/book/security.html
[10]: http://symfony.com/doc/2.5/cookbook/email.html
[11]: http://symfony.com/doc/2.5/cookbook/logging/monolog.html
[12]: http://symfony.com/doc/2.5/cookbook/assetic/asset_management.html
[13]: http://symfony.com/doc/2.5/bundles/SensioGeneratorBundle/index.html
