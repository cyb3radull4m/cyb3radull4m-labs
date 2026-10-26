FROM node:18-alpine
WORKDIR /app
COPY src/package.json ./
RUN npm install --omit=dev
COPY src/ ./

# Two flags: access (reach the admin-only preview) and proof (SSTI -> RCE).
RUN mkdir -p /opt/flags \
 && echo 'FLAG1{client_trusted_role_is_no_role}' > /opt/flags/access.txt \
 && echo 'FLAG2{ejs_ssti_executed_server_side}'  > /proof.txt \
 && chmod 644 /opt/flags/access.txt /proof.txt

EXPOSE 80
CMD ["node", "server.js"]
