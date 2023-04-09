# Use alpine for its efficiency and small attack surface.
FROM php:7.4.33-fpm-alpine

# Install packages
RUN apk update
RUN apk add --no-cache nginx liblockfile bash shadow

# Copy code and config
COPY ./docker-files/www /var/www/
COPY ./docker-files/conf/nginx.conf /etc/nginx/nginx.conf
COPY ./docker-files/conf/cloudkey-webui /etc/nginx/sites-enabled/
COPY ./docker-files/conf/fastcgi.conf /etc/nginx/fastcgi.conf
COPY ./docker-files/conf/php-fpm.conf /usr/local/etc/php-fpm.conf
COPY ./docker-files/bin/asdf-systool /sbin/
COPY ./docker-files/bin/pwcheck /sbin/
COPY ./docker-files/conf/usermap.conf /etc/security/usermap.conf

# Create runtime folders
RUN mkdir -p /run/php/sessions /var/run/php/ /run/nginx/
RUN chown -R www-data:www-data /var/run/php /run/nginx /var/lib/nginx/ /var/log/nginx

# Change www-data password
RUN echo "www-data:www-data" | chpasswd

USER root

EXPOSE 80

CMD /usr/local/sbin/php-fpm -R -D; nginx -g "daemon off;"