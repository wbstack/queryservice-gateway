FROM node:16

LABEL org.opencontainers.image.source="https://github.com/wbstack/queryservice-gateway"

WORKDIR /redbird

COPY ./ .
RUN npm install

EXPOSE 80
CMD ["node", "main.js"]
