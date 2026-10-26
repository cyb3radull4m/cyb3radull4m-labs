FROM php:8.2-apache
RUN apt-get update && apt-get install -y --no-install-recommends iputils-ping
COPY src/ /var/www/html/

# Two flags, OSWE-style: access (auth bypass) and proof (RCE). Values are NOT in the source tree.
RUN mkdir -p /opt/flags \
 && echo 'FLAG1{access_the_console_with_a_magic_hash}' > /opt/flags/access.txt \
 && echo 'FLAG2{command_injection_popped_a_shell}'    > /proof.txt \
 && chmod 644 /opt/flags/access.txt /proof.txt

EXPOSE 80
