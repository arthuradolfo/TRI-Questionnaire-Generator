# Passos para configurar sistema TQG
* Instalar git, docker, docker-compose
* git clone https://github.com/arthuradolfo/moodle-tqg-plugin.git
* git clone https://github.com/arthuradolfo/TRI-Questionnaire-Generator.git

## Configurar Moodle TQG Plugin
* Inside project folder, run `docker-compose up` and wait until the containers are running
* Run `docker cp blocks/tqg_plugin <container-name-moodle_1>:/opt/bitnami/moodle/blocks`
* Run `docker cp mod/tqg <container-name-moodle_1>:/opt/bitnami/moodle/mod`
* You will be able to access Moodle using `localhost`
* You can add the TQG block to your course and create TQG activities

## Configure TQG Backend
* Inside project folder, run `cp .env.example .env` and `docker-compose up` and wait until the containers are running
* Run `docker exec <container-name_myapp_1> php artisan key:generate`
* Run `docker exec <container-name_myapp_1> php artisan passport:install`
* Run `docker exec <container-name_myapp_1> sudo sh install_mirt.sh`
* Run `docker exec -it <container-name_myapp_1> /bin/bash` to enter in container
* Inside container, run `R` to enter in R client
* Inside R client, run `install.packages(‘DBI');`
* Inside R client, run `install.packages(‘odbc');`
* Inside R client, run `install.packages(‘devtools');`
* Inside R client, run `library('devtools')`
* Inside R client, run `install_github('philchalmers/mirt')`
* The REST API will be running on `localhost:3000`


