# Assuming this Dockerfile is located in ./dockerfiles/npm.dockerfile

# Use the latest Node.js image
FROM node:latest

# Set the working directory inside the container to match the working_dir specified in docker-compose.yml
WORKDIR /var/www/html/client

# Copy package.json and package-lock.json (or yarn.lock) from your project into the image
COPY ./client/package*.json ./

# Install project dependencies
RUN npm i

# Copy the rest of your client app's source code into the image
COPY ./client .

# Install project dependencies
RUN npm run build

# Expose the port your app runs on
EXPOSE 8080
