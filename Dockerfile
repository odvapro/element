FROM php:7.1.20-apache

RUN apt-get -y update --fix-missing
RUN apt-get upgrade -y

# Install useful tools
RUN apt-get -y install apt-utils nano wget dialog

# Install important libraries
RUN apt-get -y install --fix-missing apt-utils build-essential git curl libcurl3 libcurl3-dev zip

# Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install xdebug
RUN pecl install xdebug-2.5.0
RUN docker-php-ext-enable xdebug

# Other PHP7 Extensions

RUN apt-get -y install libmcrypt-dev
RUN docker-php-ext-install mcrypt

RUN apt-get -y install libsqlite3-dev libsqlite3-0 mysql-client
RUN docker-php-ext-install pdo_mysql
RUN docker-php-ext-install pdo_sqlite
RUN docker-php-ext-install mysqli

RUN docker-php-ext-install curl
RUN docker-php-ext-install tokenizer
RUN docker-php-ext-install json

RUN apt-get -y install zlib1g-dev
RUN docker-php-ext-install zip

RUN apt-get -y install libicu-dev
RUN docker-php-ext-install -j$(nproc) intl

RUN docker-php-ext-install mbstring

RUN apt-get install -y libfreetype6-dev libjpeg62-turbo-dev libpng-dev
RUN docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/
RUN docker-php-ext-install -j$(nproc) gd exif

#Phalcon
	RUN wget --content-disposition --output-document /tmp/phalcon.deb https://packagecloud.io/phalcon/stable/packages/debian/jessie/php7.1-phalcon_3.4.5-1+php7.1_amd64.deb/download.deb \
		&& mkdir /tmp/pkg \
		&& dpkg-deb -R /tmp/phalcon.deb /tmp/pkg \
		&& cp /tmp/pkg/usr/lib/php/*/phalcon.so "$(php-config  --extension-dir)/phalcon.so" \
		&& pecl install --force psr 1> /dev/null \
		&& echo "extension=psr.so" > "$PHP_INI_DIR/conf.d/docker-php-ext-psr.ini" \
		&& echo "extension=phalcon.so" > "$PHP_INI_DIR/conf.d/docker-php-ext-phalcon.ini"

# Enable apache modules
RUN a2enmod rewrite headers

# element
RUN sed -i 's/\/html//g' /etc/apache2/sites-available/000-default.conf

# RUN apt-get update
RUN curl -sL https://deb.nodesource.com/setup_12.x | bash
RUN apt-get -y install nodejs

WORKDIR /var/www/

RUN echo "<?php\nheader('Location: /element/');" > index.php

WORKDIR /var/www/public
WORKDIR /var/www/element

RUN rm -rf *

# server files
VOLUME /usr/local/etc/php/php.ini

VOLUME /var/www/element
VOLUME /var/www/public
