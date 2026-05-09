FROM php:8.3-fpm

RUN apt-get update && apt-get install -y \
    git curl libpng-dev libonig-dev \
    libxml2-dev zip unzip gnupg2 \
    apt-transport-https ca-certificates

# Instalar driver SQL Server (método moderno)
RUN curl -sSL https://packages.microsoft.com/keys/microsoft.asc \
        | gpg --dearmor > /usr/share/keyrings/microsoft-prod.gpg \
    && curl -sSL https://packages.microsoft.com/config/debian/12/prod.list \
        > /etc/apt/sources.list.d/mssql-release.list \
    && apt-get update \
    && ACCEPT_EULA=Y apt-get install -y msodbcsql18 unixodbc-dev \
    && pecl install sqlsrv pdo_sqlsrv \
    && docker-php-ext-enable sqlsrv pdo_sqlsrv

RUN docker-php-ext-install pdo mbstring exif pcntl bcmath gd

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN groupadd -g 1000 www && useradd -u 1000 -g www -s /bin/sh www

WORKDIR /var/www
COPY --chown=www:www . .

USER www
EXPOSE 9000
CMD ["php-fpm"]
