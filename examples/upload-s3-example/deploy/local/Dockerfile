FROM node:20.12.2-alpine3.18
WORKDIR /app
COPY ./package.json ./package-lock.json ./
RUN npm install

COPY ./client.html ./server.mjs ./
ENTRYPOINT ["node", "server.mjs"]