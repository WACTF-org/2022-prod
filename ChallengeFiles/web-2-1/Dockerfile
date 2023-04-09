FROM node:19-alpine
ENV PATH=$PATH:/home/node/.npm-global/bin

# Dependencies.
RUN apk update && apk add chromium && rm -rf /var/cache/apk/*

RUN mkdir -p /usr/app/
WORKDIR /usr/app/
COPY package.json .
RUN npm install
COPY . .

EXPOSE 80
USER node
CMD [ "node", "index.js" ]