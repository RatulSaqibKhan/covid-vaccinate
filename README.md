# Covid Vaccinate
### Author: [Md Nazmus Saqib Khan](https://ratulsaqibkhan.github.io/)
#### Date: 2024-10-13
##### Version: 1.0
## Description: 
This is a simple vaccination registration system.

## Installation
1. First we will get up and running the core services using `make up-core`
    - This will  start the backend server for RabbitMQ, MySQL, Adminer, Redis and Mailhog.
    - [MySQL](http://localhost:9501/) (User:Pass) (root:covid-vaccinate)
    - [Adminer](http://localhost:9502) (User:Pass) (root:covid-vaccinate)
    - [Redis](http://localhost:9503) (Password) (covid-vaccinate)
    - [RabbitMQ](http://localhost:9507) (User:Pass) (covid-vaccinate:covid-vaccinate)
    - [Mailhog](http://localhost:9504/) (No Auth Required)
2. After that it's time to create DB using `make create-db`
3. Now let's build and run application actual applcation `make up-app`
4. Now run this `make migrate-seed` to populate  the database with some data.