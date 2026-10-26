FROM node:18-alpine
WORKDIR /app
COPY src/package.json ./
RUN npm install --omit=dev
COPY src/ ./

# Objective: achieve RCE and read this flag.
RUN echo 'FLAG{ejs_templates_run_server_side_js}' > /flag.txt && chmod 644 /flag.txt

EXPOSE 80
CMD ["node", "server.js"]
