<p align="center">
    <a href="https://github.com/yiisoft" target="_blank">
        <img src="https://avatars0.githubusercontent.com/u/993323" height="100px">
    </a>
    <h1 align="center">Remote Help Address Book</h1>
    <br>
</p>

Приложение адресной книги на базе [Yii2 Framework](http://www.yiiframework.com/). Аналог mRemoteNG c web-интерфейсом.

Функционал:
- Древовидная структура папок подключений
- Возможность задания протокола для управления и протокола для просмотра без управления, иконки подключений
- Доплнительные протоколы (терминал (TELNET), файлы, управление питанием) для системных администраторов
- Возможность создания типов техники с основным и дополнительным протоколом подключения
- Вид списком или иконками (доработки вида иконками не ведутся, основной функционал реализуется в виде список)
- Проверка подключений онлайн в виде списком отдельного подключения или всех подключений в папке (работает медленно)
- Поиск по имени подключения/папки, а также по IP-адресу
- Поиск в неправильной раскладке языка (метод простой, но действенный)
- Возможность проверки имени хоста по обратной записи (PTR) DNS
- Импорт/экспорт адресной книги Radmin (в качестве дополнительной прослойки используется обрабока 1С)
- Импорт подключений из журналов MS RDS
- Импорт/(в разработке)экспорт подключений в специальный файл
- (в разработке) Импорт/экспорт подключений в LiteManager
- (в разработке) Генератор helppack - набора bat/reg-файлов для кастомных протоколов подключений
- (в разработке) Темная тема интерфейса
- (в разработке) User Management - отдельные учетные записи пользователей, собственные виртуальные папки и подключения
- Сбор статистики о подключениях (к кому и кто (по ip-адресу, в будущем по учетной записи))
- Дашборд с небольшой статистикой на главной странице

Далее дубль текста со стандартного README.md из Yii2 Basic Template

DIRECTORY STRUCTURE
-------------------

      assets/             contains assets definition
      commands/           contains console commands (controllers)
      config/             contains application configurations
      controllers/        contains Web controller classes
      mail/               contains view files for e-mails
      models/             contains model classes
      runtime/            contains files generated during runtime
      tests/              contains various tests for the basic application
      vendor/             contains dependent 3rd-party packages
      views/              contains view files for the Web application
      web/                contains the entry script and Web resources



REQUIREMENTS
------------

The minimum requirement by this project template that your Web server supports PHP 5.6.0.


INSTALLATION
------------
### Install with Docker

Update your vendor packages

    docker compose run --rm php composer update --prefer-dist
    
Run the installation triggers (creating cookie validation code)

    docker compose run --rm php composer install    
    
Start the container

    docker compose up -d
    
Run migrations
    
    docker exec -it remote_help-php-1 php yii migrate

You can then access the application through the following URL:

    http://127.0.0.1

**NOTES:** 
- Minimum required Docker engine version `17.04` for development (see [Performance tuning for volume mounts](https://docs.docker.com/docker-for-mac/osxfs-caching/))
- The default configuration uses a host-volume in your home directory `.docker-composer` for composer caches
