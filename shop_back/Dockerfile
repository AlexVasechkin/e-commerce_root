FROM webdevops/php:7.4-alpine

WORKDIR /app

COPY ./dist .

RUN composer config --no-plugins allow-plugins.php-http/discovery true \
&& composer install
