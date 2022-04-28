FROM php:8.0-cli
WORKDIR /app

RUN apt-get update && apt-get install -y wget libicu-dev
RUN docker-php-ext-install intl
RUN wget https://get.symfony.com/cli/installer -O - | bash
RUN mv /root/.symfony/bin/symfony /usr/local/bin/symfony

VOLUME /app

CMD symfony server:start --no-tls