###### Local installation

1. Add to hosts file
    ```
    127.0.0.1 casinoapi.local
    ```
2. Build & start the containers 
    ```
    $ cd docker
    $ cp .env.dist .env
    $ docker-compose up -d
    ```
3. Install dependencies, create DB structure
    ```
    $ docker-compose exec workspace bash
    container$ composer install 
    container$ ./bin/console doctrine:schema:update --force
    ```
4. API is exposed on URL
http://casinoapi.local:8031/api

5. Generate keys and configure them in .env[.local]/environment
