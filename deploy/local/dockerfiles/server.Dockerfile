FROM php:8.3.6

# install laravel dependencies including composer & npm
RUN apt-get update -y &&\
    apt-get install -y openssl zip unzip git nodejs npm &&\
    curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN docker-php-ext-install pdo mysqli pdo_mysql && docker-php-ext-enable pdo_mysql

# set work directory
WORKDIR /app

# set the value of `APP_ENV` to `docker` so it will load
# `.env.docker` for settings
ENV APP_ENV=docker

# copy app dependencies and install them
COPY . .
RUN composer install && npm install

# run development server, we are using CMD here because we want
# to be able to override it for database initialization purpose
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
