FROM php:8.4-fpm

ARG UID=1000
ARG GID=1000

ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/
RUN chmod +x /usr/local/bin/install-php-extensions && \
    install-php-extensions \
        pdo_pgsql pgsql redis pcntl bcmath intl opcache zip

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

RUN groupadd -g ${GID} appuser && \
    useradd -u ${UID} -g appuser -m appuser

WORKDIR /var/www/html
USER appuser