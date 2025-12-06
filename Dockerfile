FROM php:7.2-apache AS base

# Configuração de repositórios legados (Debian Buster EOL)
RUN echo "deb http://archive.debian.org/debian buster main" > /etc/apt/sources.list && \
    echo "deb http://archive.debian.org/debian-security buster/updates main" >> /etc/apt/sources.list && \
    echo "Acquire::Check-Valid-Until \"false\";" > /etc/apt/apt.conf.d/99no-check-valid-until

# Instalação de dependências e extensões PHP
RUN apt-get update && apt-get install -y \
    libicu-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    mariadb-client \
    libpng-dev \
    libmcrypt-dev \
    && pecl install mcrypt-1.0.1 \
    && docker-php-ext-enable mcrypt \
    && docker-php-ext-install \
    intl \
    pdo_mysql \
    zip \
    gd

RUN a2enmod rewrite

WORKDIR /var/www/html

# ============================================
# DEVELOPMENT: usa volumes, não copia código
# ============================================
FROM base AS dev

RUN chown -R www-data:www-data /var/www/html

# ============================================
# PRODUCTION: copia código e configura tudo
# Build: docker build --target prod -t app:prod .
# ============================================
FROM base AS prod

# 1. Copia o código fonte
COPY . /var/www/html/

# 2. Configura database.php
RUN printf '<?php\n\
class DATABASE_CONFIG {\n\
    public $default = array(\n\
        "datasource" => "Database/Mysql",\n\
        "persistent" => false,\n\
        "host" => "db",\n\
        "login" => "doity_user",\n\
        "password" => "doity_password",\n\
        "database" => "desafio_doity",\n\
        "prefix" => "",\n\
        "encoding" => "utf8mb4",\n\
    );\n\
}\n' > /var/www/html/app/Config/database.php

# 3. Cria diretórios de cache e uploads
RUN mkdir -p /var/www/html/app/tmp/cache/models \
    && mkdir -p /var/www/html/app/tmp/cache/persistent \
    && mkdir -p /var/www/html/app/tmp/cache/views \
    && mkdir -p /var/www/html/app/tmp/logs \
    && mkdir -p /var/www/html/app/tmp/tests \
    && mkdir -p /var/www/html/app/webroot/img/uploads

# 4. Ajusta permissões finais
RUN chown -R www-data:www-data /var/www/html && \
    chmod -R 755 /var/www/html/app/tmp && \
    chmod -R 755 /var/www/html/app/webroot/img/uploads
