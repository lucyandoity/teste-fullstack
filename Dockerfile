FROM php:7.4.33-apache

# instalar dependencias do sistema
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    libicu-dev \
    libxml2-dev \
    libsodium-dev \
    unzip \
    git \
    && rm -rf /var/lib/apt/lists/*

# configurar gd
RUN docker-php-ext-configure gd --with-freetype --with-jpeg

# instalar extensoes PHP
RUN docker-php-ext-install \
    pdo \
    pdo_mysql \
    mysqli \
    gd \
    intl \
    zip

# configurar PHP para uploads e MySQL local infile
RUN echo "upload_max_filesize = 25M" >> /usr/local/etc/php/conf.d/uploads.ini \
    && echo "post_max_size = 25M" >> /usr/local/etc/php/conf.d/uploads.ini \
    && echo "memory_limit = 256M" >> /usr/local/etc/php/conf.d/uploads.ini \
    && echo "mysqli.allow_local_infile = On" >> /usr/local/etc/php/conf.d/mysql.ini \
    && echo "pdo_mysql.allow_local_infile = On" >> /usr/local/etc/php/conf.d/mysql.ini

# habilitar modulos Apache necessarios
RUN a2enmod rewrite headers expires

# instalar composer
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

# diretorio do projeto
WORKDIR /var/www/html

# copiar aplicacao
COPY . /var/www/html

# instalar dependencias do Composer
RUN composer install --no-interaction --no-dev --optimize-autoloader

# ajustar permissoes
RUN chown -R www-data:www-data /var/www/html/app/tmp \
    && chmod -R 777 /var/www/html/app/tmp \
    && chown -R www-data:www-data /var/www/html/app/webroot \
    && chmod -R 777 /var/www/html/app/webroot

# configurar DocumentRoot do Apache para CakePHP
ENV APACHE_DOCUMENT_ROOT /var/www/html/app/webroot
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# expor porta 80
EXPOSE 80

# iniciar apache
CMD ["apache2-foreground"]