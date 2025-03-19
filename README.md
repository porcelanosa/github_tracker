# Сканирование репозиториев
1. Запускаем миграции ```php yii migrate/up```
2. Список названий репозиториев хранится в ```data/repos.txt```
3. Запуск синхронизации команда ```php yii sync-github-repos``` Параметр ```-f``` задает частоту обновления в секундах. Например, ```php yii sync-github-repos -f=100```
4. После успешной синхронизации добавляется задача. Слушать очередь можно запустив команду: ```php yii queue/listen``` или ```php yii queue/listen > /dev/null 2>&1 &```
5. Список обновлений доступен по адресу ```/github```

## .env файл
```
YII_ENV=dev
YII_DEBUG=true

DB_HOST=maria_db_wsl
DB_NAME=github_tracker
DB_USER=root
DB_PASSWORD=pass

GITHUB_TOKEN=developer_github_token
```