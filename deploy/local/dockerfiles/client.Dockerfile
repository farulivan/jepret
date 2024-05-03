FROM node:20.12.2

WORKDIR /jepret-client
COPY ./package.json ./package-lock.json ./
RUN npm install

COPY ./client/ ./

ENTRYPOINT ["npm", "run", "dev"]
