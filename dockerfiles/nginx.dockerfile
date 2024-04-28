FROM nginx:stable-alpine

# Change the working directory to Nginx's configuration directory
WORKDIR /etc/nginx/conf.d

# Copy our custom nginx configuration file to the container
COPY nginx/nginx.conf .

# Rename the copied nginx configuration file to default.conf
# To replace the default Nginx configuration with our custom one
RUN mv nginx.conf default.conf

# Change the working directory to the root directory where your web content will be served
WORKDIR /var/www/html

# Copy our app into the working directory (/var/www/html)
# To be served by Nginx
COPY . .
