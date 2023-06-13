from php:8.1-fpm-alpine

run wget https://getcomposer.org/installer -O ./composer.php \
    && php ./composer.php --install-dir=/bin  --filename=composer \
    && rm ./composer.php \
    && apk add libpq libpq-dev \
    && docker-php-ext-install pdo_pgsql \
    && apk del libpq-dev
