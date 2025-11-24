FROM php:8.5-apache

RUN a2enmod rewrite && \
	a2enmod headers && \
# dependencies used for php composer
    apt-get update && \
    apt-get install -y git && \
    apt-get install -y zip unzip && \
    apt-get install -y wget

ADD install-composer.sh ./install-composer.sh
RUN chmod ugo+x ./install-composer.sh
RUN ./install-composer.sh

# user for jenkins pipeline
RUN addgroup --gid 1000 build && adduser --uid 1000 --gid 1000 --disabled-password --gecos "" build
RUN usermod -a -G build www-data
