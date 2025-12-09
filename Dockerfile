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

# Instalar MongoDB PHP Driver
RUN pecl install mongodb && docker-php-ext-enable mongodb

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
