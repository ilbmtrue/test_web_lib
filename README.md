## Тестовое задание

Приложение должно содержать три страницы:  
•	список книг  
•	добавление книги  
•	редактирование книги  
  
По каждой книге нужно хранить и отображать информацию:  
•	имя  
•	год  
•	автор  
  
маленькая ремарка: добавление, изменение, удаление происходит на одной странице  
  
  
Требования:  
•	Symfony >= 5.0  
•	PHP >= 7.4  
•	MySQL  
  
Для деплоя  

git clone https://github.com/ilbmtrue/test_web_lib  
cd test_web_lib  
composer install  

Указываем настройки БД в файле .env  
  
DATABASE_URL=mysql://user:password@127.0.0.1:3306/web_lib_db?serverVersion=5.7  
user:password  - имя и пароль **существующего** пользователя в БД

```
    php bin/console doctrine:database:create --if-not-exists  
    php bin/console make:migration  
    php bin/console doctrine:migrations:migrate  
```

также есть оссобенности при локальном развертывании под [Nginx](https://symfony.com/doc/current/setup/web_server_configuration.html#nginx)

если все правильно настроено, то результат выглядит [так](https://test-web-lib.herokuapp.com/)
