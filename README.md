Собрать .env файл на основе .env.example
.env параметры которые нужно установить самостоятельно\

#### .env параметры

APP_NAME - название приложения

APP_URL - url адрес бэк-части приложения (http://back.localhost)  
FRONT_APP_URL - адрес фронт-части приложения (http://front.localhost)

DOCKER_WEB_PORT - внешний порт сервера\
DOCKER_USER_ID - ид пользователя докера\
DOCKER_GROUP_ID - ид группы докера\
DOCKER_PQSQL_PORT - внешний порт базы данных\
ADMINER_PORT - внешний порт интерфейса работы с базой данных

DB_CONNECTION - тип используемой базы данных  
DB_HOST - хост или контейнер базы данных
DB_PORT - внутренний порт базы данных  
DB_DATABASE - название базы данных  
DB_USERNAME - логин базы данных

DB_PASSWORD - пароль базы данных

BROADCAST_DRIVER - способ вещания событий  
CACHE_DRIVER - способ хранения кеша
SESSION_DRIVER - способ хранения сессии  
QUEUE_DRIVER - способ формирования очереди событий

REDIRECT_HTTPS - использовать ли принудительный редирет на https (true/false)

WEB_CONTAINER_NAME - имя докер контейнера web, пример значения activity-web  
PHP_CONTAINER_NAME - имя докер контейнера php, пример значения activity-php  
SCHEDULER_CONTAINER_NAME - имя докер контейнера scheduler, пример значения activity-scheduler  
PGSQL_CONTAINER_NAME - имя докер контейнера pgsql, пример значения activity-pgsql  
ADMINER_CONTAINER_NAME - имя докер контейнера adminer, пример значения activity-adminer

Все остальные параметры уже имеют необходимые значения для развертки или автоматически получат их в процессе
1) Собрать и запустить проект командой docker-compose up -d
2) Подключиться к php контейнеру `docker compose exec php bash` и выполнить команды:\
`composer install`\
`composer update`\
`php artisan key:generate`\
`php artisan storage:link`\
`php artisan migrate`\
`php artisan db:seed`


#### Настройка прав доступа для директорий storage и cache
Подключиться к web контейнеру `docker compose exec web bash`  
И выполнить команды:  
`chmod -R 777 /var/www/html/storage`  
`chmod -R 777 /var/www/html/bootstrap/cache`  


#### Изменение паролей пользователей
Чтобы изменить пароль пользователя, необходимо выполнить команду:
`php artisan change-user-password --email=user@example.com --password=somepassword`
