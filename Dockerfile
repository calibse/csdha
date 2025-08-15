FROM php:8-apache-bookworm

RUN apt-get update
RUN apt-get install -y --no-install-recommends git && apt-get clean
RUN apt-get install -y --no-install-recommends unzip && apt-get clean
RUN apt-get install -y --no-install-recommends zlib1g-dev && apt-get clean
RUN apt-get install -y --no-install-recommends libpng-dev && apt-get clean
RUN apt-get install -y --no-install-recommends libzip-dev && apt-get clean
RUN docker-php-ext-install pdo_mysql
RUN apt-get install -y --no-install-recommends libjpeg-dev && apt-get clean
RUN docker-php-ext-configure gd --with-jpeg && docker-php-ext-install gd
RUN docker-php-ext-install zip 

WORKDIR /usr/local/bin
ADD --chmod=755 https://getcomposer.org/download/2.8.9/composer.phar composer

WORKDIR /var/www/app
COPY composer.json composer.lock . 
RUN composer install --no-dev --no-scripts --no-autoloader --no-progress -n && composer clear-cache

RUN apt-get install -y --no-install-recommends nano && apt-get clean
RUN apt-get install -y --no-install-recommends less && apt-get clean
RUN apt-get install -y --no-install-recommends python3 python3-pip pipx && apt-get clean
RUN apt-get install -y --no-install-recommends pango1.0-tools && apt-get clean

ENV PIPX_HOME=/opt/pipx
ENV PIPX_BIN_DIR=/usr/local/bin
RUN pipx install weasyprint && pip cache purge

COPY container/apache/000-default.conf /etc/apache2/sites-available/000-default.conf
RUN a2enmod rewrite

WORKDIR /var/www/app
COPY . .
RUN composer install --no-dev --no-progress -n && composer clear-cache

RUN a2enmod remoteip
COPY container/apache/server-name.conf /etc/apache2/conf-available/server-name.conf
COPY container/apache/remote-ip.conf /etc/apache2/conf-available/remote-ip.conf
RUN a2enconf server-name
RUN a2enconf remote-ip 
RUN a2enmod headers

CMD ["/var/www/app/container/deploy.sh"]

