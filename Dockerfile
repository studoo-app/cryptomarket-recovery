FROM dunglas/frankenphp

ENV SERVER_NAME=localhost
ENV APP_ENV=dev
#ENV APP_RUNTIME=Runtime\\FrankenPhpSymfony\\Runtime
#ENV FRANKENPHP_CONFIG="worker ./public/index.php"

# ajoutez des extensions supplémentaires ici :
RUN install-php-extensions \
 pdo_mysql \
 gd \
 intl \
 zip \
 opcache

RUN curl -sSk https://getcomposer.org/installer | php -- --disable-tls && \
       mv composer.phar /usr/local/bin/composer