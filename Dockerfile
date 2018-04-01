FROM php:5.6-apache

# RUN apt-get update && apt-get install php5-memcached memcached php5-cli php5-mysql php5-dev -y
RUN docker-php-ext-install mysqli && docker-php-ext-install pcntl
RUN apt-get update && apt-get install -y memcached libmemcached-dev zlib1g-dev \
    && pecl install memcached-2.2.0 \
    && docker-php-ext-enable memcached
RUN apt-get install -y ssmtp mailutils && \
  apt-get clean && \
  echo "FromLineOverride=YES" >> /etc/ssmtp/ssmtp.conf

# Here is the gmail configuration (or change it to your private smtp server)
RUN echo "mailhub=sendmail:25" >> /etc/ssmtp/ssmtp.conf

# Set up php sendmail config
RUN echo "sendmail_path=sendmail -i -t" >> /usr/local/etc/php/conf.d/php-sendmail.ini
ADD / /var/www/html
# RUN mkdir -p /usr/local/etc/php/conf.d
COPY docker/php.ini /usr/local/etc/php/php.ini
# RUN cd /usr/bin && apt-get install -y wget && wget https://raw.githubusercontent.com/docker-library/php/7707290c53077c0fbdbe8c768e98c51ba06025f1/7.2/stretch/cli/docker-php-ext-enable && chmod a+x ./docker-php-ext-enable
RUN cd /var/www/html/core/c_combat/GiCoSys && phpize && ./configure --enable-GiCoSys && make install && docker-php-ext-enable GiCoSys

CMD memcached & apache2-foreground
EXPOSE 80