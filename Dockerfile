FROM webdevops/php-nginx:8.3-alpine

LABEL maintainer="Fma965" \
    description="nginx php-8 games-manager-frontend"

ENV WEB_DOCUMENT_ROOT='/app/panel'
ENV PHP_DATE_TIMEZONE='Europe/London'
ENV TZ='Europe/London'

COPY /app/ /app/
COPY api.conf /opt/docker/etc/nginx/conf.d/api.conf

RUN composer install -d /app