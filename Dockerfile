# Utiliser l'image PHP 8.1-FPM comme base
FROM php:8.3-fpm

# Installer les dépendances nécessaires
RUN apt-get update && apt-get install -y \
    curl \
    git \
    unzip \
    && docker-php-ext-install pdo pdo_mysql \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Installer Symfony CLI
RUN curl -sS https://get.symfony.com/cli/installer | bash && \
    mv ~/.symfony*/bin/symfony /usr/local/bin/symfony

# Installer Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# installer OPcache qui est un accélerateur PHP qui améliore les performances
RUN docker-php-ext-install opcache 

# Installer les dépendances pour l'extension intl
RUN apt-get update && apt-get install -y \
    libicu-dev \
    && docker-php-ext-install intl \
    && docker-php-ext-enable intl


