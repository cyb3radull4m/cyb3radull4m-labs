FROM node:18-alpine
WORKDIR /app
COPY src/package.json ./
RUN npm install --omit=dev
COPY src/ ./

# Two flags: access (pollute role -> admin) and proof (pollute the command gadget -> RCE).
RUN mkdir -p /opt/flags \
 && echo 'FLAG1{prototype_pollution_made_me_admin}' > /opt/flags/access.txt \
 && echo 'FLAG2{polluted_gadget_ran_my_command}'    > /proof.txt \
 && chmod 644 /opt/flags/access.txt /proof.txt

EXPOSE 80
CMD ["node", "server.js"]
