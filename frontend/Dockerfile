FROM node:14.17.0-alpine

WORKDIR /app

COPY . .

RUN npm install
RUN npm install -g npm@7.13.0
RUN npm rebuild node-sass

EXPOSE 3000

CMD ["npm", "start"]
