FROM php:8.3.6

# install laravel dependencies including composer & npm
RUN apt-get update -y &&\
    apt-get install -y openssl zip unzip git nodejs npm &&\
    curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN docker-php-ext-install pdo mysqli pdo_mysql && docker-php-ext-enable pdo_mysql
RUN apt-get install -y \
		libfreetype-dev \
		libjpeg62-turbo-dev \
		libpng-dev \
	&& docker-php-ext-configure gd --with-freetype --with-jpeg \
	&& docker-php-ext-install -j$(nproc) gd

# set work directory
WORKDIR /jepret

# set the value of `APP_ENV` to `docker` so it will load
# `.env.docker` for settings
ENV APP_ENV=testing

# copy app dependencies and install them
COPY . .

# run development server, we are using CMD here because we want
# to be able to override it for database initialization purpose
# notice that we set the host to 0.0.0.0 this is so the laravel
# app can listen to all networks (by default it set to 127.0.0.1)
# we use port 8070 since it is the port stated in the challenge
CMD composer install && php artisan migrate --seed && php artisan test --filter=HomeTest
# CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8070"]
