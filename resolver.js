var parse = require('url-parse'),
    XMLHttpRequest = require("xmlhttprequest").XMLHttpRequest

var domainForResolver = function domainForResolver(host, url, req) {

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

    // Try and log on failures (so this process keeps running...)
    try {

        var wikiDomain = domainForResolver(host,url,req)
        if(wikiDomain === null) {
            return null
        }

        // TODO should this be async?! What promise should i be returning...
        var xmlHttp = new XMLHttpRequest();
        xmlHttp.open(
            "GET",
            "http://"+process.env["PLATFORM_API_BACKEND_HOST"]+"/backend/wiki/getWikiForDomain?domain=" + encodeURI(wikiDomain),
            false // false for synchronous request
        );
        xmlHttp.setRequestHeader("User-Agent", "WBStack - Query Service - Gateway");
        xmlHttp.send(null);
        var response = JSON.parse(xmlHttp.responseText);

        if(!response.data) {
            return null
        }

        let backend = response.data.wiki_queryservice_namespace.backend;
        let namespace = response.data.wiki_queryservice_namespace.namespace;
        var parsed = parse('http://' + host + url, true);
        parsed.set('pathname', '/bigdata/namespace/' + namespace)
        parsed.set('hostname', backend)
        // unset the port as it is set in the backend host...
        parsed.set('port', '')

        return parsed.toString()

    }
    catch (error) {
        console.error(error);
    }

}

module.exports = {
    defaultResolver: defaultResolver,
    domainForResolver: domainForResolver
};
