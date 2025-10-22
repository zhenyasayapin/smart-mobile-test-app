FROM php:8.4-fpm

RUN apt-get update \
    && apt-get install -y \
        librabbitmq-dev \
        libssh-dev \
        zip \
        unzip \
        curl \
        gnupg \
        libpq-dev

RUN docker-php-ext-install \
        bcmath \
        sockets \
        pdo_mysql

COPY --from=composer /usr/bin/composer /usr/local/bin/composer

RUN curl -sS https://get.symfony.com/cli/installer | bash && mv /root/.symfony*/bin/symfony /usr/local/bin/symfony

RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

ENV HOST_UID 1000
ENV HOST_GID 1000

RUN groupadd -g ${HOST_GID} appuser
RUN useradd -m -u ${HOST_UID} -g ${HOST_GID} -s /bin/bash appuser

USER appuser

WORKDIR /var/www/html