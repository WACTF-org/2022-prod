FROM node:19-alpine

# Dependencies.
RUN apk update && apk add chromium && rm -rf /var/cache/apk/*

# Create and set app directory.
RUN mkdir -p /usr/src/main-app
WORKDIR /usr/src/main-app
COPY package*.json ./
RUN npm install

# Copy challenge.
COPY . .

# Set permissions.
RUN chown -R root:node /usr/src/main-app/*
RUN chmod -R 755 /usr/src/main-app/*

# Set uploads folder.
RUN mkdir -p /usr/src/main-app/uploads
RUN chown -R root:node /usr/src/main-app/uploads
RUN chmod -R 775 /usr/src/main-app/uploads

EXPOSE 8080
USER node

# Create test. Delete on prod.
# RUN echo '<?xml version="1.0" standalone="no"?><!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.1//EN" "http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd"><svg version="1.1" baseProfile="full" xmlns="http://www.w3.org/2000/svg"> <polygon id="triangle" points="0,0 0,50 50,0" fill="#009900" stroke="#004400"/> <script type="text/javascript"> alert("xss"); </script></svg>' > /usr/src/main-app/uploads/11111111-1111-1111-1111-111111111111.svg

# RUN echo '<?xml version="1.0" standalone="no"?><!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.1//EN" "http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd"><svg version="1.1" baseProfile="full" xmlns="http://www.w3.org/2000/svg"> <polygon id="triangle" points="0,0 0,50 50,0" fill="#009900" stroke="#004400"/> <script type="text/javascript"> alert("xss"); </script></svg>' > /usr/src/main-app/uploads/22222222-2222-2222-2222-222222222222.SVG

# Environment variables.
# ENV TOKEN=thisisatoken
ENV NPM_CONFIG_PREFIX=/home/node/.npm-global
ENV PATH=$PATH:/home/node/.npm-global/bin
ENV OPENSSL_CONF=/dev/null

# Run challenge.
CMD [ "node", "server.js" ]