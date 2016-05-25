# Simple Lumen User Persistence Layer REST API example

This setup is a simple example of a REST API implemented with Lumen which persists user object to a MySQL database.
It uses Swagger for documenting the API specs and Phpunit for making application (integration) tests.

## Why Lumen

Lumen is a micro-framework based on the Laravel framework. It is designed to be
fast and easy to use. It is one of the many other possibilities in the php ecosystem
but is a nice choice because it could be easily ported to Laravel once you decide
you need something more complex.

## Dependenceis

- php and Composer
- Docker toolbox

## Setup

### Clone this repo

```
git clone https://github.com/slavsan/lumen-user-persistence-layer-example-api
cd lumen-user-persistence-layer-example-api
```

### Install Lumen with composer

```
cd images/php/app
composer install
```

This will create the `vendor` directory in the app root which includes Lumen and some other vendor libraries.

### Build the docker images

After you've installed composer first return to the root directory of the project.

```
cd ../../..
```

Then build the docker images with the following commands:

```
docker-machine create -d virtualbox default
docker-machine start
eval $(docker-machine env)
docker-compose build
```

### Create the database

We need to setup our database which is going to be used. There is a create_database.sh shell script provided which
will create it and will run the migration for creating the `user` table. Run it and when prompted, enter
the password `secret` on the command line.

```
./create_database.sh
```

### Run the docker images

```
docker-compose up
```

You can stop the images by pressing `ctrl+c` or leave them in the background
with `ctrl+z`.

You should now have the app accessible at `http://192.168.99.100/api/v1/users`.
In case this is not the correct IP, you can check which is the correct with the following command:

```
docker-machine ip
```

### Run Phpunit tests

```
docker-compose run php bash -c "cd ..; export DB_USERNAME=root && export DB_DATABASE=user_persistence_layer_test && ./vendor/bin/phpunit --coverage-html reports/coverage"
```

This will create a reports/coverage directory with the generated code coverage.

### Explore the API with Swagger UI

If you have Swagger UI installed locally you can explore the API at the following address:

```
http://192.168.99.100/api-specs
```

### Stopping the docker images

```
docker-compose down
```

## ToDo

Things which would make this example more helpful:

- add rate limit for the GET /users route
- add authentication
- add pagination
- add field selection
