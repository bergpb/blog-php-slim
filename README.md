#### Blog App with Slim Framework

Requirements: ```docker```, ```docker-compose```, ```php```,  ```composer```

Instructions:

1. Clone project,
2. Enter in project folder,
3. Create a ```.env``` file and add this keys:
    * DB_DRIVER='mysql'
    * DB_HOST='0.0.0.0'
    * DB_DATABASE='mpblog'
    * DB_USERNAME='root'
    * DB_PASSWORD='root'
4. Install dependencies with ```composer install```,
5. Execute ```docker-composer up -d``` to start database,
6. Start app with command: ```php -S 0.0.0.0:8000 -t public/```,
7. Open in browser: [http://localhost:8000](http://localhost:8000),