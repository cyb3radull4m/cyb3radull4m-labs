FROM node:18-alpine
WORKDIR /app
COPY src/package.json ./
RUN npm install --omit=dev
COPY src/ ./

# Objective: achieve RCE and read this flag.
RUN echo 'FLAG{__proto__ pollution reached a command gadget}' | sed 's/ /_/g' > /flag.txt && chmod 644 /flag.txt

EXPOSE 80
CMD ["node", "server.js"]
