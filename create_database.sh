#!/bin/bash

echo -e "**********************************\n* MySQL root password is: secret *\n**********************************\n";
docker-compose run db bash -c "echo 'CREATE DATABASE user_persistence_layer_test' | mysql -h 192.168.99.100 -u root -p";
docker-compose run php bash -c "cd ..; php artisan migrate";
