var port = 80
if (process.argv[2]) {
    var port = process.argv[2];
}

var r = require("./resolver.js")

var proxy = new require('redbird')({
        port: port,
        resolvers: [r.defaultResolver],
        proxyTimeout: parseInt(process.env.PROXY_TIMEOUT || 5 * 60, 10) * 1000
    })
