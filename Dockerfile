FROM ubuntu:latest

ENV DEBIAN_FRONTEND=noninteractive
ENV PHP_VERSION 8.0

RUN apt-get update -yq
RUN apt-get install -yq software-properties-common
RUN add-apt-repository ppa:ondrej/php
RUN apt-get update -yq && apt-get install -yq --no-install-recommends \
    libxml2-dev \
    apt-utils \
    apt-transport-https \
    # Install git
    git \
    # Install apache
    apache2 \
    # Install php 7.4
    php$PHP_VERSION \
    libapache2-mod-php$PHP_VERSION \
    php$PHP_VERSION-cli \
    php$PHP_VERSION-json \
    php$PHP_VERSION-curl \
    php$PHP_VERSION-pgsql \
    php$PHP_VERSION-fpm \
    php$PHP_VERSION-dev \
    php$PHP_VERSION-gd \
    php$PHP_VERSION-pdo \
    php$PHP_VERSION-mysql \
    php$PHP_VERSION-mbstring \
    php$PHP_VERSION-bcmath \
    php$PHP_VERSION-sqlite3 \
    php$PHP_VERSION-xml \
    php$PHP_VERSION-zip \
    php$PHP_VERSION-intl \
    php$PHP_VERSION-sockets \
    php$PHP_VERSION-soap \
    php$PHP_VERSION-xdebug \
    libldap2-dev \
    libaio1 \
    libaio-dev \
    # Install tools
    openssl \
    curl \
    nano \
    net-tools \
    iputils-ping \
    locales \
    php-pear \
    ca-certificates \
    && apt-get clean

#RUN pecl install xdebug \ && docker-php-ext-enable xdebug

RUN rm /etc/localtime

RUN ln -s /usr/share/zoneinfo/America/Mexico_City /etc/localtime

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set locales
RUN locale-gen en_US.UTF-8 en_GB.UTF-8 de_DE.UTF-8 es_ES.UTF-8 es_CO.UTF-8 fr_FR.UTF-8 it_IT.UTF-8 km_KH sv_SE.UTF-8 fi_FI.UTF-8

COPY ./apache.conf /etc/apache2/apache2.conf

# Configure PHP for My Site
COPY my-site.ini /etc/php/$PHP_VERSION/mods-available/
RUN phpenmod my-site

# Configure apache for My Site
RUN a2enmod headers rewrite expires && \
    echo "ServerName localhost" | tee /etc/apache2/conf-available/servername.conf && \
    a2enconf servername

# Configure vhost for My Site
COPY my-site.conf /etc/apache2/sites-available/
RUN a2dissite 000-default && \
    a2ensite my-site.conf

EXPOSE 80 443

WORKDIR /var/www/


CMD apachectl -D FOREGROUND