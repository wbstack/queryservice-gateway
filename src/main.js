// TODO make this more resiliant and log stuff?

var parse = require('url-parse'),
    XMLHttpRequest = require("xmlhttprequest").XMLHttpRequest,
    proxy = new require('redbird')({
    port: 80,
    resolvers: [
        function(host, url, req) {

            //// 1) Get suspected wiki URI from the query

            // parse the url
            var parsed = parse('http://'+host+url, true);

            // get the query requested
            var query = parsed.query.query

            var match = query.match(/\<(https?:\/\/([^\<\>\/]+))(\/(?:entity|prop|reference|value|wiki)(?:[^\<\>]+))\>/ig)
            if(match === null){
                return null;
            }
            // Remove < and >
            for (var i = 0; i < match.length; i++) {
                match[i] = match[i].substring(1, match[i].length-1);
            }
            // Make array unique
            match = match.filter((v, i, a) => a.indexOf(v) === i);

            if(match.length === 2) {
                //TODO log uncertainty?
            }
            if(match.length === 0) {
                return null
            }

            var wikiDomain = parse(match[0])

            //// 2) Make request to API using suspected wiki URI

            // TODO should this be async?! What promise should i be returning...
            // TODO api should be in ENV VAR....
            var xmlHttp = new XMLHttpRequest();
            xmlHttp.open( "GET", "http://api:80/backend/wiki/getWikiForDomain?domain="+encodeURI(wikiDomain.host), false ); // false for synchronous request
            xmlHttp.send( null );
            var response = JSON.parse(xmlHttp.responseText);

            //TODO make sure response looks good?

            //// 3) Setup proxying..


            let backend = response.data.wiki_queryservice_namespace.backend;
            let namespace = response.data.wiki_queryservice_namespace.namespace;
            parsed.set('pathname', '/bigdata/namespace/'+namespace)
            parsed.set('hostname', backend)
            // unset the port as it is set in the backend host...
            parsed.set('port', '')

            return parsed.toString()
        }]
})
