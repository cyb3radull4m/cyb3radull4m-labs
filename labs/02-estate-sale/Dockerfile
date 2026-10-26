FROM php:8.2-apache
COPY src/ /var/www/html/
RUN chown -R www-data:www-data /var/www/html

# Two flags: access (session forged via object injection) and proof (RCE via the POP gadget).
RUN mkdir -p /opt/flags \
 && echo 'FLAG1{forged_remembertoken_impersonation}' > /opt/flags/access.txt \
 && echo 'FLAG2{pop_gadget_wrote_a_webshell}'        > /proof.txt \
 && chmod 644 /opt/flags/access.txt /proof.txt \
 && chown www-data:www-data /opt/flags/access.txt

EXPOSE 80
