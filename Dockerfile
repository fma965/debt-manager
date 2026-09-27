FROM serversideup/php:8.4-fpm-nginx-alpine

LABEL maintainer="Fma965" \
    description="nginx php-8 games-manager-frontend"

ENV NGINX_WEBROOT='/app/panel'

ENV PHP_DATE_TIMEZONE='Europe/London'
ENV TZ='Europe/London'

ARG APP_VERSION=dev

# Build steps need root; the container itself runs as www-data
USER root

COPY /app/ /app/
RUN echo "$APP_VERSION" > /app/VERSION
COPY api.conf /etc/nginx/conf.d/api.conf

RUN composer install -d /app

USER www-data

EXPOSE 8080 8081