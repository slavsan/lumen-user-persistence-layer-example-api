FROM php:fpm
RUN apt-get update && apt-get install -y libmcrypt-dev
RUN docker-php-ext-install mcrypt mbstring tokenizer pdo pdo_mysql
RUN pecl install xdebug
RUN docker-php-ext-enable xdebug
