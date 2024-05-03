FROM php:8.3.6

# install laravel dependencies including composer & npm
RUN apt-get update -y &&\
    apt-get install -y openssl zip unzip git nodejs npm &&\
    curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN docker-php-ext-install pdo mysqli pdo_mysql && docker-php-ext-enable pdo_mysql

# set work directory
WORKDIR /jepret

# set the value of `APP_ENV` to `docker` so it will load
# `.env.docker` for settings
ENV APP_ENV=docker

# copy app dependencies and install them
COPY . .
RUN composer install && npm install

# run development server, we are using CMD here because we want
# to be able to override it for database initialization purpose
# notice that we set the host to 0.0.0.0 this is so the laravel
# app can listen to all networks (by default it set to 127.0.0.1)
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
