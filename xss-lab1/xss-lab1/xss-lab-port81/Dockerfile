FROM php:7.4-apache
RUN apt-get update && apt-get install -y sqlite3 libsqlite3-dev && docker-php-ext-install pdo_sqlite
COPY ./app /var/www/html/
RUN chown -R www-data:www-data /var/www/html && chmod -R 755 /var/www/html
RUN mkdir -p /var/www/html/db && chmod -R 777 /var/www/html/db
