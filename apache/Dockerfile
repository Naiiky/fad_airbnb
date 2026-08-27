# 1. BASE IMAGE & ARGUMENTS
FROM php:8.4-apache

# Arguments pour les versions
ARG NVM_VERSION=0.39.7
ARG NODE_VERSION=20

# 2. SYSTÈME ET DÉPENDANCES
# Installation des outils système et des dépendances nécessaires
# aux extensions PHP, notamment GD avec support JPEG/PNG/WebP.
RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        git \
        unzip \
        wget \
        curl \
        nano \
        libpng-dev \
        libjpeg62-turbo-dev \
        libfreetype6-dev \
        libwebp-dev \
        libicu-dev \
    && rm -rf /var/lib/apt/lists/*

# 3. EXTENSIONS PHP
# Configuration de GD avec support FreeType, JPEG et WebP,
# puis installation des extensions utiles pour Symfony.
RUN docker-php-ext-configure gd \
        --with-freetype \
        --with-jpeg \
        --with-webp \
    && docker-php-ext-install -j$(nproc) \
        gd \
        intl \
        pdo \
        pdo_mysql \
        opcache

# 4. COMPOSER
# Récupération de Composer depuis l'image officielle.
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 5. SYMFONY CLI
# Installation de Symfony CLI via l'installateur officiel.
RUN wget https://get.symfony.com/cli/installer -O - | bash \
    && mv /root/.symfony*/bin/symfony /usr/local/bin/symfony

# 6. XDEBUG
# Installation et activation de Xdebug via PECL.
RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

# Configuration PHP personnalisée.
COPY custom-php.ini /usr/local/etc/php/conf.d/

# 7. NVM ET NODE.JS
ENV NVM_DIR=/root/.nvm
ENV PATH=$NVM_DIR/versions/node/v$NODE_VERSION/bin:$PATH

RUN curl -o- https://raw.githubusercontent.com/nvm-sh/nvm/v$NVM_VERSION/install.sh | bash \
    && /bin/bash -c "source $NVM_DIR/nvm.sh \
        && nvm install $NODE_VERSION \
        && nvm alias default $NODE_VERSION \
        && nvm use default \
        && ln -sf $(command -v node) /usr/local/bin/node \
        && ln -sf $(command -v npm) /usr/local/bin/npm \
        && ln -sf $(command -v npx) /usr/local/bin/npx" \
    && rm -rf /tmp/*

# 8. CONFIGURATION APACHE
# Le DocumentRoot pointe vers le dossier public de Symfony
# et le module rewrite est activé.
RUN sed -i 's|/var/www/html|/var/www/html/public|g' \
        /etc/apache2/sites-available/000-default.conf \
    && echo "<Directory /var/www/html/public>\n\
    AllowOverride All\n\
    Require all granted\n\
    </Directory>" >> /etc/apache2/apache2.conf \
    && a2enmod rewrite

# 9. PERMISSIONS
# Apache utilise www-data.
RUN chown -R www-data:www-data /var/www/html \
    && find /var/www/html -type d -exec chmod 775 {} \; \
    && find /var/www/html -type f -exec chmod 644 {} \;

# 10. PORT APACHE
EXPOSE 80

# Le CMD par défaut de php:8.4-apache démarre Apache.
# CMD ["apache2-foreground"]
