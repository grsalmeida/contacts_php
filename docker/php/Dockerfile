FROM php:8.2-fpm

# Instala extensões e dependências
RUN apt-get update && apt-get install -y \
    zip unzip curl libzip-dev libpng-dev libonig-dev libxml2-dev \
    && docker-php-ext-install pdo_mysql mbstring zip exif pcntl bcmath gd \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug

    # Instala o Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Criar usuário configurável
ARG LOCAL_UID=1000
ARG LOCAL_GID=1000
RUN groupadd -g ${LOCAL_GID} vscode && \
    useradd -m -u ${LOCAL_UID} -g vscode -s /bin/bash vscode

# Ajustar permissões do diretório de trabalho
RUN chown -R vscode:vscode /var/www

# Mudar para o usuário não privilegiado
USER vscode

WORKDIR /var/www