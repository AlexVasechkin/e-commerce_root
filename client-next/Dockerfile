FROM node:18.13.0-alpine

WORKDIR /usr/src/app

COPY ./app .

RUN    rm -rfv ./node_modules \
    && rm package-lock.json

RUN npm install \
&& npm run build
