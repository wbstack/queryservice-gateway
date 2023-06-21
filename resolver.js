var parse = require('url-parse'),
    XMLHttpRequest = require("xmlhttprequest").XMLHttpRequest
    TTLCache = require('@isaacs/ttlcache')

var domainForResolver = function domainForResolver(req) {

    /**
     * Requests will be made to a location such as:
     * (www.)?mywiki.com/query/sparql
     * And the host header will be set to reflect that.
     *
     * Example docker development local headers: { host: 'localhost:8085' }
     */

    var hostHeader = null
    if(req.headers["x-forwarded-host"]){
        hostHeader = req.headers["x-forwarded-host"]
    }else if (req.headers.host) {
        hostHeader = req.headers.host
    }

    if(!hostHeader) {
        return null
    }

    var parsedHostHeader = parse("http://"+hostHeader)
    // Remove www. (if present)
    if(parsedHostHeader.host.substr(0,4) === 'www.') {
        return parsedHostHeader.host.substr(4)
    }

    return parsedHostHeader.host

}

var defaultResolver = function resolver(host, url, req) {
    var wikiDomain = domainForResolver(req)
    if (wikiDomain === null) {
        return Promise.resolve(null)
    }

    return fetchWikiForDomain(wikiDomain)
        .then(function (response) {
            if (!response || !response.data) {
                return null
            }

            let backend = response.data.wiki_queryservice_namespace.backend
            let namespace = response.data.wiki_queryservice_namespace.namespace
            var parsed = parse('http://' + host + url, true)
            parsed.set('pathname', '/bigdata/namespace/' + namespace)
            parsed.set('hostname', backend)
            // unset the port as it is set in the backend host...
            parsed.set('port', '')

            return parsed.toString()
        })
        .catch(function (err) {
            console.error(err)
            throw err
        })

}

var responseCache = new TTLCache({
    ttl: parseInt(process.env.RESPONSE_CACHE_TTL || 60 * 60, 10) * 1000
})

function fetchWikiForDomain (wikiDomain) {
    var cachedResponse = responseCache.get(wikiDomain)
    if (cachedResponse) {
        return Promise.resolve(cachedResponse)
    }

    return new Promise(function (resolve, reject) {
        var xmlHttp = new XMLHttpRequest()
        xmlHttp.open(
            "GET",
            "http://"+process.env["PLATFORM_API_BACKEND_HOST"]+"/backend/wiki/getWikiForDomain?domain=" + encodeURI(wikiDomain)
        );
        xmlHttp.setRequestHeader("User-Agent", "WBStack - Query Service - Gateway");
        xmlHttp.send(null);
        xmlHttp.onreadystatechange = function() {
            if (this.readyState === 4) {
                if (this.status === 200) {
                    try {
                        resolve(JSON.parse(xmlHttp.responseText))
                    } catch (err) {
                        reject(err)
                    }
                    return
                }
                reject(
                    new Error(`Unexpected status code ${this.status} with error: ${xmlHttp.responseText}`)
                )
            }
        };
    })
        .then(function (response) {
            responseCache.set(wikiDomain, response)
            return response
        })
}

module.exports = {
    defaultResolver: defaultResolver,
    domainForResolver: domainForResolver
};
