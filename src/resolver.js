var parse = require('url-parse'),
    XMLHttpRequest = require("xmlhttprequest").XMLHttpRequest

var domainForResolver = function domainForResolver(host, url, req) {
    var wikiDomain = '';

    // TODO QSUI should send custom header when it knows what the wiki domain is? :)

    //// 1) Get suspected wiki URI from the query URIs

    // parse the url
    var parsed = parse('http://' + host + url, true);

    if (parsed.query.query) {
        var match = parsed.query.query.match(/\<(https?:\/\/([^\<\>\/]+))(\/(?:entity|prop|reference|value|wiki)(?:[^\<\>]+))\>/ig)
        if (match) {
            // Remove < and >
            for (var i = 0; i < match.length; i++) {
                match[i] = match[i].substring(1, match[i].length - 1);
            }
            // Make array unique
            match = match.filter((v, i, a) => a.indexOf(v) === i);

            if (match.length === 2) {
                //TODO log uncertainty?
            }
            if (match.length !== 0) {
                return parse(match[0]).host
            }
        }
    }

    //// 2) Get from origin or referer headers if possible (LOCALHOST only currently)
    // Localhost and the port come from the development docker-compose setup, and make things work locally
    if (req.headers.origin && req.headers.origin == "http://localhost:8084") {
        return "localhost"
    }
    if (req.headers.referer && req.headers.referer == "http://localhost:8084") {
        return "localhost"
    }

    return null
}

var defaultResolver = function resolver(host, url, req) {

    // Try and log on failures (so this process keeps running...)
    try {

        var wikiDomain = domainForResolver(host,url,req)
        if(wikiDomain === null) {
            return null
        }

        //// 2) Make request to API using suspected wiki URI

        // TODO should this be async?! What promise should i be returning...
        // TODO api should be in ENV VAR....
        var xmlHttp = new XMLHttpRequest();
        xmlHttp.open("GET", "http://api:80/backend/wiki/getWikiForDomain?domain=" + encodeURI(wikiDomain), false); // false for synchronous request
        xmlHttp.send(null);
        var response = JSON.parse(xmlHttp.responseText);

        //TODO make sure response looks good?

        //// 3) Setup proxying..

        let backend = response.data.wiki_queryservice_namespace.backend;
        let namespace = response.data.wiki_queryservice_namespace.namespace;
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
