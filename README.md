# Covid Vaccinate Registration System
### Author: [Md Nazmus Saqib Khan](https://ratulsaqibkhan.github.io/)
##### Version: 1.0.0
## Description: 
This is a simple vaccination registration system.

## Features
1. Users can sign up to get vaccinated at a specific vaccination center.
2. The system automatically processes the registration and schedules the vaccination based on the center's availability.
3. Users can check when their vaccination is scheduled.
4. A reminder email is sent to users the day before their vaccination date.

## Application Stack
1. Frontend: [NextJs](https://nextjs.org/) v14
2. Backend: [Laravel](https://laravel.com/) v11

### Operational Tools
1. MySQL
2. Adminer
3. Redis
4. RabbitMQ
5. Mailhog
6. Docker

## Installation Pre-requisite
1. Docker (version 27.3 or more)
2. Docker compose (v2.29 or more)
3. `make` to run Makefile
4. The following ports are used to run this system: `9500`, `9501`, `9502`, `9503`, `9504`, `9505`, `9506`, `9507`. So make sure to free up these if aleardy used.

## Installation with make
1. First we will get up and running the core services using `make up-core`
    - This will  start the backend server for RabbitMQ, MySQL, Adminer, Redis and Mailhog.
    - [MySQL](http://localhost:9501/) (User:Pass) <b>(root:covid-vaccinate)</b>
    - [Adminer](http://localhost:9502) (User:Pass) <b>(root:covid-vaccinate)</b>
    - [Redis](http://localhost:9503) (Password) <b>(covid-vaccinate)</b>
    - [RabbitMQ](http://localhost:9507) (User:Pass) <<b>(covid-vaccinate:covid-vaccinate)</b>
    - [Mailhog](http://localhost:9504/) (No Auth Required)
2. After that it's time to create DB using `make create-db`. Please run this after the mysql is up running. To check this open Adminer running at `http://localhost:9502` and login with mysql credentials above.
3. Now let's build and run application actual applcation `make up-app`
4. Now run this `make migrate-seed` to populate  the database with some data.
5. For help run `make help`

## Manual Installation
1. `cd docker/.envs` and make all `*.env` files by copying `*.env.example`
2. `cd docker` and make `.env` from `.env.example` and make `docker-compose.override.yml` from `docker-compose.override.dev.yml`
3. `cd docker` and `docker compose up -d redis mysql adminer rabbitmq mailhog`
4. docker exec covid-vaccinate-mysql sh -c "mysql -u root -p'covid-vaccinate' -e 'CREATE DATABASE IF NOT EXISTS \`covid_vaccinate\`;'" for regular application
5. docker exec covid-vaccinate-mysql sh -c "mysql -u root -p'covid-vaccinate' -e 'CREATE DATABASE IF NOT EXISTS \`covid_vaccinate_test\`;'" for regular application testing
6. `cd docker` and `docker network create covid-vaccinate-net`
7. `cd docker` and `docker compose build app`
8. `cd docker` and `docker compose up -d app`
9. `cd docker` and `docker exec covid-vaccinate-app sh -c "composer install"`
10. `cd docker` and `docker exec covid-vaccinate-app sh -c "npm install"`
8. `cd docker` and `docker compose down app`
9. `cd docker` and `docker compose up -d app cron queue`
10. `cd docker` and `docker compose build ui`
12. `cd docker` and `docker compose up -d ui`

### For Testing
1. `cd docker` and `docker compose up app-test`
2. Using make cd to project root dir and run `make up-app-test`

### Postman Collection
1. Find the postman collection [here](./backend/tests/Covid%20Vaccinate.postman_collection.json)

## Application Access
1. Backend will be running in `http://localhost:9500`
2. Frontend will be running in `https://localhost:9506`

## Note:
### Integrate phone messaging
- To integrate phone messaging, you need to install Twilio and configure it in your Laravel project.

### Install `make`
- You can install `make` using `sudo apt-get install make` on Ubuntu or `brew install make`
-  If you are using Windows, you can install `make` using from the [blog](https://leangaurav.medium.com/how-to-setup-install-gnu-make-on-windows-324480f1da69).

### Install Docker
-  You can install `Docker` and `Docker Compose` from the official [site](https://docs.docker.com/engine/install/).

## License
This project is licensed under the [MIT License](./LICENSE).