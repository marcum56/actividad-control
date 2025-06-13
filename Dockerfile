FROM php:8.2-apache

# Instalar extensiones necesarias para PostgreSQL
RUN apt-get update && apt-get install -y libpq-dev \
    && docker-php-ext-install pgsql pdo_pgsql

# Copiar el proyecto al servidor
COPY . /var/www/html/

# Habilitar mod_rewrite si lo necesitas
RUN a2enmod rewrite

