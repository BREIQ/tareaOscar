# Dockerfile
FROM php:8.1-apache

# Habilitar mod_rewrite
RUN a2enmod rewrite

# Instalar extensiones necesarias
RUN apt-get update && apt-get install -y \
    wget \
    gnupg \
    git \
    curl \
    && rm -rf /var/lib/apt/lists/*

# Instalar dependencias para MongoDB
RUN apt-get update && apt-get install -y \
    libssl-dev \
    && rm -rf /var/lib/apt/lists/*

# Instalar MongoDB PHP Driver
RUN pecl install mongodb && docker-php-ext-enable mongodb

# Instalar Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Crear archivo composer.json para PHP MongoDB Library
RUN echo '{\n  "require": {\n    "mongodb/mongodb": "^1.15"\n  }\n}' > /tmp/composer.json && \
    cd /tmp && composer install --no-dev --no-interaction && \
    cp -r /tmp/vendor /var/www/html/ && \
    rm -rf /tmp/composer.json /tmp/vendor

# Configurar DocumentRoot
RUN sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/sites-available/000-default.conf

# Establecer permisos
RUN chown -R www-data:www-data /var/www/html

# Copiar configuración de Apache
COPY apache.conf /etc/apache2/sites-available/000-default.conf

# Exponer puerto
EXPOSE 80

# Comando por defecto
CMD ["apache2-foreground"]
