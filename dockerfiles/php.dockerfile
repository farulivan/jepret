FROM php:8.2-fpm-alpine

# Set the working directory inside the container to /var/www/html.
# This directory is commonly used to serve web content in many web servers, including Nginx and Apache.
WORKDIR /var/www/html

# Copy our app into the working directory (/var/www/html)
# For our application to run
COPY . .

# Installs and enables the PDO extension and the pdo_mysql driver
# Allowing PHP applications to interact with MySQL databases using the PDO interface.
RUN docker-php-ext-install pdo pdo_mysql

# Change the ownership recursively
# www-data is a standard user under which the web server (Nginx or Apache) runs.
# This is done for security reasons and to ensure that the web server has the necessary permissions to access and serve the PHP application's files.
RUN chown -R www-data:www-data /var/www/html
