FROM ubuntu:14.04

RUN add-apt-repository ppa:ondrej/php -y
RUN apt-get update
RUN apt-get install -y php7.0

RUN curl -sS https://getcomposer.org/installer | sudo php -- --install-dir=/usr/local/bin --filename=composer

EXPOSE 8000

RUN app/console server:run
