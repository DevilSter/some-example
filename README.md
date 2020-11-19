# some-example
в hosts прописать 
127.0.0.1 example.loc

Переходим в папку docker 

docker-compose build

База db_simple должна будет создаться автоматически

docker-compose up (для запуска контейнеров)

переходим в контейнер с вебом 

make jumpin

Там инициируем миграции

1 Создаем нужные таблицы 
   php bin/console doctrine:migrations:migrate
   
2 Заполняем фикстуры
  

