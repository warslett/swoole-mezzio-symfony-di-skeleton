FROM phpswoole/swoole:latest as platform

FROM platform as dev

ENV COMPOSER_HOME /tmp

RUN pecl install inotify \
    && docker-php-ext-enable inotify \
    && php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php \
    && php -r "unlink('composer-setup.php');" \
    && mv composer.phar /usr/local/bin/composer \
    && rm -rf /tmp/* /tmp/.htaccess

FROM dev as prod

COPY . /app
WORKDIR /app

RUN composer install --no-dev && rm /usr/local/bin/composer

ENTRYPOINT php application.php
