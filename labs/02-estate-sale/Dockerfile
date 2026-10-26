FROM php:8.2-apache

COPY src/ /var/www/html/

# The app writes cache artifacts into the webroot, so it owns it.
RUN chown -R www-data:www-data /var/www/html

# Objective: achieve RCE and read this flag. (Not present in the source tree.)
RUN echo 'FLAG{unserialize_is_a_cod3_execution_sink}' > /flag.txt && chmod 644 /flag.txt

EXPOSE 80
