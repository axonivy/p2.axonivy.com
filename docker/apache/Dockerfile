FROM php:7.2-apache

RUN a2enmod rewrite && \
	a2enmod headers && \
# used for php composer
    apt-get update && \
    apt-get install -y git && \
# used for php composer
    apt-get update && \
    apt-get install -y zip unzip && \
# composer itself
    php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" && \
    php -r "if (hash_file('SHA384', 'composer-setup.php') === '544e09ee996cdf60ece3804abc52599c22b1f40f4323403c44d44fdfdd586475ca9813a858088ffbc1f233e9b180f061') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;" && \
    php composer-setup.php --version=1.6.3 --install-dir=/var && \
    php -r "unlink('composer-setup.php');" && \
    mv /var/composer.phar /usr/local/bin/composer && \
# add user and group 1000 for jenkins build
    addgroup --gid 1000 build && adduser --uid 1000 --gid 1000 --disabled-password --gecos "" build