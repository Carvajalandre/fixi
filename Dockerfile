# Etapa 1: Builder (instala dependencias de Laravel)
FROM composer:2 AS builder
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-scripts --prefer-dist --no-interaction --no-progress --ignore-platform-reqs
COPY . .
RUN composer dump-autoload --optimize

# Etapa 2: Producción
FROM php:8.2-fpm

# Instalar dependencias del sistema y extensiones PHP necesarias
RUN apt-get update && apt-get install -y \
    nginx \
    git \
    unzip \
    libpng-dev \
    libzip-dev \
    libonig-dev \
    libxml2-dev \
    libpq-dev \
    zip \
    curl \
    && docker-php-ext-install pdo_mysql pdo_pgsql zip bcmath gd mbstring \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Copiar Composer desde la etapa builder
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Establecer directorio de trabajo
WORKDIR /app

# Copiar archivos de Laravel desde la etapa builder
COPY --from=builder /app ./

# Eliminar cachés antiguos
RUN rm -f bootstrap/cache/*.php

# Crear directorios necesarios
RUN mkdir -p storage/logs \
    storage/framework/cache \
    storage/framework/sessions \
    storage/framework/views \
    bootstrap/cache

# Asignar permisos correctos
RUN chown -R www-data:www-data /app/storage /app/bootstrap/cache && \
    chmod -R 777 /app/storage /app/bootstrap/cache

# Copiar configuración de Nginx
COPY ./nginx.conf /etc/nginx/sites-enabled/default

# Exponer puerto 80 (Render lo redirige al $PORT automáticamente)
EXPOSE 80

# Comando de inicio con mejor logging
CMD sh -c "\
    echo '=== Iniciando aplicación Laravel ===' && \
    echo '=== Verificando extensiones PHP ===' && \
    php -m && \
    echo '=== Configurando permisos ===' && \
    chown -R www-data:www-data /app/storage /app/bootstrap/cache && \
    chmod -R 777 /app/storage /app/bootstrap/cache && \
    echo '=== Limpiando cachés ===' && \
    php artisan config:clear || true && \
    php artisan cache:clear || true && \
    php artisan route:clear || true && \
    php artisan view:clear || true && \
    echo '=== Verificando variables de entorno ===' && \
    php artisan env && \
    echo '=== Generando clave JWT ===' && \
    php artisan jwt:secret --force || echo 'Error generando JWT secret' && \
    echo '=== Optimizando configuración ===' && \
    php artisan config:cache && \
    echo '=== Probando conexión a BD ===' && \
    php artisan migrate --force || echo 'Error en migraciones (puede ser normal si ya están aplicadas)' && \
    echo '=== Verificando rutas ===' && \
    php artisan route:list && \
    echo '=== Iniciando PHP-FPM ===' && \
    php-fpm -D && \
    echo '=== Iniciando Nginx ===' && \
    nginx -g 'daemon off;'"