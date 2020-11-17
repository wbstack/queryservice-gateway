FROM node:12

WORKDIR /redbird

COPY ./ .
RUN npm install

EXPOSE 80
CMD ["node", "main.js"]
