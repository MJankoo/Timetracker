FROM php:8.3.7-fpm

ARG GROUP_ID
ARG USER_ID

RUN addgroup --gid ${GROUP_ID} --system symfony
RUN adduser --ingroup symfony --system --disabled-password --shell /bin/sh -u ${USER_ID} symfony

RUN apt-get update --fix-missing && \
        apt-get install -y libzip-dev
RUN apt-get install -y nginx

RUN docker-php-ext-install pdo_mysql
RUN docker-php-ext-install zip

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer --version=2.7.6

COPY ./bin/entrypoint.sh /etc/entrypoint.sh

RUN mkdir -p /var/log/nginx/body \
    mkdir -p /var/lib/nginx/body \
    && chown -R $USER_ID:$GROUP_ID /var/lib/nginx \
    && chown -R $USER_ID:$GROUP_ID /run /var/log/nginx

WORKDIR /var/www/symfony

RUN chown -R symfony:symfony /var/www/symfony

EXPOSE 8000
EXPOSE 443

ENTRYPOINT ["sh", "/etc/entrypoint.sh"]
