FROM php:8.2-apache

# ping is needed by the looking-glass probe
RUN apt-get update && apt-get install -y --no-install-recommends iputils-ping

COPY src/ /var/www/html/

# The objective: retrieve this flag via RCE. (Value is NOT in the source tree.)
RUN echo 'FLAG{typ3_juggl1ng_into_a_l34ky_p1pe}' > /flag.txt && chmod 644 /flag.txt

EXPOSE 80
