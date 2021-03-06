FROM php:apache-buster

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" &\  
    php  composer-setup.php --install-dir=/usr/bin --filename=composer &\
    php -r "unlink('composer-setup.php');" 

RUN apt-get update && apt-get install git -y

RUN apt-get update &\
    apt-get install -y libcurl4-openssl-dev pkg-config libssl-dev &\
    apt-get install php-pear &\
    pecl install mongodb &\
    echo "extension=mongodb.so" >> /usr/local/etc/php/conf.d/docker-php-ext-mongodb.ini

ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf